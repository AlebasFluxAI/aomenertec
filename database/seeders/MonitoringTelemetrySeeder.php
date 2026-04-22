<?php

namespace Database\Seeders;

use App\Models\V1\Client;
use App\Models\V1\DailyMicrocontrollerData;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\MonthlyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MonitoringTelemetrySeeder extends Seeder
{
    public function run()
    {
        $clients = Client::whereIn('id', [1, 2])->get();

        foreach ($clients as $client) {
            $this->resetClientTelemetry($client);
            $this->seedMinuteTelemetry($client);
            $this->seedHourlyTelemetry($client);
            $this->seedDailyTelemetry($client);
            $this->seedMonthlyTelemetry($client);
        }
    }

    private function resetClientTelemetry(Client $client): void
    {
        MonthlyMicrocontrollerData::where('client_id', $client->id)->forceDelete();
        DailyMicrocontrollerData::where('client_id', $client->id)->forceDelete();
        HourlyMicrocontrollerData::where('client_id', $client->id)->forceDelete();
        MicrocontrollerData::where('client_id', $client->id)->forceDelete();
    }

    private function seedMinuteTelemetry(Client $client): void
    {
        $start = now()->subMinutes(59)->startOfMinute();

        for ($index = 0; $index < 60; $index++) {
            $timestamp = $start->copy()->addMinutes($index);
            $sequence = 900 + $index;

            $this->createMicroRow($client, $timestamp, $sequence, [
                'kwh_interval' => 0.28 + (($index % 5) * 0.015),
                'varLh_interval' => 0.10 + (($index % 4) * 0.01),
                'varCh_interval' => 0.07 + (($index % 3) * 0.008),
            ]);
        }
    }

    private function seedHourlyTelemetry(Client $client): void
    {
        $start = now()->subHours(71)->startOfHour();

        for ($index = 0; $index < 72; $index++) {
            $timestamp = $start->copy()->addHours($index);
            $sequence = 400 + $index;
            $kwhInterval = 4.8 + (($index % 8) * 0.35);
            $varLhInterval = 1.1 + (($index % 6) * 0.12);
            $varChInterval = 0.7 + (($index % 5) * 0.09);

            $micro = $this->createMicroRow($client, $timestamp, $sequence, [
                'kwh_interval' => $kwhInterval,
                'varLh_interval' => $varLhInterval,
                'varCh_interval' => $varChInterval,
            ]);

            HourlyMicrocontrollerData::create([
                'year' => (int) $timestamp->format('Y'),
                'month' => (int) $timestamp->format('m'),
                'day' => (int) $timestamp->format('d'),
                'hour' => (int) $timestamp->format('H'),
                'client_id' => $client->id,
                'microcontroller_data_id' => $micro->id,
                'interval_real_consumption' => $kwhInterval,
                'interval_reactive_capacitive_consumption' => $varChInterval,
                'interval_reactive_inductive_consumption' => $varLhInterval,
                'penalizable_reactive_capacitive_consumption' => round($varChInterval * 0.65, 4),
                'penalizable_reactive_inductive_consumption' => round($varLhInterval * 0.70, 4),
                'raw_json' => json_encode($this->buildTelemetryPayload($timestamp, $sequence, $kwhInterval, $varLhInterval, $varChInterval)),
                'source_timestamp' => $timestamp->format('Y-m-d H:i:s'),
            ]);
        }
    }

    private function seedDailyTelemetry(Client $client): void
    {
        $start = now()->subDays(30)->startOfDay();

        for ($index = 0; $index < 31; $index++) {
            $day = $start->copy()->addDays($index);
            $timestamp = $day->isToday()
                ? now()->copy()->startOfHour()
                : $day->copy()->setTime(23, 0);
            $sequence = 200 + $index;
            $kwhInterval = 112 + (($index % 7) * 4.5);
            $varLhInterval = 33 + (($index % 5) * 1.8);
            $varChInterval = 19 + (($index % 4) * 1.3);

            $micro = $this->createMicroRow($client, $timestamp, $sequence, [
                'kwh_interval' => $kwhInterval,
                'varLh_interval' => $varLhInterval,
                'varCh_interval' => $varChInterval,
            ]);

            DailyMicrocontrollerData::create([
                'year' => (int) $day->format('Y'),
                'month' => (int) $day->format('m'),
                'day' => (int) $day->format('d'),
                'client_id' => $client->id,
                'microcontroller_data_id' => $micro->id,
                'interval_real_consumption' => $kwhInterval,
                'interval_reactive_capacitive_consumption' => $varChInterval,
                'interval_reactive_inductive_consumption' => $varLhInterval,
                'penalizable_reactive_capacitive_consumption' => round($varChInterval * 0.65, 4),
                'penalizable_reactive_inductive_consumption' => round($varLhInterval * 0.70, 4),
                'raw_json' => json_encode($this->buildTelemetryPayload($timestamp, $sequence, $kwhInterval, $varLhInterval, $varChInterval)),
            ]);
        }
    }

    private function seedMonthlyTelemetry(Client $client): void
    {
        $start = now()->startOfMonth()->subMonths(12);

        for ($index = 0; $index < 12; $index++) {
            $month = $start->copy()->addMonths($index);
            $timestamp = $month->copy()->endOfMonth()->setTime(23, 0);
            $sequence = 50 + $index;
            $kwhInterval = 2850 + ($index * 55);
            $varLhInterval = 910 + ($index * 24);
            $varChInterval = 620 + ($index * 18);

            $micro = $this->createMicroRow($client, $timestamp, $sequence, [
                'kwh_interval' => $kwhInterval,
                'varLh_interval' => $varLhInterval,
                'varCh_interval' => $varChInterval,
            ]);

            MonthlyMicrocontrollerData::create([
                'year' => (int) $month->format('Y'),
                'month' => (int) $month->format('m'),
                'day' => (int) $month->endOfMonth()->format('d'),
                'client_id' => $client->id,
                'microcontroller_data_id' => $micro->id,
                'interval_real_consumption' => $kwhInterval,
                'interval_reactive_capacitive_consumption' => $varChInterval,
                'interval_reactive_inductive_consumption' => $varLhInterval,
                'penalizable_reactive_capacitive_consumption' => round($varChInterval * 0.65, 4),
                'penalizable_reactive_inductive_consumption' => round($varLhInterval * 0.70, 4),
                'raw_json' => json_encode($this->buildTelemetryPayload($timestamp, $sequence, $kwhInterval, $varLhInterval, $varChInterval)),
            ]);
        }
    }

    private function createMicroRow(Client $client, Carbon $timestamp, int $sequence, array $overrides = []): MicrocontrollerData
    {
        $kwhInterval = $overrides['kwh_interval'] ?? 5.2;
        $varLhInterval = $overrides['varLh_interval'] ?? 1.4;
        $varChInterval = $overrides['varCh_interval'] ?? 0.8;
        $payload = $this->buildTelemetryPayload($timestamp, $sequence, $kwhInterval, $varLhInterval, $varChInterval);

        return MicrocontrollerData::create([
            'client_id' => $client->id,
            'raw_json' => json_encode($payload),
            'accumulated_real_consumption' => $payload['import_wh'],
            'interval_real_consumption' => $kwhInterval,
            'accumulated_reactive_inductive_consumption' => $payload['varLh_acumm'],
            'accumulated_reactive_capacitive_consumption' => $payload['varCh_acumm'],
            'interval_reactive_inductive_consumption' => $varLhInterval,
            'interval_reactive_capacitive_consumption' => $varChInterval,
            'source_timestamp' => $timestamp->format('Y-m-d H:i:s'),
            'is_alert' => false,
            'manually' => true,
            'status' => MicrocontrollerData::SUCCESS_UNPACK,
        ]);
    }

    private function buildTelemetryPayload(Carbon $timestamp, int $sequence, float $kwhInterval, float $varLhInterval, float $varChInterval): array
    {
        $baseImport = 1200 + ($sequence * 5.5);
        $phase1Share = 0.34;
        $phase2Share = 0.33;
        $phase3Share = 0.33;

        $importWh = round($baseImport, 4);
        $varLhAccum = round(340 + ($sequence * 1.45), 4);
        $varChAccum = round(180 + ($sequence * 1.1), 4);

        return [
            'timestamp' => $timestamp->timestamp,
            'equipment_id' => 1,
            'flags' => 0,
            'import_wh' => $importWh,
            'import_VArh' => round($varLhAccum + $varChAccum, 4),
            'kwh_interval' => round($kwhInterval, 4),
            'varh_interval' => round($varLhInterval + $varChInterval, 4),
            'varLh_interval' => round($varLhInterval, 4),
            'varCh_interval' => round($varChInterval, 4),
            'varLh_acumm' => $varLhAccum,
            'varCh_acumm' => $varChAccum,
            'ph1_import_kwh' => round($importWh * $phase1Share, 4),
            'ph2_import_kwh' => round($importWh * $phase2Share, 4),
            'ph3_import_kwh' => round($importWh * $phase3Share, 4),
            'ph1_import_kvarh' => round(($varLhAccum + $varChAccum) * $phase1Share, 4),
            'ph2_import_kvarh' => round(($varLhAccum + $varChAccum) * $phase2Share, 4),
            'ph3_import_kvarh' => round(($varLhAccum + $varChAccum) * $phase3Share, 4),
            'ph1_kwh_interval' => round($kwhInterval * $phase1Share, 4),
            'ph2_kwh_interval' => round($kwhInterval * $phase2Share, 4),
            'ph3_kwh_interval' => round($kwhInterval * $phase3Share, 4),
            'ph1_varLh_acumm' => round($varLhAccum * $phase1Share, 4),
            'ph2_varLh_acumm' => round($varLhAccum * $phase2Share, 4),
            'ph3_varLh_acumm' => round($varLhAccum * $phase3Share, 4),
            'ph1_varCh_acumm' => round($varChAccum * $phase1Share, 4),
            'ph2_varCh_acumm' => round($varChAccum * $phase2Share, 4),
            'ph3_varCh_acumm' => round($varChAccum * $phase3Share, 4),
            'ph1_varLh_interval' => round($varLhInterval * $phase1Share, 4),
            'ph2_varLh_interval' => round($varLhInterval * $phase2Share, 4),
            'ph3_varLh_interval' => round($varLhInterval * $phase3Share, 4),
            'ph1_varCh_interval' => round($varChInterval * $phase1Share, 4),
            'ph2_varCh_interval' => round($varChInterval * $phase2Share, 4),
            'ph3_varCh_interval' => round($varChInterval * $phase3Share, 4),
            'ph1_varh_interval' => round(($varLhInterval + $varChInterval) * $phase1Share, 4),
            'ph2_varh_interval' => round(($varLhInterval + $varChInterval) * $phase2Share, 4),
            'ph3_varh_interval' => round(($varLhInterval + $varChInterval) * $phase3Share, 4),
            'ph1_volt' => round(119 + (($sequence % 3) * 1.7), 4),
            'ph2_volt' => round(120 + (($sequence % 4) * 1.4), 4),
            'ph3_volt' => round(121 + (($sequence % 5) * 1.1), 4),
            'ph1_current' => round(16 + (($sequence % 5) * 0.8), 4),
            'ph2_current' => round(15 + (($sequence % 4) * 0.75), 4),
            'ph3_current' => round(14 + (($sequence % 6) * 0.7), 4),
            'ph1_power' => round(2100 + (($sequence % 6) * 45), 4),
            'ph2_power' => round(2050 + (($sequence % 5) * 40), 4),
            'ph3_power' => round(1980 + (($sequence % 4) * 42), 4),
            'ph1_VA' => round(2250 + (($sequence % 6) * 50), 4),
            'ph2_VA' => round(2210 + (($sequence % 5) * 46), 4),
            'ph3_VA' => round(2175 + (($sequence % 4) * 44), 4),
            'ph1_VAr' => round(420 + (($sequence % 4) * 18), 4),
            'ph2_VAr' => round(390 + (($sequence % 5) * 16), 4),
            'ph3_VAr' => round(360 + (($sequence % 6) * 15), 4),
            'ph1_power_factor' => 0.94,
            'ph2_power_factor' => 0.95,
            'ph3_power_factor' => 0.96,
            'total_power_factor' => 0.95,
            'ph1_phase_angle' => 18,
            'ph2_phase_angle' => 17,
            'ph3_phase_angle' => 16,
            'total_phase_angle' => 17,
            'total_system_power' => round(6130 + (($sequence % 5) * 75), 4),
            'total_system_var' => round(1170 + (($sequence % 4) * 40), 4),
            'frequency' => 59.9,
            'ph1_ph2_volt' => 208.4,
            'ph2_ph3_volt' => 209.1,
            'ph3_ph1_volt' => 207.8,
            'ph1_volt_thd' => 1.8,
            'ph2_volt_thd' => 1.7,
            'ph3_volt_thd' => 1.9,
            'ph1_current_thd' => 2.4,
            'ph2_current_thd' => 2.3,
            'ph3_current_thd' => 2.5,
            'ph1_ph2_volt_thd' => 1.4,
            'ph2_ph3_volt_thd' => 1.5,
            'ph3_ph1_volt_thd' => 1.6,
            'volt_dc' => 51.8,
        ];
    }
}
