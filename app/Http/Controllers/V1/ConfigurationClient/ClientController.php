<?php

namespace App\Http\Controllers\V1\ConfigurationClient;

use App\Http\Controllers\V1\Controller;
use App\Http\Services\V1\ConfigurationClient\ClientService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }
    public function addClient(Request $request): JsonResource
    {
        return $this->clientService->addClient($request);
    }

    public function getEquipmentForType(Request $request): JsonResource
    {
        return $this->clientService->getEquipmentForType($request);
    }

    public function addEquipment(Request $request): JsonResource
    {
        return $this->clientService->addEquipment($request);
    }

    public function getDateRangeSerial(Request $request): JsonResource
    {
        return $this->clientService->getDateRangeSerial($request);
    }

}
