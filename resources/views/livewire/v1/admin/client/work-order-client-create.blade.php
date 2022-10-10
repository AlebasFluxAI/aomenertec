@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Nueve orden de",
            "second_title"=>" trabajo de Cliente"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>4,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_binding"=>"client",
                    "target_binding_value"=>$model->id,
                    "target_route"=>"v1.admin.client.work_orders",
                    ],

                ]
        ])


    <div class="contenedor-grande">
        <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">

            @include("partials.v1.divider_title",[
                            "title"=>"Datos de la orden de trabajo  "
                    ]
                   )

            @include("partials.v1.form.form_input_icon",[
                  "input_model"=>"description",
                  "input_label"=>"Descripción de la orden de trabajo",
                  "icon_class"=>"fas fa-edit",
                  "placeholder"=>"Ingrese la descripcion de la orden de trabajo",
                  "col_with"=>12,
                  "input_type"=>"text",
                  "input_rows"=>6,
                  "required"=>true
         ])


            @include("partials.v1.form.form_list",[
                                 "col_with"=>8,
                                 "input_label"=>"Seleccione el tipo de orden de trabajo",
                                 "input_type"=>"text",
                                 "list_model" => "type",
                                 "list_default" => "Tipo de orden de trabajo ...",
                                 "list_options" => $types,
                                 "list_option_value"=>"value",
                                 "list_option_view"=>"key",
                                 "list_option_title"=>"",
                        ])



            @include("partials.v1.divider_title",[
                            "title"=>"Puede agregar imagenes si es necesario"
                    ]
                   )
            @include("partials.v1.form.form_input_file",[
                                 "multiple"=>true,
                                 "input_type"=>"file",
                                 "input_model"=>"photos",
                                 "icon_class"=>"fas fa-file",
                                 "placeholder"=>"Puedes seleccionar varias imagenes",
                                 "col_with"=>12,
                                 "required"=>false,
                                                ])

            @include("partials.v1.divider_title",[
                                     "title"=>"Asigne un tecnico para la orden de trabajo"
                             ]
                            )

            @include("partials.v1.form.form_list",[
             "col_with"=>8,
             "input_type"=>"text",
             "input_label"=>"Tecnico",
             "list_model" => "technician_id",
             "list_default" => "Tecnico...",
             "list_options" => $technicians,
             "list_option_value"=>"value",
             "list_option_view"=>"key",
             "list_option_title"=>"",
             "disabled"=>$technician_select_disabled
    ])
            @include("partials.v1.form.form_submit_button",[
                                  "button_align"=>"right" ,
                                  "button_content"=>"Crear orden de trabajo"
                      ])

        </form>

    </div>
</div>
