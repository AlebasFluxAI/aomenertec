<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Precios",
            "second_title"=>"administrador"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.admin.listado",
                    ],

                ]
        ])

        @include("partials.v1.tab.v1.tab",[

                           "tab_titles"=>[
                                               [
                                                   "title"=>"Configuracion inicial",

                                               ],
                                               [
                                                   "title"=>"Precios",

                                               ],



                                          ],

                           "tab_contents"=>[


                                               [
                                                    "view_name"=>"partials.v1.form.primary_form",
                                                    "view_values"=>  [
                                                                        "form_toast"=>true,
                                                                        "class_container"=>"",
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitFormConfiguration",
                                                                        "form_title"=>"",
                                                                        "form_inputs"=> [

                                                                                         [
                                                                                                "input_type"=>"multiselect",
                                                                                                "input_label"=>"Asignar tipos de clientes",
                                                                                                "mb"=>2,
                                                                                                "options_list"=> $client_types,
                                                                                                "model_select"=>"admin_client_types",
                                                                                                "col_width"=>4,
                                                                                                "option_value"=>"id",
                                                                                                "option_view"=>"type",

                                                                                        ],
                                                                                        [    "input_model"=>"frame_type",
                                                                                             "updated_input"=>"defer",
                                                                                             "input_field"=>"",
                                                                                             "input_label"=>"Datos a monitorear",
                                                                                             "input_type"=>"select",
                                                                                             "icon_class"=>null,
                                                                                             "placeholder"=>"Datos",
                                                                                             "col_with"=>4,
                                                                                             "required"=>true,
                                                                                             "offset"=>'',
                                                                                             "data_target"=>'',
                                                                                             "placeholder_clickable"=>false,
                                                                                             "input_rows"=>0,
                                                                                             "select_options"=>$frame_types,
                                                                                             "select_option_value"=>"value",
                                                                                             "select_option_view"=>"key",
                                                                                            ],



                                                                                    ]
                                                                        ]
                                               ],
                                               [
                                                    "view_name"=>"partials.v2.form.primary_form",
                                                    "view_values"=>  [
                                                                        "form_toast"=>true,
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitFormPrice",
                                                                        "form_title"=>"",
                                                                        "form_inputs"=> [
                                                                                         [
                                                                                            "input_type"=>"divider",
                                                                                            "title"=>"Costo paquete minimo"
                                                                                         ],
                                                                                         [
                                                                                                "input_type"=>"number",
                                                                                                "input_model"=>"config.min_value",
                                                                                                "updated_input"=>"defer",
                                                                                                "icon_class"=>null,
                                                                                                "placeholder"=>"Cobro minimo",
                                                                                              "col_with"=>6,
                                                                                              "required"=>true,
                                                                                              "offset"=>'',
                                                                                              "data_target"=>'',
                                                                                              "placeholder_clickable"=>false,
                                                                                              "input_rows"=>0,
                                                                                            ],

                                                                                            ["input_model"=>"config.coin",
                                                                                             "updated_input"=>"defer",
                                                                                             "input_field"=>"",
                                                                                             "input_type"=>"select",
                                                                                             "icon_class"=>null,
                                                                                             "placeholder"=>"Moneda",
                                                                                             "col_with"=>6,
                                                                                             "required"=>true,
                                                                                             "offset"=>'',
                                                                                             "data_target"=>'',
                                                                                             "placeholder_clickable"=>false,
                                                                                             "input_rows"=>0,
                                                                                             "select_options"=>$coins,
                                                                                             "select_option_value"=>"value",
                                                                                             "select_option_view"=>"key",
                                                                                            ],
                                                                                            ["input_model"=>"config.min_clients",
                                                                                              "updated_input"=>"defer",
                                                                                              "input_field"=>"",
                                                                                              "input_type"=>"number",
                                                                                              "icon_class"=>null,
                                                                                              "placeholder"=>"Paquete minimo de clientes",
                                                                                              "col_with"=>12,
                                                                                              "required"=>true,
                                                                                              "offset"=>'',
                                                                                              "data_target"=>'',
                                                                                              "placeholder_clickable"=>false,
                                                                                              "input_rows"=>0,
                                                                                            ],
                                                                                            [
                                                                                            "input_type"=>"divider",
                                                                                            "title"=>"Costo por tipo de cliente activo"
                                                                                         ],
                                                                                         [
                                                                                           "data_foreach" => $prices,
                                                                                           "model_foreach" => "prices.",
                                                                                           "foreach_inputs" => [
                                                                                                                ["input_model"=>".value",
                                                                                                                  "updated_input"=>"defer",
                                                                                                                  "input_field"=>"",
                                                                                                                  "input_type"=>"number",
                                                                                                                  "icon_class"=>"fa-solid fa-circle-dollar-to-slot",
                                                                                                                  "placeholder"=>"clientType.type",
                                                                                                                  "placeholder_input"=>"Valor $",
                                                                                                                  "col_with"=>12,
                                                                                                                  "required"=>true,
                                                                                                                  "offset"=>'',
                                                                                                                  "data_target"=>'',
                                                                                                                  "placeholder_clickable"=>false,
                                                                                                                  "input_rows"=>0,
                                                                                                                  ]

                                                                                                                ],
                                                                                        ],

                                                                                    ]
                                                                        ]
                                               ],



                                  ]
        ])

</div>
