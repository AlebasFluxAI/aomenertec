<?php

namespace App\Http\Repositories\Client;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ClientRepository
{
   // public function getEquipmentForType($request): Collection;

    public function addClient($request): Collection;
    public function getDateRangeClientDataPaginate($request, $id): LengthAwarePaginator;


}
