<div>
    <section class="login">
        @section("header") {{--extended app.blade--}}
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
                                  "input_label"=>"Nombre del cliente",
                                  "icon_class"=>"fas fa-user",
                                  "placeholder"=>"Nombre",
                                  "col_with"=>8,
                                  "input_type"=>"text",
                                  "required"=>true
                         ])

                            @include("partials.v1.form.form_input_icon",[
                                   "input_label"=>"Apellido del cliente",
                                   "input_model"=>"last_name",
                                   "icon_class"=>"fas fa-user",
                                   "placeholder"=>"Apellido",
                                   "col_with"=>8,
                                   "input_type"=>"text",
                                   "required"=>true
                                                ])

                            @include("partials.v1.form.form_input_icon",[
                                    "input_label"=>"Telefono del cliente (Sin indicativo)",
                                    "input_model"=>"phone",
                                    "icon_class"=>"fas fa-barcode",
                                    "placeholder"=>"Telefono",
                                    "col_with"=>8,
                                    "input_type"=>"text",
                           ])


                            @include("partials.v1.form.form_input_icon",[
                                    "input_label"=>"Correo electronico de cliente",
                                    "input_model"=>"email",
                                    "icon_class"=>"fas fa-envelope",
                                    "placeholder"=>"E-mail",
                                    "col_with"=>8,
                                    "input_type"=>"email",
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
                                    "input_label"=>"Numero de identificación de cliente",
                                    "input_model"=>"identification",
                                    "icon_class"=>"fas fa-barcode",
                                    "placeholder"=>"identificación",
                                    "col_with"=>8,
                                    "input_type"=>"text",
                                    "required"=>true
                           ])
                            @include("partials.v1.form.form_input_icon",[
                                  "input_label"=>"Nombre para generar factura",
                                  "input_model"=>"identification",
                                  "icon_class"=>"fas fa-barcode",
                                  "placeholder"=>"identificación",
                                  "col_with"=>8,
                                  "input_type"=>"text",
                                  "required"=>true
                            ])
                            @include("partials.v1.form.form_input_icon",[
                                "input_label"=>"Direccion de facturacion",
                                "input_model"=>"identification",
                                "icon_class"=>"fas fa-barcode",
                                "placeholder"=>"identificación",
                                "col_with"=>8,
                                "input_type"=>"text",
                                "required"=>true
                          ])

                        </div>

                        @include("partials.v1.divider_title",[
                                "title"=>"Ubicación del cliente"
                        ]
                       )
                        <div class="row pl-5 pr-3">
                            @include("partials.v1.form.form_list",[
                                     "col_with"=>8,
                                     "input_type"=>"text",
                                     "input_label"=>"Tipo de ubicacion",
                                     "list_model" => "location_type_id",
                                     "list_default" => "Tipo ubicación...",
                                     "list_options" => $location_types,
                                     "list_option_value"=>"id",
                                     "list_option_view"=>"type",
                                     "list_option_title"=>"",
                            ])

                            @include("partials.v1.map",[
                                     "input_label"=>"Seleccione la ubicacion del cliente en el mapa o ingrese una direccion"
                               ])

                        </div>
                        @include("partials.v1.divider_title",[
                            "title"=>"Tipo de red / Contribuciones"
                            ]
                           )
                        <div class="row pl-5 pr-3">
                            @include("partials.v1.form.form_list",[
                                    "col_with"=>8,
                                    "list_model" => "stratum_id",
                                    "input_label"=>"Estrado de cliente",
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
                                     "list_option_value"=>"id",
                                     "list_option_view"=>"type",
                                     "list_option_title"=>"description",
                            ])


                            @if($client_type != "")
                                @if(strpos($client_type->type, "CONVENCIONAL") !== false)
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
                                  "input_label"=>"Seleccione la topologia de red",
                                  "input_type"=>"text",
                                  "list_model" => "network_topology",
                                  "list_default" => "Topologia de red ...",
                                  "list_options" => $network_topologies,
                                  "list_option_value"=>"value",
                                  "list_option_view"=>"key",
                                  "list_option_title"=>"",
                         ])
                        </div>

                        @include("partials.v1.divider_title",[
                          "title"=>"Operador de red / Tecnico"
                          ]
                         )
                        <div class="row pl-5 pr-3">
                            @include("partials.v1.form.form_dropdown_input_searchable",[
                                      "col_with" => 8,
                                      "icon_class" => "fas fa-user",
                                      "dropdown_model" => "network_operator",
                                      "placeholder" => "Operador de red",
                                      "required" => true,
                                      "picked_variable" => $picked_network_operator,
                                      "message_variable" => $message_network_operator,
                                      "dropdown_results" => $network_operators,
                                      "selected_value_function" => "assignNetworkOperator",
                                      "dropdown_result_id" => "id",
                                      "dropdown_result_value" => "name",
                                      "count_bool" => (count($network_operators)>0),

                            ])

                            @include("partials.v1.form.form_dropdown_input_searchable",[
                                   "col_with" => 8,
                                   "icon_class" => "fas fa-user",
                                   "dropdown_model" => "technician",
                                   "placeholder" => "Tecnico",
                                   "required" => true,
                                   "picked_variable" => $picked_technician,
                                   "message_variable" => $message_technician,
                                   "dropdown_results" => $technicians,
                                   "selected_value_function" => "assignTechnician",
                                   "dropdown_result_id" => "id",
                                   "dropdown_result_value" => "name",
                                   "count_bool" => (count($technicians)>0),

                         ])
                        </div>
                        @include("partials.v1.divider_title",[
                        "title"=>"Equipos de clientes"
                        ]
                       )
                        <div class="row pl-5 pr-3">
                            @if($client_type_id != "")

                                <div class="col-12 text-left"> &nbsp;&nbsp; <strong> Seriales de componentes</strong>
                                </div>
                                @foreach($equipment as $index => $item)
                                    <div wire:key="equipment-field-{{ $index }}"
                                         class="form-group mb-2 align-content-start col-md-3 col-sm-12">
                                        @include("partials.v1.form.form_list",[
                                     "col_with"=>8,
                                     "mb"=>0,
                                     "disabled" => $item['disable'],
                                     "aux_class"=>"no-border",
                                     "list_model" => "equipment.".$index.".type_id",
                                     "list_default" => "Seleccione equipo...",
                                     "list_options" => $equipment_types,
                                     "list_option_value"=>"id",
                                     "list_option_view"=>"type",
                                     "list_option_title"=>""
                            ])
                                        @include("partials.v1.form.form_dropdown_input_searchable",[
                                      "form_group" => false,
                                              "col_with"=>8,
                                      "dropdown_model" => "equipment.".$index.".serial",
                                      "placeholder" => $item['type'],
                                      "required" => true,
                                      "picked_variable" => $item['picked'],
                                      "message_variable" => $item['post'],
                                      "variable_2" => $index??0,
                                      "dropdown_results" => $serials,
                                      "count_bool" => $serials->contains('equipment_type_id', $item['type_id']),
                                      "selected_value_function" => "assignEquipment",
                                      "dropdown_result_id" => "id",
                                      "dropdown_result_value" => "serial",
                            ])
                                    </div>
                                @endforeach

                                <div class="d-flex align-items-center col-md-2 col-sm-12">
                                    @include("partials.v1.primary_button",[
                                                                 "button_align"=>"right" ,
                                                                 "click_action" => "addInputEquipment()",
                                                                 "button_content"=>"",
                                                                 "button_icon" => "fas fa-plus",
                                                                 "class_button"=>"b-success"

                                                     ])
                                    @include("partials.v1.primary_button",[
                                                                 "button_align"=>"right" ,
                                                                 "click_action" => "deleteInputEquipment()",
                                                                 "button_content"=>"",
                                                                 "button_icon" => "fas fa-minus",
                                                                 "class_button"=>"b-danger"
                                                     ])
                                </div>
                            @endif
                            @if (session()->has('no_delete'))
                                <div class="alert alert-danger">
                                    {{ session('no_delete') }}
                                </div>
                            @endif


                            <div class="text-right">
                                <button id="add" type="submit" class="mb-2 py-2 px-4"
                                        @if(!$picked_network_operator) disabled="true" @endif>
                                    <b>
                                        Guardar cliente
                                    </b>
                                </button>
                            </div>
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



