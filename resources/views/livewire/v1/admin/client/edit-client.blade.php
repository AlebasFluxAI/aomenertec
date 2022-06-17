@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"Cliente"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>2,"nav_options"=>[
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


            <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
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
                              "input_label"=>"Nombre para facturación",
                              "input_model"=>"billing_name",
                              "icon_class"=>"fas fa-user",
                              "placeholder"=>"Razon social para facturación",
                              "col_with"=>8,
                              "input_type"=>"text",
                              "required"=>true
                        ])
                        @include("partials.v1.form.form_input_icon",[
                            "input_label"=>"Direccion de facturacion",
                            "input_model"=>"billing_address",
                            "icon_class"=>"fas fa-map",
                            "placeholder"=>"Direccion de facturacion",
                            "col_with"=>8,
                            "input_type"=>"text",
                            "required"=>true
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
                                "input_label"=>"Estrado de cliente",
                                "list_default" => "Estrato...",
                                "list_options" => $strata,
                                "list_option_value"=>"id",
                                "list_option_view"=>"acronym",
                                "list_option_title"=>"",
                       ])
                    </div>

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
</div>
