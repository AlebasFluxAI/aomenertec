<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"vendedor"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>2,
            "nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.vendedores.listado",
                    ],

                ]
        ])
    {{----------------------------------Formulario--------------------------}}
    @if(\Illuminate\Support\Facades\Auth::user()->networkOperator)
        <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
            @include("partials.v1.addUserTemplate.user-add-form")
        </form>
    @elseif(\Illuminate\Support\Facades\Auth::user()->admin)
        <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">

            @include("partials.v1.addUserTemplate.user-add-form",[
                          "custom_input"=>[
                               [
                               "view_name"=>"partials.v1.divider_title",
                               "view_values" =>[
                                              "title"=>"Operador de red"
                                              ]

                               ],
                              [
                               "view_name"=>"partials.v1.form.form_dropdown",
                               "view_values" => [
                                        "input_label"=>"Seleccione el operador de red",
                                        "input_type"=>"dropdown",
                                        "icon_class"=>"fas fa-desktop",
                                        "placeholder"=>"Seleccione el operador de red",
                                        "col_with"=>12,
                                        "dropdown_model"=>"model.network_operator_id",
                                        "dropdown_values"=>$network_operators,
                                        "dropdown_result_id"=>"id",
                                        "dropdown_result_value"=>"name",
                                        "dropdown_editing"=>true,
                                      ]
                               ]
                               ]
              ])
        </form>
    @else
        <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">

            @include("partials.v1.addUserTemplate.user-add-form",[
                          "custom_input"=>[
                               [
                               "view_name"=>"partials.v1.divider_title",
                               "view_values" =>[
                                              "title"=>"Administrador"
                                              ]

                               ],
                              [
                               "view_name"=>"partials.v1.form.form_dropdown",
                               "view_values" => [
                                        "input_label"=>"Seleccione administrador",
                                        "input_type"=>"dropdown",
                                        "icon_class"=>"fas fa-desktop",
                                        "placeholder"=>"Seleccione administrador",
                                        "col_with"=>12,
                                        "dropdown_model"=>"admin_id",
                                        "dropdown_values"=>$admins,
                                        "dropdown_result_id"=>"id",
                                        "dropdown_result_value"=>"name",
                                        "dropdown_editing"=>true,
                                      ]
                               ],
                               [
                               "view_name"=>"partials.v1.divider_title",
                               "view_values" =>[
                                              "title"=>"Operador de red"
                                              ]

                               ],
                              [
                               "view_name"=>"partials.v1.form.form_dropdown",
                               "view_values" => [
                                        "input_label"=>"Seleccione el operador de red",
                                        "input_type"=>"dropdown",
                                        "icon_class"=>"fas fa-desktop",
                                        "placeholder"=>"Seleccione el operador de red",
                                        "col_with"=>12,
                                        "disabled"=>($admin_id=="")?true:false,
                                        "dropdown_model"=>"model.network_operator_id",
                                        "dropdown_values"=>$network_operators,
                                        "dropdown_result_id"=>"id",
                                        "dropdown_result_value"=>"name",
                                        "dropdown_editing"=>true,
                                      ]
                               ]
                               ]
              ])
        </form>
    @endif


</div>
