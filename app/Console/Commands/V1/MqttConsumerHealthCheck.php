<?php

namespace App\Console\Commands\V1;

use App\Console\ConsumerCommand;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Verifica que el consumer MQTT esté vivo y procesando mensajes.
 *
 * El ConsumerCommand escribe un heartbeat en cache cada 30 segundos.
 * Si el heartbeat tiene más de 90 segundos de antigüedad, se considera
 * que el consumer perdió la conexión y se reinicia via Supervisor.
 *
 * Programado para correr cada 5 minutos vía Kernel::schedule().
 */
class MqttConsumerHealthCheck extends Command
{
    protected $signature = 'mqtt:health-check';

    protected $description = 'Verifica que el consumer MQTT esté vivo y reinicia si no responde';

    /**
     * Tiempo máximo (en segundos) sin heartbeat antes de considerar al consumer muerto.
     */
    private const STALE_THRESHOLD = 90;

    public function handle(): int
    {
        $heartbeat = Cache::get(ConsumerCommand::HEARTBEAT_CACHE_KEY);

        if (!$heartbeat) {
            $this->warn('No se encontró heartbeat del consumer MQTT — no ha arrancado o Redis no disponible.');
            Log::warning('MQTT health check: sin heartbeat, intentando reiniciar consumer.');
            $this->restartConsumer();
            return 1;
        }

        $elapsed = time() - $heartbeat['timestamp'];
        $pid = $heartbeat['pid'] ?? 'desconocido';

        if ($elapsed > self::STALE_THRESHOLD) {
            $this->error("Consumer MQTT sin respuesta hace {$elapsed}s (PID: {$pid}). Reiniciando...");
            Log::error("MQTT health check: heartbeat obsoleto ({$elapsed}s, PID: {$pid}). Reiniciando consumer.");
            $this->restartConsumer();
            return 1;
        }

        $this->info("Consumer MQTT saludable — último heartbeat hace {$elapsed}s (PID: {$pid}).");
        return 0;
    }

    /**
     * Reinicia el proceso mqtt-consumer via Supervisor.
     */
    private function restartConsumer(): void
    {
        $output = [];
        $exitCode = 0;
        exec('supervisorctl restart mqtt-consumer 2>&1', $output, $exitCode);

        $outputStr = implode("\n", $output);

        if ($exitCode === 0) {
            $this->info('Consumer MQTT reiniciado exitosamente.');
            Log::info('MQTT health check: consumer reiniciado exitosamente. Output: ' . $outputStr);
            // Limpiar heartbeat viejo para dar tiempo al nuevo consumer de arrancar
            Cache::forget(ConsumerCommand::HEARTBEAT_CACHE_KEY);
        } else {
            $this->error('Falló el reinicio del consumer MQTT: ' . $outputStr);
            Log::error('MQTT health check: falló reinicio de consumer. Exit code: ' . $exitCode . ' Output: ' . $outputStr);
        }
    }
}
