<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"Equipos"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>2,"nav_options"=>[
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
                                        "input_label"=>"Seleccione el tipo de equipo",
                                        "input_type"=>"dropdown",
                                        "icon_class"=>"fas fa-desktop",
                                        "placeholder"=>"Seleccione el tipo de equipo",
                                        "col_with"=>12,
                                        "dropdown_model"=>"equipmentTypeId",
                                        "dropdown_values"=>$equipmentTypes,
                                        "dropdown_result_id"=>"id",
                                        "dropdown_result_value"=>"type",
                                        "dropdown_editing"=>true,
                                        "dropdown_refresh"=>"refreshEquipmentTypes"

                            ],
                            [
                                        "input_label"=>"Nombre del equipo",
                                        "input_type"=>"text",
                                        "input_model"=>"name",
                                        "icon_class"=>"fas fa-barcode",
                                        "placeholder"=>"Nombre del equipo",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_label"=>"Serial del equipo",
                                        "input_type"=>"text",
                                        "input_model"=>"equipmentSerial",
                                        "icon_class"=>"fas fa-barcode",
                                        "placeholder"=>"Serial del equipo",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_label"=>"Descripcion",
                                        "input_type"=>"text",
                                        "input_model"=>"equipmentDescription",
                                        "icon_class"=>"fas fa-file",
                                         "placeholder"=>"Descripcion del equipo",
                                        "col_with"=>12,
                                        "input_rows"=>3,
                                        "required"=>false,

                             ],
                             [
                                       "input_label"=>"¿ Equipo con multiple conexión ?",
                                       "input_model"=>"has_multiple_connection",
                                       "placeholder"=>"Descripcion del equipo",
                                       "col_with"=>3,
                                       "input_type"=>"checkbox",
                                       "required"=>false,

                             ],

                         ]
                 ])


</div>
