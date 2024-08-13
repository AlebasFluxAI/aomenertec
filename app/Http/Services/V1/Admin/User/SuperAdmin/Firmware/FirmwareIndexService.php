<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\Singleton;
use App\Models\Model\V1\Firmware;
use App\Models\V1\SuperAdmin;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use Livewire\Component;

class FirmwareIndexService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function edit(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.superadmin.firmware.editar", ["firmware" => $modelId]);
    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.superadmin.firmware.detalles", ["firmware" => $modelId]);
    }

    public function delete(Component $component, $modelId)
    {
        $firmware = Firmware::find($modelId);
        $firmware->delete();
    }

    public function otaUpload(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.superadmin.firmware.cargar", ["firmware" => $modelId]);

    }

    public function downloadFile(Component $component, $modelId)
    {
        // URL del archivo en S3
        $firmware = Firmware::find($modelId);

        $evidence = $firmware->evidences()->first();

        if ($evidence){
            $fileUrl = $evidence->url;
            // Define un nombre para el archivo descargado
            $fileName = basename(parse_url($fileUrl, PHP_URL_PATH));

            try {
                // Realiza la solicitud HTTP para obtener el contenido del archivo
                $response = Http::get($fileUrl);

                // Verifica si la solicitud fue exitosa
                if ($response->successful()) {
                    // Obtiene el contenido del archivo
                    $fileContent = $response->body();
                    redirect($fileUrl);
                } else {
                    return response()->json(['message' => 'Failed to download file from URL'], 500);
                }
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to download file', 'error' => $e->getMessage()], 500);
            }
        }

    }

    public function getData(Component $component)
    {
        if ($component->filter) {
            return Firmware::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }
        return Firmware::pagination();
    }
}
