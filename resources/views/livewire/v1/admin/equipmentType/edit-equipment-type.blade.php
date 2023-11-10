<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"tipo de equipo"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>2,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.equipos.tipos.listado",
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
                                        "input_model"=>"type",
                                        "icon_class"=>"fa-solid fa-file-signature",
                                        "placeholder"=>"Tipo",
                                        "input_field"=>"Tipo",
                                        "col_with"=>12,
                                        "required"=>true
                            ],

         [
                                        "input_type"=>"text",
                                        "input_model"=>"description",
                                        "icon_class"=>"fa-solid fa-file-signature",
                                        "placeholder"=>"Descripcion",
                                        "input_field"=>"Descripcion",
                                        "col_with"=>12,
                                        "required"=>true
                            ],


                         ]
                 ])
</div>
