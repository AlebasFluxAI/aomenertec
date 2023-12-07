<?php

namespace App\Http\Services\V1\Admin\Client;


use App\Http\Services\Singleton;
use App\Jobs\V1\Enertec\Import\ClientImportationJob;
use App\Models\V1\Import;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Livewire\Component;

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
        $string = utf8_encode(Storage::disk('s3')->get('imports/' . $fileName));
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
        $authModel = User::getUserModel();
        $networkOperator = $authModel;
        dispatch(new ClientImportationJob(iterator_to_array($csvValues), $import, $admin, $networkOperator))->onConnection("sync");
        $component->redirectRoute("v1.admin.client.import-details.client", ["import" => $import->id]);
    }

}
