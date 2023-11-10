<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"tipos de alertas"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.equipos.alertas.tipos.listado",
                    ],

                ]
        ])

    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.form.primary_form",[
            "form_toast"=>false,
            "session_message"=>"message",
            "form_submit_action"=>"submitForm",
            "form_inputs"=>[

                             [
                                        "input_type"=>"text",
                                        "input_model"=>"name",
                                        "icon_class"=>"fa-solid fa-file-signature",
                                        "placeholder"=>"Nombre de alerta",
                                        "input_field"=>"Nombre de alerta",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_type"=>"number",
                                        "input_model"=>"value",
                                        "icon_class"=>"fa-solid fa-list-ol",
                                        "placeholder"=>"Valor interno",
                                        "input_field"=>"Valor interno",
                                        "col_with"=>12,
                                        "required"=>true
                            ],

                              [
                                        "input_type"=>"text",
                                        "input_model"=>"unit",
                                        "icon_class"=>"fa-solid fa-tag",
                                        "placeholder"=>"Unidad de valor",
                                        "input_field"=>"Unidad de valor",
                                        "col_with"=>12,
                                        "required"=>true
                            ],


                         ]
                 ])

</div>
