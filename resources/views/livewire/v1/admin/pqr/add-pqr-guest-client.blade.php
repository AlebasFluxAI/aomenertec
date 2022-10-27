<div>
    <div style="background-color: #f2f2f2;padding: 15px;border-radius: 15px;margin-bottom: 5%">
        @include("layouts.menu.v1.header_menu_password")
        <br>
        @include("partials.v1.primary_navigator",
             [
                 "mt"=>2,
                 "button_align"=>"right",
                 "click_action"=>"",
                 "button_content"=>"Gestionar PQR's",
                 "icon"=>"fa-solid fa-folder",
                 "target_route"=>"guest.admin-pqr",
                 "button_align"=>"right",
                 "target_binding"=>"subdomain",
                 "target_binding_value"=>\Illuminate\Support\Facades\Route::input("subdomain")??\App\Http\Resources\V1\Subdomain::SUBDOMAIN_DEFAULT

            ])
        <div class="section-title my-5">
            <h3 class="text-center p3"><b><span class="fas fa-ticket"></span> Registro de peticiones para clientes</b>
            </h3>
        </div>

        <div class="contenedor-grande">
            <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
                @include("partials.v1.divider_title",[
                                           "title"=>"¿ Sabes tu codigo de cliente ?"
                                   ]
                                  )
                @include("partials.v1.form.radio_button",[
                                      "input_label"=>"Usar codigo de cliente",
                                      "input_model"=>"has_client_code"
                                  ])
                @include("partials.v1.divider_title",[
                                             "title"=>"Información de contacto"
                                     ]
                                    )

                @if(!$has_client_code)
                    @include("partials.v1.form.form_input_icon",[
                          "input_model"=>"contact_name",
                          "input_label"=>"Nombre",
                          "icon_class"=>"fas fa-user",
                          "placeholder"=>"Nombre",
                          "col_with"=>6,
                          "input_type"=>"text",
                          "required"=>true
                 ])

                    @include("partials.v1.form.form_input_icon",[
                            "input_label"=>"Telefono (Sin indicativo)",
                            "input_model"=>"contact_phone",
                            "icon_class"=>"fas fa-barcode",
                            "placeholder"=>"Telefono",
                            "col_with"=>6,
                            "input_type"=>"text",
                   ])


                    @include("partials.v1.form.form_input_icon",[
                            "input_label"=>"Correo electronico",
                            "input_model"=>"contact_email",
                            "icon_class"=>"fas fa-envelope",
                            "placeholder"=>"E-mail",
                            "col_with"=>6,
                            "input_type"=>"email",
                   ])

                    @include("partials.v1.form.form_input_icon",[
                                        "input_label"=>"Numero de identificación",
                                        "input_model"=>"contact_identification",
                                        "icon_class"=>"fas fa-barcode",
                                        "placeholder"=>"identificación",
                                        "col_with"=>6,
                                        "input_type"=>"text",
                                        "required"=>false
                               ])
                @else

                    @include("partials.v1.form.form_input_icon",[
                            "input_label"=>"Codigo de cliente",
                            "input_model"=>"client_code",
                            "icon_class"=>"fas fa-user",
                            "placeholder"=>"Ingresa tu codigo de cliente",
                            "col_with"=>6,
                            "input_type"=>"text",
                   ])
                @endif
                @include("partials.v1.divider_title",[
                                             "title"=>"Seleccione el tipo de inconveniente"
                                     ]
                                    )



                @include("partials.v1.form.form_dropdown",[
                                          "input_label"=>"Seleccione el tipo de incidencia",
                                                "input_type"=>"dropdown",
                                                "icon_class"=>"fas fa-table-list",
                                                "placeholder"=>"Seleccione el tipo de incidencia",
                                                "col_with"=>6,
                                                "dropdown_model"=>"pqr_type",
                                                "dropdown_values"=>$pqr_types,
                                                "dropdown_editing"=>false,
                                  ])
                @include("partials.v1.form.form_dropdown",[
                                     "input_label"=>"Seleccione una categoria",
                                           "input_type"=>"dropdown",
                                           "icon_class"=>"fas fa-rectangle-list",
                                           "placeholder"=>"Seleccione una categoria",
                                           "col_with"=>6,
                                           "dropdown_model"=>"pqr_category",
                                           "dropdown_values"=>$pqr_categories,
                                           "dropdown_editing"=>false,
                             ])


                @include("partials.v1.divider_title",[
                                          "title"=>"Describe tu inconveniente"
                                  ]
                                 )

                @include("partials.v1.form.form_input_icon",[
                                        "input_label"=>"Asunto del incidente ",
                                        "input_model"=>"subject",
                                        "icon_class"=>"fas fa-envelope-open",
                                        "placeholder"=>"Error al ingresar al portal, sobrecosto, averias en equipo..etc",
                                        "col_with"=>6,
                                        "input_type"=>"text",
                                        "required"=>true
                                                     ])

                @include("partials.v1.form.form_input_icon",[
                                       "input_label"=>"Detalles del incidente",
                                       "input_model"=>"description",
                                       "input_rows"=>12,
                                       "placeholder"=>"Cuentanos mas sobre tu problema",
                                       "col_with"=>12,
                                       "input_type"=>"text",
                                       "required"=>true
                                                    ])

                @include("partials.v1.form.form_input_file",[
                                    "input_type"=>"file",
                                                        "input_model"=>"attach",
                                                        "icon_class"=>"fas fa-file",
                                                        "placeholder"=>"Puedes cargar una imagen",
                                                        "col_with"=>12,
                                                        "required"=>false,
                                                   ])

                @include("partials.v1.divider_title",[
                                         "title"=>"Prioridad"
                                 ]
                                )
                @include("partials.v1.form.form_dropdown",[
                                    "input_label"=>"Seleccione la gravedad del incidente",
                                          "input_type"=>"dropdown",
                                          "icon_class"=>"fas fa-bell",
                                          "placeholder"=>"Seleccione la gravedad del incidente",
                                          "col_with"=>6,
                                          "dropdown_model"=>"severity",
                                          "dropdown_values"=>$severities,
                                          "dropdown_editing"=>false,
                            ])

                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>"Enviar petición"
                          ])

            </form>

        </div>
    </div>
</div>
