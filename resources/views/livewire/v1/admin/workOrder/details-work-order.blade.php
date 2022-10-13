@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
@include("partials.v1.title",[
        "first_title"=>"Detalles de",
        "second_title"=>"order de servicio"
    ])

{{--optiones de cabecera de formulario--}}

@include("partials.v1.table_nav",
     ["mt"=>4,"nav_options"=>[
                ["button_align"=>"right",
                "click_action"=>"",
                "button_icon"=>"fas fa-list",
                "button_content"=>"Ver listado",
                "target_route"=>"administrar.v1.ordenes_de_servicio.listado",
                ],

            ]
    ])

@include("partials.v1.tab.v1.tab",[

                        "tab_titles"=>[
                                            [
                                                "title"=>"Detalles",

                                            ],

                                       ],

                        "tab_contents"=>[
                                            [
                                                "view_name"=>"partials.v1.table.primary-details-table",
                                                "view_values"=>  [
                                                                    "table_info"=>[
                                                                     [
                                                                         "key"=>"Id",
                                                                         "value"=>$model->id
                                                                     ],
                                                                     [
                                                                         "key"=>"Descripción",
                                                                         "value"=>$model->description
                                                                     ],
                                                                     [
                                                                         "key"=>"Cliente",
                                                                         "value"=>$model->client?($model->client->name." ". $model->client->last_name."-". $model->client->identification):"Sin cliente relacionado",
                                                                         "redirect_route"=>"v1.admin.client.detail.client",
                                                                         "redirect_binding"=>"client",
                                                                         "redirect_value"=>$model->client_id,
                                                                     ],
                                                                     [
                                                                             "key"=>"Tecnico asignado",
                                                                             "value"=>$model->technician?$model->technician->name." ".$model->technician->last_name."-".$model->technician->identification:"",
                                                                             "redirect_route"=>"administrar.v1.usuarios.tecnicos.detalles",
                                                                             "redirect_binding"=>"technician",
                                                                             "redirect_value"=>$model->technician_id
                                                                     ],
                                                                     [
                                                                             "key"=>"Usuario de soporte asignado",
                                                                             "value"=>$model->support?$model->support->name." ".$model->support->last_name."-".$model->support->identification:"",
                                                                             "redirect_route"=>"administrar.v1.usuarios.soporte.detalles",
                                                                             "redirect_binding"=>"support",
                                                                             "redirect_value"=>$model->support_id
                                                                     ],
                                                                     [
                                                                          "key"=>"Herramientas",
                                                                          "value"=>$model->tools
                                                                     ],
                                                                     [
                                                                          "key"=>"Materiales",
                                                                          "value"=>$model->materials
                                                                     ],
                                                                     [
                                                                          "key"=>"Equipo a intervenir",
                                                                          "value"=>$model->equipments->first()?$model->equipments->first()->equipment->id." - ".$model->equipments->first()->equipment->serial:""
                                                                     ],
                                                                     [
                                                                          "key"=>"Tiempo estimado",
                                                                          "value"=>($model->days??"0")." Dias ".($model->hours??"0")." Horas ".($model->minutes??"0")." Minutos"
                                                                     ],
                                                                     [
                                                                          "key"=>"Estado",
                                                                          "value"=>__("work_order.".$model->status)
                                                                     ],
                                                                     [
                                                                          "key"=>"Imagenes adjuntas",
                                                                          "type"=>"image_multiple",
                                                                           "value"=>$model->images,
                                                                     ],
                                                                     [
                                                                          "key"=>"Descripcion de la solucion",
                                                                          "value"=>$model->solution_description,
                                                                          "show_column"=>($model->status==\App\Models\V1\WorkOrder::WORK_ORDER_STATUS_CLOSED),
                                                                     ],
                                                                     [
                                                                          "key"=>"Evidencias de solucion",
                                                                          "type"=>"image_multiple",
                                                                          "value"=>$model->evidences,
                                                                          "show_column"=>($model->status==\App\Models\V1\WorkOrder::WORK_ORDER_STATUS_CLOSED),
                                                                     ],

                                                                 ]
                                                        ]
                                            ],

                                            ]
     ])
