<?php

namespace App\Http\Repositories\Client\Impl;

use App\Http\Repositories\Client\ClientRepository;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\BillingInformation;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentClient;
use App\Models\V1\EquipmentType;
use App\Models\V1\Api\EventLog;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class ClientRepositoryImpl implements ClientRepository
{


    public function getEquipmentForType($request): Collection
    {

        $serial = $request->input('serial');
        $key = ApiKey::where('api_key', $request->header('x-api-key'))->first();
        $user = User::getUserModel($key);
        $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
        return Equipment::where('network_operator_id', $user->id)->where('assigned', false)->where('has_clients', false)->where('equipment_type_id', $equipment_type->id)
            ->where('serial', 'like', '%'.$serial.'%')->get();
    }

    public function addClient($request): Collection
    {
        $data = $request->input('serial');
        DB::transaction(function () use ($data) {
            $client = $this->createClient();
            $this->linkEquipments($data, $client);
        });
        $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
        $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
            ->where('serial', $data)
            ->first();

        return $equipment->clients()->get();
    }

    private function createClient()
    {
        while (true) {
            $code = $this->clientCode();
            if (!(Client::whereCode($code)->exists())) {
                break;
            }
        }
        return Client::create([
            'name' => "Cliente ". $code,
            'last_name' => "enelar",
            'email' => $code."@default.com",
            'alias' => null,
            'code' => $code,
            'phone' => null,
            'identification' => $code,
            'network_topology' => Client::MONOPHASIC,
            'network_operator_id' => 7,
            'client_type_id' => 4,
            'subsistence_consumption_id' => 1,
            'voltage_level_id' => 1,
            'stratum_id' => 1,
            'identification_type' => Client::IDENTIFICATION_TYPE_CC,
            'person_type' => Client::PERSON_TYPE_NATURAL,
            "has_telemetry" => true,
            "admin_id" => 1,
        ]);
    }

    private function clientCode($input = '0123456789', $strength = 10)
    {
        $input_length = strlen($input);
        $random_codigo = "";
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_codigo .= $random_character;
        }
        return $random_codigo;
    }

    private function linkAddress($data, Client $client)
    {
        $client->addresses()->create([
            "latitude" => $data['latitude'] ?? 7.086593,
            "longitude" => $data['longitude'] ?? -70.758872,
            "details" => $data['address_details'],
        ]);
    }

    private function linkEquipments($data, Client $client)
    {
        $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
        $equipment = Equipment::where('equipment_type_id', $equipment_type->id)
            ->where('serial', $data)
            ->first();
        if ($equipment) {
            EquipmentClient::create([
                'client_id' => $client->id,
                'equipment_id' => $equipment->id,
                'current_assigned' => true,
            ]);
            $equipment->assigned = true;
            $equipment->has_clients = true;
            $equipment->save();
        }
    }

    private function createBillingInformation($data, Client $client)
    {
        $client->billingInformation()->create([
            "address" => $data['address_details'],
            "phone" => $client->phone,
            "identification" => $client->identification,
            "identification_type" => $client->identification_type,
            "name" => $client->name,
            "default" => true,
            "type" => BillingInformation::BILLING_TYPE_NONE
        ]);
    }

    public function getPagination(): LengthAwarePaginator
    {
        return Client::pagination();
    }

    public function getEventLogId()
    {
        return json_decode(Request::header(EventLog::EVENT_LOG_HEADER), true)["id"];
    }

    public function getDateRangeClientDataPaginate($request, $id): LengthAwarePaginator
    {
        if (is_numeric($request->query('fecha_inicio')) && is_numeric($request->query('fecha_fin'))) {
            $start =  Carbon::createFromTimestamp($request->query('fecha_inicio'));
            $end = Carbon::createFromTimestamp($request->query('fecha_fin'));
        } else {
            $start = new Carbon($request->query('fecha_inicio'));
            $end = new Carbon($request->query('fecha_fin'));
        }

        return HourlyMicrocontrollerData::where('client_id', $id)
            ->whereBetween("source_timestamp", [$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')])
            ->orderBy('source_timestamp', 'desc')->paginate();
    }
}
