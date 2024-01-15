<?php

namespace App\Jobs\V1\Enertec\Import;

use App\Models\V1\BillingInformation;
use App\Models\V1\Client;
use App\Models\V1\ClientSupervisor;
use App\Models\V1\ClientType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentClient;
use App\Models\V1\Import;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


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
    public $networkOperator;

    public function __construct($csvValues, $import, $admin, $networkOperator)
    {
        $this->csvValues = $csvValues;
        $this->import = $import;
        $this->admin = $admin;
        $this->networkOperator = $networkOperator;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $index = 0;
        foreach ($this->csvValues as $row) {
            $index++;
            $item = $this->import->items()->create([
                "status" => Import::STATUS_PENDING,
                "item_index" => $index
            ]);
            $errors = [];
            try {
                $client = $this->createClient($row, $this->admin, $errors);
                if (!$client) {
                    throw new \Exception();
                }
                $this->linkConnectionInformation($client, $row, $errors);
                $this->linkBillingInformation($client, $row, $errors);
                $this->linkTechnician($client, $row, $item, $errors);
                $this->linkSupervisor($client, $row, $errors);
                $this->linkAddressInformation($client, $row, $errors);
                $this->linkEquipments($client, $row, $errors);
                $item->update([
                    "importable_type" => Client::class,
                    "importable_id" => $client->id,
                    "status" => Import::STATUS_PROCESSING,
                ]);

            } catch (\Throwable $t) {
                if ($t->getMessage()) {
                    $this->addErrorToArray($errors, ["Error en creacion de cliente base" => $t->getMessage()]);
                }
                $item->update([
                    "status" => Import::STATUS_ERROR,
                    "error" => json_encode($errors)
                ]);
                continue;
            }
            $item->update([
                "status" => Import::STATUS_COMPLETED,
                "error" => json_encode($errors)
            ]);

        }
    }

    private function createClient($importValues, $admin, &$errors)
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
        if ($this->networkOperator) {
            $clientArray = array_merge($clientArray, ["network_operator_id" => $this->networkOperator->id]);
        }


        //if (!$this->validateClientData($importValues, $errors)) {
        //    return null;
        //}
        return Client::create($clientArray);
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

    private function getModelArray($modelMapper, $importValues)
    {
        $resultArray = [];
        foreach ($modelMapper as $key => $value) {
            if (!array_key_exists($key, $importValues)) {
                continue;
            }
            $fieldValue = $importValues[$key];
            if ($value == "person_type") {
                $fieldValue = $fieldValue == "JURIDICA" ? "juridical" : strtolower($fieldValue);
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

    private function linkConnectionInformation(Client $client, $importValues, &$errors)
    {
        try {
            $connectionInfo = $this->getModelArray($this->mapHeadersNetworkTopologyInformation(), $importValues);
            $connectionValues = $this->mapValuesNetworkConnectionInformation();
            if ($connectionInfo["subsistence_consumption"]) {
                $subValue = $connectionInfo["subsistence_consumption"];
                $subsistenceConsumption = SubsistenceConsumption::whereValue($subValue)->first();
                if (!$subsistenceConsumption) {
                    $this->addErrorToArray($errors, ["Error al asociar subsidio" => "Subsidio con valor $subValue no existe"]);
                } else {
                    $client->update([
                        "subsistence_consumption_id" => $subsistenceConsumption->id
                    ]);
                }
            }
            $client->update([
                "network_topology" => $connectionValues[$connectionInfo["network_topology"]],
                "client_type_id" => ClientType::whereType($connectionValues[$connectionInfo["client_type"]])->first()->id,
                "public_lighting_tax" => $connectionInfo["public_lighting_tax"] == "SI"
            ]);
        } catch (\Throwable $error) {
            $this->addErrorToArray($errors, ["Error al asociar informacion de conexion" => $error->getMessage()]);
        }
    }

    private function mapHeadersNetworkTopologyInformation()
    {
        return [
            "TOPOLOGIA_RED" => "network_topology",
            "TIPO_CONEXION" => "client_type",
            "CLIENTE_CON_CONTRIBUCION" => "contribution",
            "CLIENTE_CON_IMPUESTO_ALUMBRADO" => "public_lighting_tax",
            "TIPO_DE_SUBSIDIO" => "subsistence_consumption"
        ];

    }

    private function mapValuesNetworkConnectionInformation()
    {
        return [
            "MONO" => Client::MONOPHASIC,
            "BI" => Client::BIPHASIC,
            "TRI" => Client::TRIPHASIC,
            "ZNI_CONV" => ClientType::ZIN_CONVENTIONAL,
            "ZNI_FOTO" => ClientType::ZIN_PHOTOVOLTAIC,
            "ZNI_RURAL" => ClientType::ZIN_RURAL,
            "SIN_CONV" => ClientType::SIN_CONVENTIONAL,
            "MON" => ClientType::MONITORING
        ];

    }

    private function addErrorToArray(&$errors, array $message)
    {
        $errors = array_merge($errors, $message);
    }

    private function linkBillingInformation(Client $client, $importValues, &$errors)
    {
        try {
            $billingInformationArray = $this->getModelArray($this->mapHeadersBillingInformation(), $importValues);
            $billingInformationArray = array_merge($billingInformationArray, ["default" => true]);
            $billingInformationArray = array_merge($billingInformationArray, ["type" => $this->mapValuesBillingInformation()[$billingInformationArray["type"]]]);
            $client->billingInformation()->create($billingInformationArray);
        } catch (\Throwable $error) {
            $this->addErrorToArray($errors, ["Error en creacion de informacion de facturacion" => $error->getMessage()]);
        }
    }

    private function mapHeadersBillingInformation()
    {
        return [
            "TIPO_PERSONA_FACTURACION" => "person_type",
            "TIPO_IDENTIFICACION_FACTURACION" => "identification_type",
            "IDENTIFICACION_FACTURACION" => "identification",
            "TIPO_FACTURACION" => "type",
            "DIRECCION_FACTURACION" => "address",
            "RAZON_SOCIAL" => "name"
        ];

    }

    private function mapValuesBillingInformation()
    {
        return [
            "PREPAGO" => BillingInformation::BILLING_TYPE_PREPAID,
            "POSTPAGO" => BillingInformation::BILLING_TYPE_POSTPAID,
        ];

    }

    private function linkTechnician(Client $client, $importValues, $item, &$errors)
    {
        $technicianInformation = $this->getModelArray($this->mapHeadersTechnicianInformation(), $importValues);
        if (!($technicianInformation["technician_id"])) {
            return;
        }
        $technician = Technician::find($technicianInformation["technician_id"]);
        $networkOperatorId = $technicianInformation["network_operator_id"];
        if (!$networkOperator = NetworkOperator::find($networkOperatorId)) {
            $this->addErrorToArray($errors, ["Error al asociar tecnico" => "Operador de red con identificador $networkOperatorId no existe"]);
        }

        if ($networkOperator->admin_id != $this->admin) {
            $this->addErrorToArray($errors, ["Error al asociar tecnico" => "Operador de red con identificador $networkOperatorId no pertenece al administrador {$this->admin}"]);
        }
        if ($technician->network_operator_id != $networkOperatorId) {
            $this->addErrorToArray($errors, ["Error al asociar tecnico" => "Tecnico no pertecene a operador de red"]);
            return;
        }
        $client->update(["network_operator_id" => $networkOperator->id]);
        $client->technician()->create($technicianInformation);
    }

    private function mapHeadersTechnicianInformation()
    {
        return [
            "ASOCIAR_TECNICO" => "technician_id",
            "ASOCIAR_OPERADOR_DE_RED" => "network_operator_id",

        ];

    }

    private function linkSupervisor(Client $client, $importValues, &$errors)
    {
        $createSupervisor = $this->getModelArray($this->mapHeadersCreateSupervisor(), $importValues);

        if (!$createSupervisor["create_supervisor"] && !$createSupervisor["has_telemetry"]) {
            return;
        }
        if ($createSupervisor["create_supervisor"] != "SI" && $createSupervisor["has_telemetry"] != "SI") {
            return;
        }
        try {
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
        } catch (\Throwable $error) {
            $this->addErrorToArray($errors, ["Error en creacion de supervisor" => $error->getMessage()]);
        }

    }

    private function mapHeadersCreateSupervisor()
    {
        return [
            "CREAR_SUPERVISOR" => "create_supervisor",
            "TIENE_TELEMETRIA" => "has_telemetry",
        ];

    }

    private function linkAddressInformation(Client $client, $importValues, &$errors)
    {
        try {
            $billingInformationArray = $this->getModelArray($this->mapHeadersAddressInformation(), $importValues);
            $client->addresses()->create($billingInformationArray);
        } catch (\Throwable $error) {
            $this->addErrorToArray($errors, ["Error en creacion de informacion de direccion" => $error->getMessage()]);

        }
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

    private function linkEquipments(Client $client, $importValues, &$errors)
    {
        $equipmentInformation = $this->getModelArray($this->mapHeadersCreateEquipment(), $importValues);
        $equipmentTypeId = explode("/", $equipmentInformation["equipments_serial_type"]);
        $equipmentSerials = explode("/", $equipmentInformation["equipments_serial"]);
        if (count($equipmentTypeId) != count($equipmentSerials)) {
            $this->addErrorToArray($errors, ["Error al asociar equipos" => "La cantidad de tipos de equipo no coincide con el serial de los equipos"]);
            return;
        }
        foreach ($equipmentSerials as $key => $serial) {
            $equipment = Equipment::whereSerial($serial)->whereEquipmentTypeId($equipmentTypeId[$key])->first();
            if (!$equipment) {
                $this->addErrorToArray($errors, ["Error al asociar equipos" => "Equipo $serial no existe"]);
                return;
            }
            if ($equipment->assigned) {
                $this->addErrorToArray($errors, ["Error al asociar equipos" => "Equipo $serial asignado"]);
            }
            try {
                EquipmentClient::create([
                    'client_id' => $client->id,
                    'equipment_id' => $equipment->id,
                    'current_assigned' => true,
                ]);
                $equipment->update(['assigned' => true]);
            } catch (\Throwable $error) {

            }
        }
    }

    private function mapHeadersCreateEquipment()
    {
        return [
            "EQUIPOS_ASOCIADOS" => "equipments_serial",
            "TIPO_EQUIPOS_ASOCIADOS" => "equipments_serial_type",
        ];

    }

    private function validateClientData($importValues, &$errors): bool
    {
        $clientArray = $this->getModelArray($this->mapHeadersClientBase(), $importValues);
        $flag = true;
        if (Client::whereIdentification($clientArray["identification"])->exists()) {
            $this->addErrorToArray($errors, ["Error en creacion de cliente" => "El numero de identificacion {$clientArray["identification"]} ya es usado por otro cliente"]);
            $flag = false;
        }
        if (Client::wherePhone($clientArray["phone"])->exists()) {
            $this->addErrorToArray($errors, ["Error en creacion de cliente" => "El numero de telefono {$clientArray["phone"]} ya es usado por otro cliente"]);
            $flag = false;
        }
        return $flag;
    }
}
