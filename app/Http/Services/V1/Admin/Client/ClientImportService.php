<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Resources\V1\IndicativeHelper;
use App\Http\Resources\V1\TimeZoneHelper;

use App\Http\Resources\V1\Icon;
use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;

use App\Jobs\V1\Enertec\Import\ClientImportationJob;
use App\Models\Traits\ClientServiceTrait;
use App\Models\V1\BillingInformation;
use App\Models\V1\ClientSupervisor;
use App\Models\V1\EquipmentClient;
use App\Models\V1\ClientType;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Import;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\Municipality;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Client;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Reader;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class ClientImportService extends Singleton
{

    public function mount(Component $component)
    {

    }

    public function import(Component $component)
    {
        $component->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);
        $import = Import::create([
            "auditable_id" => Auth::user()->id,
            "auditable_type" => User::class,
        ]);
        $fileName = "import_client_" . $import->id . ".csv";
        $component->file->storePubliclyAs('imports', $fileName, 's3');
        $url = Storage::disk('s3')->url('imports/' . $fileName);
        $string = Storage::disk('s3')->get('imports/' . $fileName);
        $import->update([
            "name" => "Importacion_clientes_" . $import->id,
            "type" => Import::TYPE_CLIENT,
            "status" => Import::STATUS_PROCESSING,
            "url" => $url,
            "file_name" => $component->file->getClientOriginalName()
        ]);
        $csv = Reader::createFromString($string, 'r');
        $csv->setHeaderOffset(0);
        $csvValues = $csv->getRecords();
        $admin = Auth::user()->getAdmin() ? Auth::user()->getAdmin()->id : null;
        dispatch(new ClientImportationJob(iterator_to_array($csvValues), $import, $admin))->onConnection("sync");
        $component->redirectRoute("v1.admin.client.import-details.client", ["import" => $import->id]);
    }

}
