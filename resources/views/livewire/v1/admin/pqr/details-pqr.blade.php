@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"PQR"
        ])

    {{--optiones de cabecera de formulario--}}
    @include("partials.v1.table_nav",
         ["mt"=>4,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.peticiones.listado",
                    ],
                     [
                    "button_align"=>"right",
                    "button_type"=>"dropdown",
                    "button_icon"=>"fas fa-gear",
                    "button_content"=>"Acciones",
                    "button_options"=>$model->navigatorDropdownOptions()
                  ]
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
                                                                             "key"=>"Código",
                                                                             "value"=>$model->code
                                                                         ],
                                                                         [
                                                                             "key"=>"Solicitante",
                                                                             "value"=>($model->sender()?$model->sender()->identification:"" )." - ".($model->sender()?$model->sender()->name:""),

                                                                         ],
                                                                         [
                                                                             "key"=>"Tipo de solicitante",
                                                                             "value"=>$model->senderType(),

                                                                         ],
                                                                         [
                                                                             "key"=>"Telefono de contacto",
                                                                             "value"=>$model->sender()?$model->sender()->phone:"" ,

                                                                         ],
                                                                         [
                                                                             "key"=>"Correo de contacto",
                                                                             "value"=>$model->sender()?$model->sender()->email:"",

                                                                         ],
                                                                         [
                                                                             "key"=>"Cliente relacionado",
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
                                                                             "key"=>"Asunto",
                                                                             "value"=>$model->subject
                                                                         ],
                                                                          [
                                                                             "key"=>"Tipo",
                                                                             "value"=>$model->type,
                                                                             "translate"=>"pqr"
                                                                         ],
                                                                          [
                                                                             "key"=>"Categoria",
                                                                             "value"=>$model->sub_type,
                                                                             "translate"=>"pqr"
                                                                         ],
                                                                          [
                                                                             "key"=>"Nivel",
                                                                             "value"=>$model->level,
                                                                             "translate"=>"pqr"
                                                                         ],
                                                                          [
                                                                             "key"=>"Descripción",
                                                                             "value"=>$model->description,

                                                                         ],

                                                                          [
                                                                             "key"=>"Imagen adjunta",
                                                                             "type"=>$model->attach?"image":"text",
                                                                             "value"=>$model->attach?$model->attach->url:"Sin adjunto"
                                                                         ],
                                                                         [
                                                                              "key"=>"Descripcion de la solucion",
                                                                              "value"=>($model->closeMessage?$model->closeMessage->message:null),
                                                                              "show_column"=>($model->status==\App\Models\V1\Pqr::STATUS_CLOSED),
                                                                         ],
                                                                         [
                                                                              "key"=>"Evidencias de solucion",
                                                                              "type"=>"image",
                                                                              "value"=>($model->closeMessage?($model->closeMessage->attach?$model->closeMessage->attach->url:null):null),
                                                                              "show_column"=>($model->status==\App\Models\V1\Pqr::STATUS_CLOSED),
                                                                         ],
                                                                         [
                                                                             "key"=>"Fecha de apertura",
                                                                             "value"=>\Carbon\Carbon::parse($model->created_at)->format('d/m/Y - H:i:s'),

                                                                         ],
                                                                         [
                                                                             "key"=>"Fecha de cierre",
                                                                             "value"=>\Carbon\Carbon::parse($model->status_closed_at)->format('d/m/Y - H:i:s'),
                                                                              "show_column"=>($model->status==\App\Models\V1\Pqr::STATUS_CLOSED),
                                                                         ],
                                                                         [
                                                                             "key"=>"Tiempo de solucion",
                                                                             "value"=>\Carbon\Carbon::parse($model->status_closed_at)->diff(\Carbon\Carbon::parse($model->created_at))->format("%a Dias %h Horas  %i Minutos"),
                                                                              "show_column"=>($model->status==\App\Models\V1\Pqr::STATUS_CLOSED),
                                                                         ],
                                                                            [
                                                                             "key"=>"Order de trabajo",
                                                                             "value"=>$model->workOrder?($model->workOrder->id).". ".$model->workOrder->description:"",
                                                                             "show_column"=>($model->workOrder!=null),
                                                                             "redirect_route"=>"administrar.v1.ordenes_de_servicio.detalle",
                                                                             "redirect_binding"=>"workOrder",
                                                                             "redirect_value"=>($model->workOrder?$model->workOrder->id:1)
                                                                         ],
                                                                         ]
                                                            ]
                                                ],

                                                ]
         ])


</div>
