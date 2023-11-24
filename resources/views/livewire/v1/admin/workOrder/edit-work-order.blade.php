@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"order de servicio"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>4,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.ordenes_de_servicio.listado",
                    ],
                     [
                    "button_align"=>"right",
                    "button_type"=>"dropdown",
                    "button_icon"=>"fas fa-gear",
                    "button_content"=>"Acciones",
                    "button_options"=>$model->navigatorDropdownOptions()
                 ]

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
                                  "button_content"=>"Editar orden de trabajo"
                      ])

        </form>

    </div>
</div>
