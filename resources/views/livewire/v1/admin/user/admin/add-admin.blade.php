<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"Administrador"
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
    {{----------------------------------Formulario--------------------------}}
    <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
        @include("partials.v1.addUserTemplate.user-add-form",
                    [
                      "custom_input"=>[
                            [
                           "view_name"=>"partials.v1.divider_title",
                           "view_values" =>[
                                          "title"=>"NIT"
                                          ]

                           ],
                             [
                           "view_name"=>"partials.v1.form.form_input_icon",
                           "view_values" =>[
                                                   "input_type"=>"text",
                                                    "input_label"=>"Ingrese el nit del administrador",
                                                    "input_model"=>"nit",
                                                    "icon_class"=>"fas fa-barcode",
                                                    "placeholder"=>"NIT ",
                                                    "col_with"=>12,
                                                    "required"=>true
                                          ]
                           ],
                           [
                           "view_name"=>"partials.v1.divider_title",
                           "view_values" =>[
                                          "title"=>"Personalización"
                                          ]

                           ],
                            [
                           "view_name"=>"partials.v1.form.form_dropdown",
                           "view_values" =>[
                                                 "input_type"=>"dropdown",
                                                 "input_model"=>"style",
                                                 "icon_class"=>"fas fa-pencil",
                                                 "placeholder"=>"Archivo de estilos",
                                                 "col_with"=>12,
                                                 "dropdown_editing"=>false,
                                                 "dropdown_refresh"=>"setStyle",
                                                 "dropdown_model"=>"style",
                                                 "dropdown_values"=>$styles,
                                                 "required"=>false,
                                          ]
                           ],
                           [
                               "view_name"=>"partials.v1.form.form_input_file",
                                "view_values" =>[
                                                "input_type"=>"file",
                                                "input_model"=>"icon",
                                                "icon_class"=>"fas fa-file",
                                                "placeholder"=>"Logo del administrador",
                                                "col_with"=>12,
                                                "required"=>false,
                                          ]
                           ]
                        ]

          ])
    </form>

</div>
