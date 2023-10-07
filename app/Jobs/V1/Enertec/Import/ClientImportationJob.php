<?php

namespace App\Jobs\V1\Enertec\Import;

use App\Models\V1\Client;
use App\Models\V1\ClientSupervisor;
use App\Models\V1\Import;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class ClientImportationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $csvValues;
    public $import;
    public $admin;

    public function __construct($csvValues, $import, $admin)
    {
        $this->csvValues = $csvValues;
        $this->import = $import;
        $this->admin = $admin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->csvValues as $row) {
            $item = $this->import->items()->create([
                "status" => Import::STATUS_PENDING,
            ]);
            $errors = "";
            try {
                DB::transaction(function () use ($row, $item) {
                    $client = $this->createClient($row, $this->admin);
                    $this->linkSupervisor($client, $row);
                    $this->linkBillingInformation($client, $row);
                    $this->linkTechnician($client, $row, $item);
                    $this->linkAddressInformation($client, $row);
                    $item->update([
                        "importable_type" => Client::class,
                        "importable_id" => $client->id,
                        "status" => Import::STATUS_PROCESSING,
                    ]);
                });

            } catch (\Throwable $t) {
                $item->update([
                    "status" => Import::STATUS_ERROR,
                    "error" => $t->getMessage()
                ]);
                continue;
            }
            $item->update([
                "status" => Import::STATUS_COMPLETED,
            ]);

        }
    }

    private function linkSupervisor(Client $client, $importValues)
    {
        $createSupervisor = $this->getModelArray($this->mapHeadersCreateSupervisor(), $importValues);

        if (!$createSupervisor["create_supervisor"]) {
            return;
        }
        $supervisor = Supervisor::create(
            [
                "name" => $client->name,
                "last_name" => $client->last_name ?? "",
                "email" => $client->email,
                "phone" => $client->phone,
                "network_operator_id" => $client->network_operator_id,
                "identification" => $client->identification,

            ]
        );

        $user = User::create([
            "name" => $client->name,
            "last_name" => $client->last_name ?? "",
            "email" => $client->email,
            "phone" => $client->phone,
            "network_operator_id" => $client->network_operator_id,
            "identification" => $client->identification,
            "type" => User::TYPE_SUPERVISOR

        ]);
        $supervisor->update([
            "user_id" => $user->id
        ]);

        ClientSupervisor::create([
            "client_id" => $client->id,
            "supervisor_id" => $supervisor->id,
            "active" => true
        ]);
    }

    private function linkTechnician(Client $client, $importValues, $item)
    {
        $technicianInformation = $this->getModelArray($this->mapHeadersTechnicianInformation(), $importValues);
        if (!($technicianInformation["technician_id"])) {
            return;
        }
        $technician = Technician::find($technicianInformation["technician_id"]);
        $networkOperatorId = $technicianInformation["network_operator_id"];
        if ($technician->network_operator_id != $networkOperatorId) {
            $item->update([
                "error" => $item->error . ", " . "Tecnico no pertecene a operador de red"
            ]);
            return;
        }
        $client->technician()->create($technicianInformation);
    }

    private function linkBillingInformation(Client $client, $importValues)
    {
        $billingInformationArray = $this->getModelArray($this->mapHeadersBillingInformation(), $importValues);
        $billingInformationArray = array_merge($billingInformationArray, ["default" => true]);
        $client->billingInformation()->create($billingInformationArray);
    }

    private function linkAddressInformation(Client $client, $importValues)
    {
        $billingInformationArray = $this->getModelArray($this->mapHeadersAddressInformation(), $importValues);
        $client->addresses()->create($billingInformationArray);
    }

    private function createClient($importValues, $admin)
    {
        while (true) {
            $code = $this->clientCode();
            if (!(Client::whereCode($code)->exists())) {
                break;
            }
        }
        $clientArray = $this->getModelArray($this->mapHeadersClientBase(), $importValues);
        if (!array_key_exists("code", $clientArray)) {
            $clientArray = array_merge($clientArray, ["code" => $code]);
        }
        $clientArray = array_merge($clientArray, ["admin_id" => $admin]);

        return Client::create($clientArray);
    }

    private function getModelArray($modelMapper, $importValues)
    {
        $resultArray = [];
        foreach ($modelMapper as $key => $value) {
            if (!array_key_exists($key, $importValues)) {
                continue;
            }
            $fieldValue = $importValues[$key];
            if ($value == "person_type") {
                $fieldValue = strtolower($fieldValue);
            }
            $resultArray = array_merge($resultArray, [$value => $fieldValue]);
        }
        return $resultArray;
    }

    private function mapHeadersClientBase()
    {
        return [
            "NOMBRE" => "name",
            "CODE" => "code",
            "APELLIDO" => "last_name",
            "ALIAS" => "alias",
            "TELEFONO" => "phone",
            "INDICATIVO_TELEFONO" => "indicative",
            "EMAIL" => "email",
            "TIPO_PERSONA" => "person_type",
            "TIPO_IDENTIFICACION" => "identification_type",
            "IDENTIFICACION" => "identification",

        ];
    }

    private function mapHeadersAddressInformation()
    {
        return [
            "DETALLES_DIRECCION" => "details",
            "DIRECCION_LATITUD" => "latitude",
            "DIRECCION_LONGITUD" => "longitude",
            "DIRECCION" => "address",
        ];

    }

    private function mapHeadersCreateSupervisor()
    {
        return [
            "CREAR_SUPERVISOR" => "create_supervisor",
        ];

    }

    private function mapHeadersTechnicianInformation()
    {
        return [
            "ASOCIAR_TECNICO" => "technician_id",
            "ASOCIAR_OPERADOR_DE_RED" => "network_operator_id",

        ];

    }


    private function mapHeadersBillingInformation()
    {
        return [
            "TIPO_PERSONA_FACTURACION" => "person_type",
            "TIPO_IDENTIFICACION_FACTURACION" => "identification_type",
            "IDENTIFICACION_FACTURACION" => "identification",
            "TIPO_FACTURACION" => "type",
            "DIRECCION_FACTURACION" => "address",
        ];

    }

    public function clientCode($input = '0123456789', $strength = 10)
    {
        $input_length = strlen($input);
        $random_codigo = "";
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_codigo .= $random_character;
        }
        return $random_codigo;
    }
}
