<div class="login">
    @section("header")
        {{--extended app.blade--}}
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
                                            "title"=>"Seleccione Administrador"
                                            ]

                             ],
                            [
                             "view_name"=>"partials.v1.form.form_list",
                             "view_values" =>[
                                            "col_with"=>8,
                                            "input_type"=>"text",
                                            "input_label"=>"Administrador",
                                            "list_model" => "model.admin_id",
                                            "list_default" => "administrador...",
                                            "list_options" => $admins,
                                            "list_option_value"=>"id",
                                            "list_option_view"=>"name",
                                            "list_option_title"=>"",
                                            ]
                                    ]
                             ]
            ])

        </form>
    @endif


</div>
