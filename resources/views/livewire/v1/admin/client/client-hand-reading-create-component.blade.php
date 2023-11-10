@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Registrar lectura manual",
            "second_title"=>""
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>4,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_binding"=>"client",
                    "target_binding_value"=>$model->client_id,
                    "target_route"=>"v1.admin.client.hand_reading",
                    ]

                ]
        ])


    <div class="contenedor-grande">
        <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
            <div class="row ">
                @include("partials.v1.divider_title",[
                                "title"=>"Datos del cliente  "
                        ]
                       )

                @include("partials.v1.form.form_input_icon",[
                      "input_model"=>"model.client.code",
                      "input_label"=>"Codigo de cliente",
                      "icon_class"=>"fas fa-code",
                      "placeholder"=>"Codigo de cliente",
                      "col_with"=>3,
                      "input_type"=>"text",
                      "required"=>true,
                      "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                      "updated_input"=>"lazy",
                      "input_model"=>"model.client.name",
                      "input_label"=>$model->client->name,
                      "icon_class"=>"fas fa-user",
                      "placeholder"=>"Nombre de cliente",
                      "col_with"=>6,
                      "input_type"=>"text",
                      "required"=>true,
                      "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                     "input_model"=>"model.client.identification",
                     "input_label"=>"Identificación de cliente",
                     "icon_class"=>"fas fa-card",
                     "placeholder"=>"Identificación de cliente",
                     "col_with"=>3,
                     "input_type"=>"text",
                     "required"=>true,
                      "disabled"=>true
               ])

                @include("partials.v1.divider_title",[
                               "title"=>"Información de orden de servicio"
                       ]
                      )

                @include("partials.v1.form.form_input_icon",[
                      "input_model"=>"model.id",
                      "input_label"=>"Numero de orden",
                      "icon_class"=>"fas fa-number",
                      "placeholder"=>"Numero de orden",
                      "col_with"=>2,
                      "input_type"=>"text",
                      "required"=>true,
                      "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                      "input_model"=>"model.created_at",
                      "input_label"=>"Fecha de creación",
                      "icon_class"=>"fas fa-date",
                      "placeholder"=>"Fecha de creación",
                      "col_with"=>3,
                      "input_type"=>"text",
                      "required"=>true,
                      "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                     "input_model"=>"model.technician.name",
                     "input_label"=>"Tecnico encargado",
                     "icon_class"=>"fas fa-support",
                     "placeholder"=>"Tecnico encargado",
                     "col_with"=>7,
                     "input_type"=>"text",
                     "required"=>true,
                      "disabled"=>true
               ])

                @include("partials.v1.divider_title",[
                                                         "title"=>"Datos de lectura"
                                                 ]
                                                )
                @include("partials.v1.form.form_input_icon",[
                         "input_model"=>"model.microcontrollerData.accumulated_real_consumption",
                         "input_label"=>"Consumo activo (Kwh)",
                         "icon_class"=>"fas fa-screwdriver-wrench",
                         "placeholder"=>"Ingrese acumulado de consumo activo",
                         "col_with"=>4,
                         "input_type"=>"number",
                         "number_min" => 0,
                         "number_step" => 0.0001,
                         "required"=>true
                ])

                @include("partials.v1.form.form_input_icon",[
                        "input_model"=>"model.microcontrollerData.accumulated_reactive_consumption",
                        "input_label"=>"Consumo reactivo (Kvarh)",
                        "icon_class"=>"fas fa-screwdriver-wrench",
                        "placeholder"=>"Ingrese acumulado de consumo reactivo",
                        "col_with"=>4,
                        "input_type"=>"number",
                        "number_min" => 0,
                        "number_step" => 0.0001,
                        "required"=>true
               ])

                @include("partials.v1.form.form_input_icon",[
                           "mt"=>0,
                           "input_model"=>"model.microcontrollerData.source_timestamp",
                           "icon_class"=>"fas fa-calendar",
                          "updated_input"=>"defer",
                           "placeholder"=>"Seleccione Hora y fecha de lectura",
                           "col_with"=>4,
                           "input_type"=>"text",
                           "input_name"=>"datetime_report",
                           "autocomplete"=> "off",
                           "input_id"=>"datetime",
                           "input_label"=>"Fecha y hora de lectura"
                  ])
                @include("partials.v1.form.form_input_file",[
                                 "multiple"=>true,
                                 "input_type"=>"file",
                                 "input_model"=>"evidences",
                                 "icon_class"=>"fas fa-file",
                                 "placeholder"=>"Puedes seleccionar varias imagenes",
                                 "col_with"=>12,
                                 "required"=>false,
                                                ])
                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>"Registrar lectura"
                          ])
            </div>
        </form>
        <script>

            document.addEventListener('livewire:load', function () {
                flatpickr("#datetime", {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    // Puedes personalizar más opciones aquí
                });
            });
        </script>
    </div>
</div>

