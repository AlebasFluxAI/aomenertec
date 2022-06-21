@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"Equipos"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.equipos.listado",
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
                                        "input_model"=>"equipmentName",
                                        "icon_class"=>"fas fa-user",
                                        "placeholder"=>"Nombre del equipo",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_type"=>"text",
                                        "input_model"=>"equipmentSerial",
                                        "icon_class"=>"fas fa-barcode",
                                        "placeholder"=>"Serial del equipo",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_type"=>"text",
                                        "input_model"=>"equipmentDescription",
                                        "icon_class"=>"fas fa-file",
                                         "placeholder"=>"Descripcion del equipo",
                                        "col_with"=>12,
                                        "input_rows"=>3,
                                        "required"=>false,

                             ],

                         ]
                 ])


</div>
