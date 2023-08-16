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
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::WORK_ORDER_EDIT],
                                                 ],
                                                  [

                                                       "conditional"=>"setInProgressWorkOrderConditional",
                                                       "function"=>"setInProgress",
                                                       "icon"=>"fas fa-rotate-right",
                                                       "tooltip_title"=>"Iniciar orden de trabajo",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::WORK_ORDER_IN_PROGRESS],
                                                 ],
                                                 [
                                                       "function"=>"processEquipmentReplace",
                                                       "conditional"=>"replaceEquipmentHandlerConditional",
                                                       "icon"=>"fas fa-computer",
                                                       "tooltip_title"=>"Gestionar cambio de equpo",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::PQR_EQUIPMENT_CHANGE_MANAGE],
                                                 ],
                                               ],
                                           ],
         "table_rows"=>$data

     ])
