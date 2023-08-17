@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
@include("partials.v1.title",[
        "first_title"=>"Detalles de",
        "second_title"=>"lectura manual"
    ])

{{--optiones de cabecera de formulario--}}

@include("partials.v1.table_nav",
     ["mt"=>4,"nav_options"=>[
                [
                    "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_HAND_READING],
                    "button_align"=>"right",
                "click_action"=>"",
                "button_icon"=>"fas fa-list",
                "button_content"=>"Ver listado",
                "target_route"=>"v1.admin.client.hand_reading",
                "target_binding"=>"client",
                "target_binding_value"=>$client->id
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
                                                                         "key"=>"Cliente",
                                                                         "value"=>$client?($client->name." ". $client->last_name."-". $client->identification):"Sin cliente relacionado",
                                                                         "redirect_route"=>"v1.admin.client.detail.client",
                                                                         "redirect_binding"=>"client",
                                                                         "redirect_value"=>$client->id,
                                                                     ],
                                                                     [
                                                                             "key"=>"Tecnico asignado",
                                                                             "value"=>$client->technician?$client->clientTechnician[0]->name." ".$client->clientTechnician[0]->last_name."-".$client->clientTechnician[0]->identification:"",
                                                                             "redirect_route"=>"administrar.v1.usuarios.tecnicos.detalles",
                                                                             "redirect_binding"=>"technician",
                                                                             "redirect_value"=>$client->clientTechnician[0]->technician_id
                                                                     ],

                                                                     [
                                                                          "key"=>"Fecha de lectura",
                                                                          "value"=>$model->source_timestamp
                                                                     ],
                                                                     [
                                                                          "key"=>"Consumo activo(Kwh)",
                                                                          "value"=>$model->accumulated_real_consumption
                                                                     ],
                                                                     [
                                                                          "key"=>"Consumo reactivo(Kwh)",
                                                                          "value"=>$model->accumulated_reactive_consumption
                                                                     ],
                                                                     [
                                                                          "key"=>"Imagenes adjuntas",
                                                                          "type"=>"image_multiple",
                                                                           "value"=>$model->images,
                                                                     ],

                                                                 ]
                                                        ]
                                            ],

                                            ]
     ])






