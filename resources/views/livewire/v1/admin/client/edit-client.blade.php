@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"Cliente"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"v1.admin.client.list.client",
                    ],

                ]
        ])
    @include("partials.v1.form.primary_form",[
                                                                        "form_toast"=>true,
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitForm",
                                                                        "form_inputs"=>[
                                                                                        [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"identification",
                                                                                                    "icon_class"=>"fas fa-barcode",
                                                                                                    "placeholder"=>"identificación",
                                                                                                    "col_with"=>4,
                                                                                                    "required"=>true
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"name",
                                                                                                    "icon_class"=>"fas fa-barcode",
                                                                                                    "placeholder"=>"Serial del equipo",
                                                                                                    "col_with"=>5,
                                                                                                    "required"=>true
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"phone",
                                                                                                    "icon_class"=>"fas fa-barcode",
                                                                                                    "placeholder"=>"Telefono",
                                                                                                    "col_with"=>3,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"list",
                                                                                                    "col_with"=>4,
                                                                                                     "list_model" => "location_type_id",
                                                                                                     "list_default" => "Tipo ubicación...",
                                                                                                     "list_options" => $location_types,
                                                                                                     "list_option_value"=>"id",
                                                                                                     "list_option_view"=>"location",
                                                                                                     "list_option_title"=>"",

                                                                                        ],
                                                                                       [
                                                                                                     "input_type"=>"list",
                                                                                                     "col_with"=>4,
                                                                                                     "list_model" => "department_id",
                                                                                                     "list_default" => "Departamento...",
                                                                                                     "list_options" => $departments,
                                                                                                     "list_option_value"=>"id",
                                                                                                     "list_option_view"=>"name",
                                                                                                     "list_option_title"=>"",
                                                                                        ],
                                                                                        [
                                                                                                     "input_type"=>"list",
                                                                                                     "col_with"=>4,
                                                                                                     "list_model" => "municipality_id",
                                                                                                     "list_default" => "Municipio...",
                                                                                                     "list_options" => $municipalities,
                                                                                                     "list_option_value"=>"id",
                                                                                                     "list_option_view"=>"name",
                                                                                                     "list_option_title"=>"",
                                                                                        ],
                                                                                        [
                                                                                                     "input_type"=>"list",
                                                                                                     "col_with"=>4,
                                                                                                     "list_model" => "location_id",
                                                                                                     "list_default" => "Ubicacion...",
                                                                                                     "list_options" => $locations,
                                                                                                     "list_option_value"=>"id",
                                                                                                     "list_option_view"=>"name",
                                                                                                     "list_option_title"=>"",
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"direction",
                                                                                                    "icon_class"=>"",
                                                                                                    "placeholder"=>"Direccion",
                                                                                                    "col_with"=>4,
                                                                                                    "required" =>false,
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"email",
                                                                                                    "input_model"=>"email",
                                                                                                    "icon_class"=>"",
                                                                                                    "placeholder"=>"E-mail",
                                                                                                    "col_with"=>4,
                                                                                                    "required" =>false,

                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"latitude",
                                                                                                    "icon_class"=>"",
                                                                                                    "placeholder"=>"Latitud",
                                                                                                    "col_with"=>2,
                                                                                                    "required" =>false,
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"longitude",
                                                                                                    "icon_class"=>"",
                                                                                                    "placeholder"=>"Longitud",
                                                                                                    "col_with"=>2,
                                                                                                    "required" =>false,
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"list",
                                                                                                    "col_with"=>2,
                                                                                                     "list_model" => "stratum_id",
                                                                                                     "list_default" => "Estrato...",
                                                                                                     "list_options" => $strata,
                                                                                                     "list_option_value"=>"id",
                                                                                                     "list_option_view"=>"acronym",
                                                                                                     "list_option_title"=>"",
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"list",
                                                                                                    "col_with"=>4,
                                                                                                     "list_model" => "voltage_level_id",
                                                                                                     "list_default" => "Nivel tensión...",
                                                                                                     "list_options" => $voltage_levels,
                                                                                                     "list_option_value"=>"id",
                                                                                                     "list_option_view"=>"level",
                                                                                                     "list_option_title"=>"description",
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"list",
                                                                                                    "col_with"=>2,
                                                                                                     "list_model" => "subsistence_consumption_id",
                                                                                                     "list_default" => "Subsidio...",
                                                                                                     "list_options" => $subsistence_consumptions,
                                                                                                     "list_option_value"=>"id",
                                                                                                     "list_option_view"=>"value",
                                                                                                     "list_option_title"=>"description",
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"list",
                                                                                                    "col_with"=>2,
                                                                                                     "list_model" => "network_topology",
                                                                                                     "list_default" => "Topologia red...",
                                                                                                     "list_options" => $topologies,
                                                                                                     "list_option_value"=>"id",
                                                                                                     "list_option_view"=>"value",
                                                                                                     "list_option_title"=>"",
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"dropdown-search",
                                                                                                    "col_with" => 4,
                                                                                                  "icon_class" => "fas fa-user",
                                                                                                  "dropdown_model" => "network_operator",
                                                                                                  "placeholder" => "Operador de red",
                                                                                                  "required" => true,
                                                                                                  "picked_variable" => $picked_network_operator,
                                                                                                  "message_variable" => $message_network_operator,
                                                                                                  "dropdown_results" => $network_operators,
                                                                                                  "selected_value_function" => "assignNetworkOperator",
                                                                                                  "dropdown_enter_function" => "",
                                                                                                  "dropdown_result_id" => "id",
                                                                                                  "dropdown_result_value" => "name",
                                                                                                  "count_bool" => (count($network_operators)>0),
                                                                                        ],

                                                                                     ]
         ])


</div>
