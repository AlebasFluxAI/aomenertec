<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"Administrador"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>2,"nav_options"=>[
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
                      "add_user_type_technician"=>true,
                      "add_user_type_network_operator"=>true,
                      "custom_input"=>[

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
                                                 "input_model"=>"model.css_file",
                                                 "icon_class"=>"fas fa-pencil",
                                                 "placeholder"=>"Archivo de estilos",
                                                 "col_with"=>12,
                                                 "dropdown_editing"=>false,
                                                 "dropdown_refresh"=>"setStyle",
                                                 "dropdown_model"=>"model.css_file",
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
