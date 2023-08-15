<?php

namespace App\Console\Commands\V1;


use App\Models\V1\User;
use App\Notifications\Alert\ServerAlertNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AlertMonitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:alert_monitor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitorea la salud del servidor principal';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $time_sleep = 120;
        while (true) {
            $mqttLaraverReceiverCommand = shell_exec("pm2 pid mqttLaraverReceiverCommand");
            $mosquittoServer = shell_exec("pm2 pid mosquittoServer");
            print('Prueba de conexion ' . date("Y/m/d h:i:sa") . "\n");
            $request = Http::get("http://3.12.98.178/healthcheck");
            if ($request->ok() and $mqttLaraverReceiverCommand and $mosquittoServer) {
                print("Conexion exitosa ... \n");
                sleep($time_sleep);
                continue;
            }
            print("Primer error de conexion reintentando...\n");
            sleep($time_sleep);
            $request = Http::get("http://3.12.98.178/healthcheck");
            if ($request->ok() and $mqttLaraverReceiverCommand and $mosquittoServer) {
                print("Conexion exitosa...\n");
                continue;
            }
            print("Segundo error de conexion enviando alerta...\n");
            $cellphones = [3209720220, 3103343616, 3163085286];
            foreach ($cellphones as $cellphone) {
                try {
                    User::wherePhone($cellphone)->first()->notifyNow(new ServerAlertNotification());
                } catch (\Throwable $error) {
                    print("No se logro notificar por whatsapp");
                }
            }

        }
    }


}
