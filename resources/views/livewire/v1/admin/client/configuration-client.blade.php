@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Configurar",
            "second_title"=>"equipo de cliente"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Listado de clientes",
                    "target_route"=>"v1.admin.client.list.client",
                    ],

                ]
        ])

    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Conexión",

                                                ],
                                                [
                                                    "title"=>"Alertas",

                                                ],

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v2.form.primary_form",
                                                    "view_values"=>  [
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
                                                                                                "icon_class"=>"fas fa-barcode",
                                                                                                "placeholder"=>"Red Wifi",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "updated_input" => "defer",
                                                                                                "required"=>true
                                                                                            ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.wifi_password",
                                                                                                "icon_class"=>"fas fa-barcode",
                                                                                                "placeholder"=>"Contraseña WiFi",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "updated_input" => "defer",
                                                                                                "required"=>true
                                                                                            ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.mqtt_host",
                                                                                                "icon_class"=>"fas fa-barcode",
                                                                                                "placeholder"=>"Servidor MQTT",
                                                                                                "updated_input"=>"defer",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,

                                                                                            ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.mqtt_port",
                                                                                                "icon_class"=>"fas fa-barcode",
                                                                                                "placeholder"=>"Puerto MQTT",
                                                                                                "updated_input" => "defer",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,

                                                                                            ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.mqtt_password",
                                                                                                "icon_class"=>"fas fa-barcode",
                                                                                                "placeholder"=>"Contraseña MQTT",
                                                                                                "updated_input"=>"defer",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,

                                                                                            ], [
                                                                                                "input_type"=>"text",
                                                                                                "input_model"=>"client_config.mqtt_user",
                                                                                                "icon_class"=>"fas fa-barcode",
                                                                                                "placeholder"=>"Usuario MQTT",
                                                                                                "updated_input"=>"defer",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,

                                                                                            ],
                                                                                            [
                                                                                                "input_type"=>"number",
                                                                                                "input_model"=>"client_config.digital_outputs",
                                                                                                "icon_class"=>"fas fa-barcode",
                                                                                                "placeholder"=>"Salidas disponibles",
                                                                                                "offset"=>2,
                                                                                                "updated_input"=>"lazy",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,

                                                                                            ], [
                                                                                                "input_type"=>"divider",
                                                                                                "title"=>"Configuraciones de muestreo"
                                                                                            ], [
                                                                                                "input_type"=>"number",
                                                                                                "input_model"=>"client_config.real_time_latency",
                                                                                                "placeholder"=>"Tiempo de muestreo en tiempo real",
                                                                                                "updated_input"=>"lazy",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "required"=>false,

                                                                                            ],
                                                                                            [
                                                                                                "input_type"=>"number",
                                                                                                "input_model"=>"client_config.storage_latency",
                                                                                                "placeholder"=>"Tiempo de muestreo monitoreo normal",
                                                                                                "col_with"=>6,
                                                                                                "click_action"=>"",
                                                                                                "updated_input" => "lazy",
                                                                                                "required"=>false,

                                                                                            ],
                                                                                ]
                                                            ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v2.form.primary_form",
                                                    "view_values"=>  [
                                                                        "form_toast"=>true,
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitFormAlert",
                                                                        "form_title"=>"",
                                                                        "form_inputs"=> $inputs

                                                            ]
                                                ],

                                          ]
         ])
@foreach($client_config_alert as $index => $item)
    <div wire:ignore.self class="modal fade" id="modal_{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel_{{ $item->id }}" aria-hidden="true">
        <div  class="modal-dialog modal-xl" role="document">
            <div  class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel_{{ $item->id }}">Seleccione las salidas relacionadas para control automatico</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div  class="modal-body">
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
                    @foreach($digital_outputs as $index => $output)
                        @include("partials.v1.form.check_button",[
                            "mt"=>0,
                            "mb"=>0,
                            "col_width"=>3,
                            "check_model"=>"checks.". $index .".output",
                            "check_label"=>$output->name,
                            "check_id"=>$index,

                            ])
                    @endforeach

                </div>
                <div class="modal-footer">
                    <button><a type="button" class="btn btn-secondary" data-dismiss="modal">Close</a></button>
                    <button><a wire:click="assignmentOutput('{{ $item->id }}','{{ $index }}')" type="button" class="btn btn-primary">Save changes</a></button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <script>
        document.addEventListener('livewire:load', function () {
            @this.on('closeModal', (e) => {

                $('#modal_'+e.id).hide()
                if ($('.modal-backdrop').is(':visible')) {
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        })
    </script>
</div>
