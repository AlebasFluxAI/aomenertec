<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"Firmware"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>2,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.superadmin.firmware.listado",
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
                                        "input_model"=>"model.name",
                                        "icon_class"=>"fas fa-user",
                                        "placeholder"=>"Nombre ",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_type"=>"text",
                                        "input_model"=>"model.version",
                                        "icon_class"=>"fas fa-user",
                                        "placeholder"=>"Versión",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_type"=>"text",
                                        "input_model"=>"model.description",
                                        "icon_class"=>"fas fa-envelope",
                                        "placeholder"=>"Descripción",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_type"=>"file",
                                        "input_model"=>"file",
                                        "icon_class"=>"fas fa-file",
                                        "placeholder"=>"Archivo .bin",
                                        "col_with"=>12,
                                        "required"=>true,
                                        "multiple"=>true,
                                        "file_accepted"=>".bin",
                            ],

                         ]
                 ])


</div>
