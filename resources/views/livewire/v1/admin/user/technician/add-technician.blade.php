<div class="login">
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
    <form wire:submit.prevent="save" id="formulario" class="needs-validation" role="form">
        @include("partials.v1.addUserTemplate.user-add-form")
    </form>
    @else
        @include("partials.v1.addUserTemplate.user-add-form",[
                          "custom_input"=>[
                               [
                               "view_name"=>"partials.v1.divider_title",
                               "view_values" =>[
                                              "title"=>"Operador de red"
                                              ]

                               ],
                              [
                               "view_name"=>"partials.v1.form.form_dropdown_input_searchable",
                               "view_values" =>[
                                                  "input_type"=>"dropdown-search",
                                                    "icon_class"=>"fas fa-desktop",
                                                    "placeholder"=>"Seleccione el operador de red",
                                                    "col_with"=>10,
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
                               ]
              ])

        @endrole

</div>
