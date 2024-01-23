<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateMqttSender extends Command
{
    protected $signature = 'generate:mqtt-sender {className}';
    protected $description = 'Generate MqttSender class implementing MqttSenderInterface';

    public function handle()
    {
        $className = $this->argument('className');
        $filePath = app_path("Strategy/MqttSenderPattern/{$className}.php");

        if (File::exists($filePath)) {
            $this->error("El archivo {$className}.php ya existe.");
            return;
        }

        $content = $this->generateClassContent($className);

        File::put($filePath, $content);

        $this->info("Clase {$className}.php generada con éxito en Strategy/MqttSenderPattern.");
    }

    protected function generateClassContent($className)
    {

        return "<?php

namespace App\Strategy\MqttSenderPattern;

use Livewire\Component;
use PhpMqtt\Client\MqttClient;
use App\Strategy\MqttSenderPattern\MqttSenderInterface;
use App\Strategy\MqttSenderPattern\MqttSenderTrait;

class {$className} implements MqttSenderInterface
{
    use MqttSenderTrait;


    public function subscribeContext(\$message)
    {
        // Implementa la lógica para suscribirse al contexto aquí
    }

    public function registerLoopEventHandlerContext(float \$elapsedTime, MqttClient \$mqtt)
    {
        // Implementa la lógica para registrar un controlador de bucle de evento aquí
    }

    public function setMessage()
    {
        // Implementa la lógica para establecer el mensaje aquí
    }

    public function setTopic()
    {
        // Implementa la lógica para establecer el tema aquí
    }
}
";
    }
}
