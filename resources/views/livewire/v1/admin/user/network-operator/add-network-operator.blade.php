<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"operador de red"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>2,
          "nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.operadores.listado",
                    ],

                ]
        ])
    {{----------------------------------Formulario--------------------------}}
    @if(\Illuminate\Support\Facades\Auth::user()->admin)
        <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
            @include("partials.v1.addUserTemplate.user-add-form")
        </form>
    @else
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
                             "view_name"=>"partials.v1.form.form_dropdown_input_searchable",
                             "view_values" =>[
                                            "form_group" => true,
                                            "col_with"=>8,
                                            "dropdown_model" => "admin_id",
                                            "placeholder"=>"Seleccione el administrador",
                                            "input_label"=>"Seleccione el operador de red",
                                            "required" => false,
                                            "picked_variable"=>$picked,
                                            "dropdown_results"=>$admins,
                                            "count_bool"=>true,
                                            "selected_value_function"=>"setAdminId",
                                            "dropdown_result_id"=>"id",
                                            "dropdown_result_value"=>"name",
                                            ]
                                    ]
                             ]
            ])

        </form>
    @endif


</div>
