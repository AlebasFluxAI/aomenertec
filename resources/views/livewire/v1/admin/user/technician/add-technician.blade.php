<div>
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"tecnico"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.tecnicos.listado",
                    ],
                ]
        ])
    {{----------------------------------Formulario--------------------------}}
    @role("network_operator")
    @include("partials.v1.form.primary_form",[
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

                                       [
                                   "input_type"=>"email",
                                   "input_model"=>"email",
                                   "icon_class"=>"fas fa-envelope",
                                   "placeholder"=>"Correo electronico ",
                                   "col_with"=>6,
                                   "required"=>true
                       ],
                                    [
                                   "input_type"=>"password",
                                   "input_model"=>"password",
                                   "icon_class"=>"fas fa-file",
                                    "placeholder"=>"Contrasena",
                                   "col_with"=>6,
                                   "required"=>false,

                        ]
                    ]
            ])
    @else
        @include("partials.v1.form.primary_form",[
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

                                                [
                                            "input_type"=>"email",
                                            "input_model"=>"email",
                                            "icon_class"=>"fas fa-envelope",
                                            "placeholder"=>"Correo electronico ",
                                            "col_with"=>6,
                                            "required"=>true
                                ],
                                             [
                                            "input_type"=>"password",
                                            "input_model"=>"password",
                                            "icon_class"=>"fas fa-file",
                                             "placeholder"=>"Contrasena",
                                            "col_with"=>6,
                                            "required"=>false,

                                 ],
                                               [
                                            "input_type"=>"dropdown-search",
                                            "icon_class"=>"fas fa-desktop",
                                            "placeholder"=>"Seleccione el operador de red",
                                            "col_with"=>6,
                                            "dropdown_model"=>"network_operator",
                                            "picked_variable"=>$picked,
                                            "dropdown_results"=>$network_operators,
                                            "dropdown_enter_function"=>"assignNetworkOperator",
                                            "selected_value_function" => "assignNetworkOperator",
                                            "dropdown_result_id"=>"id",
                                            "dropdown_result_value"=>"name",
                                            "count_bool" => (count($network_operators)>0),

                                ]

                             ]
                     ])
        @endrole

</div>
