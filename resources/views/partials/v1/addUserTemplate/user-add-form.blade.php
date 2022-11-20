<div class="contenedor-grande">
    <div class="row content p-5">
        <div class="row ">
            @if($form_title??null)
                @include("partials.v1.divider_title",[
                               "title"=>$form_title??""
                    ])
            @endif
            <div class="row pl-5 pr-3">
                @include("partials.v1.divider_title",[
                                       "title"=>"Información de usuario"
                            ])

                @include("partials.v1.form.form_input_icon",[
                      "input_model"=>"model.name",
                      "input_label"=>"Nombre",
                       "updated_input"=>"defer",
                      "icon_class"=>"fas fa-user",
                      "placeholder"=>"Nombre",
                      "col_with"=>8,
                      "input_type"=>"text",
                      "required"=>true,
             ])

                @include("partials.v1.form.form_input_icon",[
                       "input_label"=>"Apellido",
                       "input_model"=>"model.last_name",
                       "updated_input"=>"defer",
                       "icon_class"=>"fas fa-user",
                       "placeholder"=>"Apellido",
                       "col_with"=>8,
                       "input_type"=>"text",
                       "required"=>true,
                                    ])
                @include("partials.v1.divider_title",[
                                                    "title"=>"Datos de contacto"
                                            ]
                                           )
                @include("partials.v1.form.form_list",[
                         "col_with"=>2,
                         "input_label"=>"Indicativo",
                         "input_type"=>"text",
                         "list_model" => "model.indicative",
                         "list_default" => "Indicativo ...",
                         "list_options" => $indicatives,
                         "list_option_value"=>"value",
                         "list_option_view"=>"key",
                          "list_option_title"=>"",
                         ])
                @include("partials.v1.form.form_input_icon",[
                        "input_label"=>"Telefono del cliente (Sin indicativo)",
                        "input_model"=>"model.phone",
                       "updated_input"=>"defer",
                        "icon_class"=>"fas fa-barcode",
                        "placeholder"=>"Telefono",
                        "col_with"=>6,
                        "input_type"=>"text",
               ])


                @include("partials.v1.form.form_input_icon",[
                        "input_label"=>"Correo electronico de usuario",
                        "input_model"=>"model.email",
                        "updated_input"=>"lazy",
                        "icon_class"=>"fas fa-envelope",
                        "placeholder"=>"E-mail",
                        "col_with"=>8,
                        "input_type"=>"email",
               ])
                @include("partials.v1.divider_title",[
                                "title"=>"Datos de facturacion"
                        ]
                       )

                @include("partials.v1.form.form_list",[
                                  "col_with"=>8,
                                  "input_label"=>"Seleccione el tipo de persona",
                                  "input_type"=>"text",
                                  "list_model" => "model.person_type",
                                  "list_default" => "Tipo de persona ...",
                                  "list_options" => $person_types??[],
                                  "list_option_value"=>"value",
                                  "list_option_view"=>"key",
                                  "list_option_title"=>"",
                         ])

                @include("partials.v1.form.form_list",[
                                    "col_with"=>8,
                                    "input_type"=>"text",
                                    "input_label"=>"Seleccione el tipo de indentificación",
                                    "list_model" => "model.identification_type",
                                    "list_default" => "Tipo de identificación",
                                    "list_options" => $identification_types,
                                    "list_option_value"=>"value",
                                    "list_option_view"=>"key",
                                    "list_option_title"=>"",
                           ])

                @include("partials.v1.form.form_input_icon",[
                               "input_label"=>"Numero de identificación",
                               "input_model"=>"model.identification",
                               "updated_input"=>"lazy",
                               "icon_class"=>"fas fa-barcode",
                               "placeholder"=>"identificación",
                               "col_with"=>8,
                               "input_type"=>"text",
                               "required"=>true,
                      ])

                @include("partials.v1.form.form_input_icon",[
                                  "input_label"=>"Nombre para facturación",
                                  "input_model"=>"model.billing_name",
                                  "updated_input"=>"lazy",
                                  "icon_class"=>"fas fa-user",
                                  "placeholder"=>"Razon social para facturación",
                                  "col_with"=>8,
                                  "input_type"=>"text",
                                  "required"=>true
                            ])

                @include("partials.v1.form.form_input_icon",[
                                "input_label"=>"Direccion de facturacion",
                                "input_model"=>"model.billing_address",
                                "updated_input"=>"lazy",
                                "icon_class"=>"fas fa-map",
                                "placeholder"=>"Direccion de facturacion",
                                "col_with"=>8,
                                "input_type"=>"text",
                                "required"=>true,
                          ])

                @include("partials.v1.divider_title",[
                          "title"=>"Ubicacion"
               ]
               )

                @include("partials.v1.addUserTemplate.user-add-location-form")

                @foreach($custom_input??[] as $input)
                    @include($input["view_name"],$input["view_values"])
                @endforeach
                @error('address_error') <span class="error">{{ $message }}</span> @enderror

                @if($add_user_type_network_operator??false)
                    @include("partials.v1.divider_title",["title"=>"Agregar tipos de usuarios"])
                    @include("partials.v1.form.radio_button",[
                          "input_model"=>"user_type_network_operator",
                          "input_label"=>"Operador de red"
                      ])
                @endif
                @if($add_user_type_technician??false)
                    @include("partials.v1.form.radio_button",[
                          "input_model"=>"user_type_technician",
                         "input_label"=>"Tecnico"
                      ])
                @endif
                @include("partials.v1.divider_title")

                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>$form_submit_action_text??"Guardar"
                          ])
            </div>
        </div>
    </div>
</div>
