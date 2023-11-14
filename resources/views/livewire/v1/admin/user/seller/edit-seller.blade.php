<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"vendedor"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.vendedores.listado",
                    ],

                ]
        ])
    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.tab.v1.tab",[

            "tab_titles"=>[
                                [
                                    "title"=>"Vendedor",

                                ],

                           ],

            "tab_contents"=>[
                                [
                                    "view_name"=>"partials.v1.form.primary_form",
                                    "view_values"=>  [
                                                            "class_container"=>"",
                                                            "form_toast"=>false,
                                                            "session_message"=>"message",
                                                            "form_submit_action"=>"submitForm",
                                                            "form_inputs"=>[
                                                                             [
                                                                                        "input_type"=>"text",
                                                                                        "input_model"=>"name",
                                                                                        "icon_class"=>"fas fa-user",
                                                                                        "placeholder"=>"Nombre ",
                                                                                        "col_with"=>6,
                                                                                        "required"=>true
                                                                            ],
                                                                            [
                                                                                        "input_type"=>"text",
                                                                                        "input_model"=>"last_name",
                                                                                        "icon_class"=>"fas fa-user",
                                                                                        "placeholder"=>"Apellido",
                                                                                        "col_with"=>6,
                                                                                        "required"=>true
                                                                            ],
                                                                            [
                                                                                        "input_type"=>"email",
                                                                                        "input_model"=>"email",
                                                                                        "icon_class"=>"fas fa-envelope",
                                                                                        "placeholder"=>"Correo electronico ",
                                                                                        "col_with"=>6,
                                                                                        "required"=>true
                                                                            ],
                                                                            [
                                                                                        "input_type"=>"text",
                                                                                        "input_model"=>"phone",
                                                                                        "icon_class"=>"fas fa-file",
                                                                                         "placeholder"=>"Telefono",
                                                                                        "col_with"=>6,

                                                                                        "required"=>false,

                                                                             ],
                                                                             [
                                                                                        "input_type"=>"text",
                                                                                        "input_model"=>"identification",
                                                                                        "icon_class"=>"fas fa-file",
                                                                                         "placeholder"=>"Identificacion",
                                                                                        "col_with"=>6,

                                                                                        "required"=>false,

                                                                             ],

                                                                         ]
                                                                    ],
                                                        ]

                 ]
         ])


</div>
