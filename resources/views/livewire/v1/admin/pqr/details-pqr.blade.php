@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"PQR"
        ])

    {{--optiones de cabecera de formulario--}}
    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.peticiones.listado",
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
                                                                             "key"=>"Código",
                                                                             "value"=>$model->code
                                                                         ],
                                                                         [
                                                                             "key"=>"Solicitante",
                                                                             "value"=>$model->sender()->identification ." - ".$model->sender()->name,

                                                                         ],
                                                                         [
                                                                             "key"=>"Tipo de solicitante",
                                                                             "value"=>$model->senderType(),

                                                                         ],
                                                                         [
                                                                             "key"=>"Telefono de contacto",
                                                                             "value"=>$model->sender()->phone ,

                                                                         ],
                                                                         [
                                                                             "key"=>"Correo de contacto",
                                                                             "value"=>$model->sender()->email,

                                                                         ],
                                                                         [
                                                                             "key"=>"Cliente relacionado",
                                                                             "value"=>$model->client?($model->client->name." ". $model->client->last_name."-". $model->client->identification):"Sin cliente relacionado",
                                                                             "redirect_route"=>"v1.admin.client.detail.client",
                                                                             "redirect_binding"=>"client",
                                                                             "redirect_value"=>$model->client?$model->client->id:null,

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
                                                                             "type"=>"image",
                                                                             "value"=>$model->attach?$model->attach->url:"https://aom.enerteclatam.com/images/logo-horizontal.svg"
                                                                         ],

                                                                     ]
                                                            ]
                                                ],

                                                ]
         ])


</div>
