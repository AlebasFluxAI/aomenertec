@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Configurar",
            "second_title"=>"equipo de cliente ".($model->alias??$model->name),
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>4,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Listado de clientes",
                    "target_route"=>"v1.admin.client.list.client",
                    ],
                    [
                    "button_align"=>"right",
                    "button_type"=>"dropdown",
                    "button_icon"=>"fas fa-gear",
                    "button_content"=>"Acciones",
                    "button_options"=>$client->navigatorDropdownOptions()
                    ]

                ]
        ])

    @include("partials.v1.tab.v1.tab",[
                            "tab_titles"=>[
                                                [
                                                    "title"=>"Conexión",
                                                    "conditionable"=>true,
                                                    "permissions"=>[
                                                        \App\Models\V1\Admin::class=>\App\Models\V1\TabPermission::CLIENT_CONFIG_CONNECTION,
                                                        \App\Models\V1\NetworkOperator::class=>\App\Models\V1\TabPermission::CLIENT_CONFIG_CONNECTION,
                                                        \App\Models\V1\Technician::class=>\App\Models\V1\TabPermission::CLIENT_CONFIG_CONNECTION,
                                                        ]

                                                ],
                                                [
                                                    "title"=>"Conexión WIFI",
                                                ],
                                                [
                                                    "title"=>"Alertas",

                                                ],
                                                [
                                                    "title"=>"Alertas control",

                                                ],
                                                 [
                                                    "title"=>"Facturacion",
                                                    "conditionable"=>false,
                                                    "permissions"=>[
                                                        \App\Models\V1\Admin::class=>\App\Models\V1\TabPermission::CLIENT_BILLING_CONFIG
                                                        ]

                                                ],



                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v2.form.primary_form",
                                                    "view_values"=>  [
                                                                        "loading_state"=>true,
                                                                        "form_toast"=>true,
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitFormConection",
                                                                        "form_title"=>"",
                                                                        "form_inputs"=> [
                                                                                            [
                                                                                            "input_type"=>"divider",
                                                                                            "title"=>"Configuraciones de conexion"
                                                                                        ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.ssid",
                                                                                                "icon_class"=>null,
                                                                                                "placeholder"=>"Red Wifi",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "updated_input" => "defer",
                                                                                                "required"=>true
                                                                                            ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.wifi_password",
                                                                                                "icon_class"=>null,
                                                                                                "placeholder"=>"Contraseña WiFi",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "updated_input" => "defer",
                                                                                                "required"=>true
                                                                                            ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.mqtt_host",
                                                                                                "icon_class"=>null,
                                                                                                "placeholder"=>"Servidor MQTT",
                                                                                                "updated_input"=>"defer",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,

                                                                                            ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.mqtt_port",
                                                                                                "icon_class"=>null,
                                                                                                "placeholder"=>"Puerto MQTT",
                                                                                                "updated_input" => "defer",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,

                                                                                            ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.mqtt_password",
                                                                                                "icon_class"=>null,
                                                                                                "placeholder"=>"Contraseña MQTT",
                                                                                                "updated_input"=>"defer",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,

                                                                                            ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.mqtt_user",
                                                                                                "icon_class"=>null,
                                                                                                "placeholder"=>"Usuario MQTT",
                                                                                                "updated_input"=>"defer",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,

                                                                                            ],
                                                                                            [
                                                                                                "input_type"=>"number",
                                                                                                "input_model"=>"client_config.digital_outputs",
                                                                                                "icon_class"=>null,
                                                                                                "placeholder"=>"Salidas disponibles",
                                                                                                "offset"=>0,
                                                                                                "updated_input"=>"lazy",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,

                                                                                            ],
                                                                                            [
                                                                                                "input_type"=>"divider",
                                                                                                "title"=>"Configuraciones de muestreo"
                                                                                            ],
                                                                                            [
                                                                                             "input_model"=>"client_config.billing_day",
                                                                                             "updated_input"=>"defer",
                                                                                             "input_field"=>"",
                                                                                             "input_type"=>"select",
                                                                                             "icon_class"=>null,
                                                                                             "placeholder"=>"Dia de corte mensual",
                                                                                             "col_with"=>12,
                                                                                             "required"=>true,
                                                                                             "offset"=>'',
                                                                                             "data_target"=>'',
                                                                                             "placeholder_clickable"=>false,
                                                                                             "input_rows"=>0,
                                                                                             "select_options"=> \App\Models\V1\ClientConfiguration::STORAGE_LATENCY_OPTIONS[\App\Models\V1\ClientConfiguration::STORAGE_LATENCY_TYPE_MONTHLY],
                                                                                             "select_option_value"=>"value",
                                                                                             "select_option_view"=>"key",
                                                                                            ],
                                                                                            [
                                                                                                "input_type"=>"select",
                                                                                                "input_model"=>"client_config.storage_type_latency",
                                                                                                "placeholder"=>"Tipo de latencia de almacenamiento",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>true,
                                                                                                "select_options"=>$storage_latency_types,
                                                                                                "select_option_value"=>"value",
                                                                                                "select_option_view"=>"key",

                                                                                            ],
                                                                                            [
                                                                                                "input_type"=>"select",
                                                                                                "input_model"=>"client_config.storage_latency",
                                                                                                "placeholder"=>"Tasa ó dia de muestreo",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,
                                                                                                "select_options"=>$storage_latency_options,
                                                                                                "select_option_value"=>"value",
                                                                                                "select_option_view"=>"key",
                                                                                            ],
                                                                                            [
                                                                                                "input_type"=>"checkbox",
                                                                                                "input_model"=>"client_config.active_real_time",
                                                                                                "placeholder"=>"Habilitar tiempo real",
                                                                                                "updated_input"=>"lazy",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,
                                                                                            ],
                                                                                            [
                                                                                                "input_type"=>"number",
                                                                                                "input_model"=>"client_config.real_time_latency",
                                                                                                "placeholder"=>"Tiempo de muestreo en tiempo real (segundos)",
                                                                                                "updated_input"=>"lazy",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "disabled" => !$client_config->active_real_time,
                                                                                                "required"=>false,

                                                                                            ],
                                                                                            [
                                                                                                "input_type"=>"checkbox",
                                                                                                "input_model"=>"client_config.automatic_control",
                                                                                                "placeholder"=>"Habilitar control automatico",
                                                                                                "updated_input"=>"lazy",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>true,
                                                                                            ],

                                                                                ]
                                                            ]
                                                ],
                                                             [
                                                    "view_name"=>"partials.v2.form.primary_form",
                                                    "view_values"=>  [
                                                                        "loading_state"=>true,
                                                                        "form_toast"=>true,
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitFormConection",
                                                                        "form_title"=>"",
                                                                        "form_inputs"=> [
                                                                                            [
                                                                                            "input_type"=>"divider",
                                                                                            "title"=>"Configuraciones de conexion"
                                                                                        ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.ssid",
                                                                                                "icon_class"=>null,
                                                                                                "placeholder"=>"Red Wifi",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "updated_input" => "defer",
                                                                                                "required"=>true
                                                                                            ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.wifi_password",
                                                                                                "icon_class"=>null,
                                                                                                "placeholder"=>"Contraseña WiFi",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "updated_input" => "defer",
                                                                                                "required"=>true
                                                                                            ],

                                                                                ]
                                                            ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v2.form.primary_form",
                                                    "view_values"=>  [
                                                                        "loading_state"=>true,
                                                                        "form_toast"=>true,
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitFormAlert",
                                                                        "form_title"=>"",
                                                                        "form_inputs"=> $inputs

                                                            ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v2.form.primary_form",
                                                    "view_values"=>  [
                                                                        "loading_state"=>true,
                                                                        "form_toast"=>true,
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitFormControl",
                                                                        "form_title"=>"",
                                                                        "form_inputs"=> $inputs_control

                                                            ]
                                                ],
                                                               [
                                                    "view_name"=>"partials.v2.form.primary_form",
                                                    "view_values"=>  [
                                                                        "loading_state"=>true,
                                                                        "form_toast"=>true,
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitFormInvoicing",
                                                                        "form_title"=>"",
                                                                        "form_inputs"=> [
                                                                                            [
                                                                                            "input_type"=>"divider",
                                                                                            "title"=>"Configuraciones de facturacion"
                                                                                        ], [
                                                                                             "input_model"=>"invoicing_day",
                                                                                             "updated_input"=>"defer",
                                                                                             "input_field"=>"",
                                                                                             "input_type"=>"select",
                                                                                             "icon_class"=>null,
                                                                                             "placeholder"=>"Dia de corte mensual",
                                                                                             "col_with"=>6,
                                                                                             "required"=>true,
                                                                                             "offset"=>'',
                                                                                             "data_target"=>'',
                                                                                             "placeholder_clickable"=>false,
                                                                                             "input_rows"=>0,
                                                                                             "select_options"=> \App\Models\V1\ClientConfiguration::STORAGE_LATENCY_OPTIONS[\App\Models\V1\ClientConfiguration::STORAGE_LATENCY_TYPE_MONTHLY],
                                                                                             "select_option_value"=>"value",
                                                                                             "select_option_view"=>"key",
                                                                                            ],
                                                                                            [
                                                                                                "input_type"=>"checkbox",
                                                                                                "input_model"=>"client_config.automatic_control",
                                                                                                "placeholder"=>"Habilitar control automatico",
                                                                                                "updated_input"=>"lazy",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>true,
                                                                                            ]

                                                                                ]
                                                            ]
                                                ],


                                          ]
         ])
    @foreach($client_config_alert as $index => $item)
        <div wire:ignore.self class="modal fade" id="modal_{{ $item->id }}" tabindex="-1" role="dialog"
             aria-labelledby="ModalLabel_{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel_{{ $item->id }}">Seleccione las salidas relacionadas para
                            control automatico</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if($item->flag_id < 47)
                            @include('partials.v2.form.form_input_max_min',[
                                            "input_type"=>"input_min_max",
                                            "input_min_model"=> "client_config_alert.".$index.".min_control",
                                            "input_max_model"=>"client_config_alert.".$index.".max_control",
                                            "placeholder"=>$placeholders[$index],
                                            "col_with"=>12,
                                            "required"=>false,
                                            "updated_input" => "lazy",
                                            "placeholder_clickable"=>false,
                                            "data_target"=>"",
                                            "click_action" => "",
                                    ])
                        @else
                            @include('partials.v2.form.form_input_icon',[
                                            "input_type"=>"number",
                                            "offset"=>2,
                                            "input_model"=>"client_config_alert.".$index.".max_control",
                                            "placeholder"=>$placeholders[$index],
                                            "col_with"=>8,
                                            "updated_input" => "lazy",
                                            "required"=>false,
                                            "placeholder_clickable"=>true,
                                            "data_target"=>"modal_".$item['id'],
                                            "click_action" => "",
                                    ])
                        @endif
                        <div class="container">
                            @foreach($digital_outputs as $index => $output)
                                <div class="row">
                                    @include("partials.v1.form.check_button",[
                                        "col_width" => 6,
                                        "mt"=>0,
                                        "mb"=>0,
                                        "check_model"=>"checks.". $index .".output",
                                        "check_label"=>$output->name,
                                        "check_id"=>$index,

                                        ])
                                    @include("partials.v1.form.form_list",[
                                 "col_with"=>2,
                                 "input_type"=>"text",
                                 "list_model" => "checks.". $index .".control_status",
                                 "list_default" => "Estado de control ...",
                                 "list_options" => $control_options,
                                 "list_option_value"=>"value",
                                 "list_option_view"=>"key",
                                  "list_option_title"=>"",
                                 ])
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button><a type="button" class="btn btn-secondary" data-dismiss="modal">Close</a></button>
                        <button><a wire:click="assignmentOutput('{{ $item->id }}','{{ $index }}')" type="button"
                                   class="btn btn-primary">Save changes</a></button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script>
        document.addEventListener('livewire:load', function () {
        @this.on('closeModal', (e) => {

            $('#modal_' + e.id).hide()
            if ($('.modal-backdrop').is(':visible')) {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            }
        })
            console.log(@this.control_options)

        })
    </script>
</div>
