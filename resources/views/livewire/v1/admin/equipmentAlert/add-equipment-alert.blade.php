<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"Alertas para equipos"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.equipos.alertas.listado",
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
                                        "input_type"=>"dropdown-search",
                                        "icon_class"=>"fas fa-desktop",
                                        "placeholder"=>"Seleccione el equipo",
                                        "col_with"=>6,
                                        "dropdown_model"=>"equipmentId",
                                        "dropdown_enter_function"=>"updatedEquipmentId",
                                        "picked_variable"=>$picked,
                                        "dropdown_results"=>$equipments,
                                        "selected_value_function"=>"setEquipment",
                                        "dropdown_result_id"=>"id",
                                        "dropdown_result_value"=>"name",

                            ],
                             [
                                        "input_type"=>"number",
                                        "input_model"=>"value",
                                        "icon_class"=>"fa-solid fa-list-ol",
                                        "placeholder"=>"Valor a alarmar",
                                        "input_field"=>"Valor a alarmar",
                                        "col_with"=>6,
                                        "required"=>true
                            ],
                                    [
                                        "input_type"=>"dropdown",
                                        "input_model"=>"value",
                                        "placeholder"=>"Tipo de alarma",
                                        "icon_class"=>"fa-solid fa-bell",
                                        "col_with"=>6,
                                        "dropdown_model"=>"alertType",
                                        "dropdown_values"=>$alertTypes
                            ],

                         ]
                 ])

</div>
