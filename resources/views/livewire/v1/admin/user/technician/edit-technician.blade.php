<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Editar",
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

    <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
        @include("partials.v1.addUserTemplate.user-add-form",[
                      "custom_input"=>[[
                          "view_name"=>"partials.v1.form.form_input_file",
                           "view_values" =>[
                                        "input_type"=>"file",
                                        "input_model"=>"sign",
                                        "icon_class"=>"fas fa-file",
                                        "placeholder"=>"Firma de supervisor",
                                        "col_with"=>12,
                                        "required"=>false,
                                  ]
]
]
                      ])
    </form>


</div>
