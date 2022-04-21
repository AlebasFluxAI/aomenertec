@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"Cliente"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"v1.admin.client.list.client",
                    ],

                ]
        ])


    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],
                                                [
                                                    "title"=>"Equipos",
                                                ]
                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>  [
                                                                        "table_info"=>[
                                                                         [
                                                                             "key"=>"Id",
                                                                             "value"=>$client->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Codigo",
                                                                             "value"=>$client->code
                                                                         ],
                                                                         [
                                                                             "key"=>"Nombre",
                                                                             "value"=>$client->name
                                                                         ],
                                                                         [
                                                                             "key"=>"Email",
                                                                             "value"=>$client->email
                                                                         ],
                                                                         [
                                                                             "key"=>"Operador de red",
                                                                             "value"=>$client->networkOperator->id. "- ". $client->networkOperator->name
                                                                         ],


                                                                     ]
                                                            ]
                                                ],
                                                [
                                                   "view_name"=>"partials.v1.form.primary_form",
                                                    "view_values"=>[
                                                                        "form_toast"=>true,
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitForm",
                                                                        "form_inputs"=>[
                                                                                        [
                                                                                                    "input_type"=>"list",
                                                                                                    "col_with"=>4,
                                                                                                     "list_model" => "client_type_id",
                                                                                                     "list_default" => "Tipo cliente...",
                                                                                                     "list_options" => $client_types,
                                                                                                     "list_option_value"=>"id",
                                                                                                     "list_option_view"=>"type",
                                                                                                     "list_option_title"=>"",
                                                                                                     "disabled"=>true

                                                                                        ],
                                                                                          [
                                                                                                    "input_type"=>"multi_input_equipment",
                                                                                                    "equipment"=>$equipment,
                                                                                                    "equipment_types"=>$equipment_types,
                                                                                                    "serials"=>$serials,
                                                                                        ],



                                                                                        ]
                                                                    ]
                                                ]
                                        ]
         ])


</div>

