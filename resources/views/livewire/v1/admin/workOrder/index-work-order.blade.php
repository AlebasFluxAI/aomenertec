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
         "table_headers"=>[
              [
                 "col_name" =>"ID",
                 "col_data" =>"id",
                 "col_filter"=>false
             ],
             [
                 "col_name" =>"Cliente",
                 "col_data" =>"client.name",
                 "col_filter"=>false
             ],
             [
                 "col_name" =>"Tecnico asignado",
                 "col_data" =>"technician.name",
                 "col_filter"=>false
             ],
             [
                 "col_name" =>"Tipo",
                 "col_translate"=>"work_order",
                 "col_data" =>"type",
                 "col_filter"=>false
             ],
             [
                 "col_name" =>"Estado",
                 "col_translate"=>"work_order",
                 "col_data" =>"status",
                 "col_filter"=>false
             ],
             [
                 "col_name" =>"Descripción",
                 "col_data" =>"description",
                 "col_filter"=>false
             ],
          ],
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

                                                       "conditional"=>"setOpenWorkOrderConditional",
                                                       "function"=>"setOpen",
                                                       "icon"=>"fas fa-pause",
                                                       "tooltip_title"=>"Pausar orden de trabajo",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::WORK_ORDER_STOP],
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
