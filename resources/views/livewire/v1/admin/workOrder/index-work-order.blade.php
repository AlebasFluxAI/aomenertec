@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
@include("partials.v1.title",[
        "first_title"=>"Ordenes de",
        "second_title"=>" trabajo"
    ])

{{--optiones de cabecera de formulario--}}


@include("partials.v2.table.primary-table",[
            "table_headers"=>\App\Models\V1\WorkOrder::indexTableHeaders(),
           "table_actions"=>[

                              "customs"=>[
                                                [
                                                    "redirect"=>[
                                                               "route"=>"administrar.v1.ordenes_de_servicio.detalle",
                                                               "binding"=>"workOrder"
                                                         ],
                                                       "icon"=>"fas fa-search",
                                                       "tooltip_title"=>"Detalles",
                                                       "conditional" => "conditionalTypeReading",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::WORK_ORDER_DETAILS],
                                                 ],
                                                 [
                                                     "redirect"=>[
                                                               "route"=>"administrar.v1.ordenes_de_servicio.administrar",
                                                               "binding"=>"workOrder"
                                                         ],
                                                       "conditional"=>"adminWorkOrderConditional",
                                                       "icon"=>"fas fa-toolbox",
                                                       "tooltip_title"=>"Gestionar",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::WORK_ORDER_SOLVE],
                                                 ],
                                                 [
                                                     "redirect"=>[
                                                               "route"=>"administrar.v1.ordenes_de_servicio.editar",
                                                               "binding"=>"workOrder"
                                                         ],
                                                       "icon"=>"fas fa-pencil",
                                                       "tooltip_title"=>"Editar",
                                                       "conditional" => "conditionalTypeReading",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::WORK_ORDER_EDIT],
                                                 ],
                                                  ["redirect"=>[
                                                               "route"=>"v1.admin.client.hand_reading.detalle",
                                                               "binding"=>"microcontroller_data",
                                                               "binding_value"=>"microcontroller_data_id"

                                                         ],
                                                       "icon"=>"fas fa-info-circle",
                                                       "tooltip_title"=>"Detalle de lectura",
                                                       "conditional" => "conditionalManuallyDetail",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_HAND_READING_SHOW],
                                                 ],
                                                 ["redirect"=>[
                                                               "route"=>"v1.admin.client.hand_reading.crear",
                                                         ],
                                                       "icon"=>"fas fa-file-signature",
                                                       "tooltip_title"=>"Registrar lectura",
                                                       "conditional" => "conditionalManuallyCreate",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_HAND_READING_CREATE],
                                                 ],
                                                  [

                                                       "function"=>"setInProgress",
                                                       "icon"=>"fas fa-rotate-right",
                                                       "tooltip_title"=>"Iniciar orden de trabajo",
                                                       "conditional" => "conditionalStart",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::WORK_ORDER_IN_PROGRESS],
                                                 ],
                                                 [
                                                       "function"=>"processEquipmentReplace",
                                                       "conditional"=>"replaceEquipmentHandlerConditional",
                                                       "icon"=>"fas fa-computer",
                                                       "tooltip_title"=>"Gestionar cambio de equipo",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::PQR_EQUIPMENT_CHANGE_MANAGE],
                                                 ],

                                                 [
                                                        "function"=>"downloadReport",
                                                        "icon"=>"fas fa-file-download",
                                                        "tooltip_title"=>"Reporte de orden de trabajo",
                                                        "conditional"=>"canDownloadReport"
                                                ],
                                               ],
                                           ],
         "table_rows"=>$data

     ])
