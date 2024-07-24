<div>
    <section class="login">
        @section("header")
            {{--extended app.blade--}}
        @endsection
        @if (session()->has('success'))
            <div class="alert alert-succes">
                {{ session('success') }}
            </div>
        @endif
        @include("partials.v1.title",[
                "first_title"=>"Añadir",
                "second_title"=>"Clientes"
            ])

        @include("partials.v1.table_nav",
     ["mt"=>2, "nav_options"=>[
                ["button_align"=>"right",
                "click_action"=>"",
                "button_icon"=>"fas fa-list",
                "button_content"=>"Ver listado",
                "target_route"=>"v1.admin.client.list.client",
                ],


            ]
    ])


        <div class="contenedor-grande">
            <div class="row content p-5">


                <form wire:submit.prevent="save" id="formulario" class="needs-validation" role="form">
                    {{--<div> &nbsp;&nbsp; <strong> Agregar manualmente</strong></div>--}}
                    <div class="row ">
                        @include("partials.v1.divider_title",[
                                       "title"=>"Información de cliente"
                            ]
                            )
                        <div class="row pl-5 pr-3">

                            @include("partials.v1.form.form_input_icon",[
                                  "input_model"=>"name",
                                  "updated_input"=> "defer",
                                  "input_label"=>"Nombre del cliente",
                                  "icon_class"=>"fas fa-user",
                                  "placeholder"=>"Nombre",
                                  "col_with"=>8,
                                  "input_type"=>"text",
                                  "required"=>true
                         ])

                            @include("partials.v1.form.form_input_icon",[
                                "input_model"=>"last_name",
                                "updated_input"=> "defer",
                                "input_label"=>"Apellido del cliente",
                                "icon_class"=>"fas fa-user",
                                "placeholder"=>"Apellido del cliente",
                                "col_with"=>8,
                                "input_type"=>"text",
                                "required"=>true
                       ])
                            @include("partials.v1.form.form_input_icon",[
                                    "input_model"=>"alias",
                                    "input_label"=>"Alias del cliente",
                                    "tooltip_title"=>"Nombre personalizado para el cliente. Este alias se presentara dentro del monitoreo y las notificaciones asociadas al cliente (si no se asigna alias se presentara el nombre y apellido del cliente)",
                                    "updated_input"=>"defer",
                                    "icon_class"=>"fas fa-user",
                                    "placeholder"=>"Alias de cliente",
                                    "col_with"=>8,
                                    "input_type"=>"text",
                                    "required"=>true
                           ])
                            @include("partials.v1.divider_title",[
                                      "title"=>"Datos de contacto"
                              ]
                             )
                            @include("partials.v1.form.form_list",[
                               "col_with"=>2,
                               "input_label"=>"Indicativo",
                               "input_type"=>"text",
                               "list_model" => "indicative",
                               "list_default" => "Indicativo ...",
                               "list_options" => $indicatives,
                               "list_option_value"=>"value",
                               "list_option_view"=>"key",
                                "list_option_title"=>"",
                               ])
                            @include("partials.v1.form.form_input_icon",[
                                    "input_label"=>"Telefono del cliente (Sin indicativo)",
                                    "input_model"=>"phone",
                                   "updated_input"=>"defer",
                                    "icon_class"=>"fas fa-barcode",
                                    "placeholder"=>"Telefono",
                                    "col_with"=>6,
                                    "input_type"=>"text",
                           ])

                            @include("partials.v1.form.form_input_icon",[
                                    "input_label"=>"Correo electronico de cliente",
                                    "input_model"=>"email",
                                   "updated_input"=>"lazy",
                                    "icon_class"=>"fas fa-envelope",
                                    "placeholder"=>"E-mail",
                                    "col_with"=>8,
                                    "input_type"=>"email",
                           ])
                            @include("partials.v1.form.form_list",[
                               "col_with"=>2,
                               "input_label"=>"Zona horaria",
                               "input_type"=>"text",
                               "list_model" => "time_zone",
                               "list_default" => "Zona horaria ...",
                               "list_options" => $time_zones,
                               "list_option_value"=>"value",
                               "list_option_view"=>"key",
                                "list_option_title"=>"",
                               ])

                            @include("partials.v1.form.form_list",[
                              "col_with"=>8,
                              "input_label"=>"Seleccione el tipo de persona",
                              "input_type"=>"text",
                              "list_model" => "client_person_type",
                              "list_default" => "Tipo de persona ...",
                              "list_options" => $person_types,
                              "list_option_value"=>"value",
                              "list_option_view"=>"key",
                              "list_option_title"=>"",
                     ])
                            @include("partials.v1.form.form_list",[
                                  "col_with"=>8,
                                  "input_type"=>"text",
                                  "input_label"=>"Seleccione el tipo de indentificación de cliente",
                                  "list_model" => "client_identification_type",
                                  "list_default" => "Tipo de identificación cliente",
                                  "list_options" => $identification_types,
                                  "list_option_value"=>"value",
                                  "list_option_view"=>"key",
                                  "list_option_title"=>"",
                         ])
                            @include("partials.v1.form.form_input_icon",[
                                   "input_label"=>"Numero de identificación de cliente",
                                   "input_model"=>"client_identification",
                                  "updated_input"=>"lazy",
                                   "icon_class"=>"fas fa-barcode",
                                   "placeholder"=>"identificación",
                                   "col_with"=>8,
                                   "input_type"=>"text",
                                   "required"=>false
                          ])
                        </div>


                        @include("partials.v1.divider_title",[
                                "title"=>"Datos de facturacion"
                        ]
                       )
                        <div class="row pl-5 pr-3">
                            @include("partials.v1.form.form_list",[
                                  "col_with"=>8,
                                  "input_label"=>"Seleccione el tipo de persona",
                                  "input_type"=>"text",
                                  "list_model" => "person_type",
                                  "list_default" => "Tipo de persona ...",
                                  "list_options" => $person_types,
                                  "list_option_value"=>"value",
                                  "list_option_view"=>"key",
                                  "list_option_title"=>"",
                         ])


                            @include("partials.v1.form.form_list",[
                                    "col_with"=>8,
                                    "input_type"=>"text",
                                    "input_label"=>"Seleccione el tipo de indentificación",
                                    "list_model" => "identification_type",
                                    "list_default" => "Tipo de identificación",
                                    "list_options" => $identification_types,
                                    "list_option_value"=>"value",
                                    "list_option_view"=>"key",
                                    "list_option_title"=>"",
                           ])
                            @include("partials.v1.form.form_input_icon",[
                                    "input_label"=>"Numero de identificación para facturacion",
                                    "input_model"=>"identification",
                                   "updated_input"=>"lazy",
                                    "icon_class"=>"fas fa-barcode",
                                    "placeholder"=>"identificación",
                                    "col_with"=>8,
                                    "input_type"=>"text",
                                    "required"=>false
                           ])
                            @include("partials.v1.form.form_list",[
                               "col_with"=>8,
                               "input_label"=>"Seleccione el tipo de facturacion",
                               "input_type"=>"text",
                               "list_model" => "billing_type",
                               "list_default" => "Tipo de facturacion ...",
                               "list_options" => $billing_types,
                               "list_option_value"=>"value",
                               "list_option_view"=>"key",
                               "list_option_title"=>"",
                      ])

                            @include("partials.v1.form.form_input_icon",[
                                  "input_label"=>"Nombre para facturación",
                                  "input_model"=>"billing_name",
                                  "updated_input"=>"lazy",
                                  "icon_class"=>"fas fa-user",
                                  "placeholder"=>"Razon social para facturación",
                                  "col_with"=>8,
                                  "input_type"=>"text",
                                  "required"=>false
                            ])


                            @include("partials.v1.form.form_input_icon",[
                                "input_label"=>"Direccion de facturacion",
                                "input_model"=>"billing_address",
                                "updated_input"=>"lazy",
                                "icon_class"=>"fas fa-map",
                                "placeholder"=>"Direccion de facturacion",
                                "col_with"=>8,
                                "input_type"=>"text",
                                "required"=>false
                          ])

                        </div>

                        @include("partials.v1.divider_title",[
                                "title"=>"Ubicación del cliente"
                        ]
                       )
                        @include("partials.v1.addUserTemplate.user-add-location-form")

                        @include("partials.v1.divider_title",[
                            "title"=>"Tipo de red / Contribuciones"
                            ]
                           )
                        <div class="row pl-5 pr-3">
                            @include("partials.v1.form.form_list",[
                                    "col_with"=>8,
                                    "list_model" => "stratum_id",
                                    "input_label"=>"Estrato de cliente",
                                    "list_default" => "Estrato...",
                                    "list_options" => $strata,
                                    "list_option_value"=>"id",
                                    "list_option_view"=>"acronym",
                                    "list_option_title"=>"",
                           ])

                            @include("partials.v1.form.form_list",[
                                     "col_with"=>8,
                                     "input_label"=>"Tipo de conexion de cliente",
                                     "list_model" => "client_type_id",
                                     "list_default" => "Conexión...",
                                     "list_options" => $client_types,
                                     "list_option_value"=>"value",
                                     "list_option_view"=>"key",
                                     "list_option_title"=>"",
                            ])
                            @error('client_type') <span class="error">{{ $message }}</span> @enderror


                            @if($client_type != "")
                                @if((strpos($client_type->type, "Convencion") !== false))
                                    @include("partials.v1.form.form_list",[
                                             "col_with"=>8,
                                             "input_label"=>"Nivel de tension",
                                             "list_model" => "voltage_level_id",
                                             "list_default" => "Nivel tensión...",
                                             "list_options" => $voltage_levels,
                                             "list_option_value"=>"id",
                                             "list_option_view"=>"level",
                                             "list_option_title"=>"description",
                                    ])

                                    @include("partials.v1.form.radio_button",[
                                        "input_label"=>" ¿ Impuesto AP? Marque si el usuario paga impuesto alumbrado publico",
                                        "input_model"=>"public_lighting_tax"
                                    ])


                                    @if($stratum_id > 4)
                                        @include("partials.v1.form.radio_button",[
                                        "input_label"=>"   ¿Contribución?",
                                        "input_model"=>"contribution"
                                    ])
                                    @endif
                                    <br>
                                    @if($stratum_id < 4)
                                        @include("partials.v1.form.form_list",[
                                                 "col_with"=>8,
                                                 "input_label"=>"Seleccione el subsidio del cliente",
                                                 "list_model" => "subsistence_consumption_id",
                                                 "list_default" => "Subsidio...",
                                                 "list_options" => $subsistence_consumptions,
                                                 "list_option_value"=>"id",
                                                 "list_option_view"=>"value",
                                                 "list_option_title"=>"description",
                                        ])
                                    @endif
                                @endif
                            @endif


                            @include("partials.v1.form.form_list",[
                                  "col_with"=>8,
                                  "input_label"=>"Seleccione la tipologia de red",
                                  "input_type"=>"text",
                                  "list_model" => "network_topology",
                                  "list_default" => "Topologia de red ...",
                                  "list_options" => $network_topologies,
                                  "list_option_value"=>"value",
                                  "list_option_view"=>"key",
                                  "list_option_title"=>"",
                         ])
                            <div class="col-md-12 mt-3">
                                @include("partials.v1.form.radio_button",[
                                         "input_label"=>"Cliente con telemetria",
                                         "input_model"=>"has_telemetry"
                                     ])
                            </div>
                            <div class="col-md-12 mt-3">
                                @if($has_telemetry)
                                    @include("partials.v1.form.radio_button",[
                                       "input_label"=>"¿ Crear supervisor asociado a cliente ?",
                                       "input_model"=>"create_supervisor"
                                   ])
                            </div>
                            @endif
                        </div>

                        @include("partials.v1.divider_title",[
                          "title"=>"Operador de red / Tecnico"
                          ]
                         )
                        <div class="row pl-5 pr-3">
                            @if(\App\Models\V1\User::getUserModel()::class == \App\Models\V1\Admin::class)
                                @include("partials.v1.form.form_list",[
                                        "col_with"=>8,
                                        "input_type"=>"text",
                                        "input_label"=>"Operador de red",
                                        "list_model" => "network_operator_id",
                                        "list_default" => "Operador de red...",
                                        "list_options" => $network_operators,
                                        "list_option_value"=>"value",
                                        "list_option_view"=>"key",
                                        "list_option_title"=>"",
                                         "required"=>false
                               ])
                                @error('network_operator') <span class="error">{{ $message }}</span> @enderror

                            @endif
                            @include("partials.v1.form.form_list",[
                                      "col_with"=>8,
                                      "input_type"=>"text",
                                      "input_label"=>"Tecnico",
                                      "list_model" => "technician_id",
                                      "list_default" => "Tecnico...",
                                      "list_options" => $technicians,
                                      "list_option_value"=>"value",
                                      "list_option_view"=>"key",
                                      "list_option_title"=>"",
                                      "disabled"=>$technician_select_disabled
                             ])

                        </div>
                        <div>
                            @if($this->getVaupesStratification())
                                @include("partials.v1.divider_title",[
                             "title"=>"Tipificación cliente Vaupes"
                             ]
                            )

                                @include("partials.v1.form.form_list",[
                                     "col_with"=>8,
                                     "input_type"=>"text",
                                     "input_label"=>"Estratificacion de cliente",
                                     "list_model" => "stratification_name",
                                     "list_default" => "Tipo...",
                                     "list_options" => $stratification,
                                     "list_option_value"=>"value",
                                     "list_option_view"=>"key",
                                     "list_option_title"=>"",
                            ])

                            @endif
                        </div>

                        {{--@include("partials.v1.equipment_to_client_association")--}}

                        <div class="text-right">
                            <button id="add" type="submit" class="mb-2 py-2 px-4">
                                <b>
                                    Guardar cliente
                                </b>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>
    <script>
        document.addEventListener('livewire:load', function () {
            $('input[type="file"]').change(function (e) {
                var fileName = e.target.files[0].name;
                $('.custom-file-label').html(fileName);
                $("#importar").prop('disabled', false);
            });
            $("input").keydown(function (e) {
                var keyCode = e.which;
                if (keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });
    </script>
</div>






