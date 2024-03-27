@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Nueva orden de",
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
            @if($type!=\App\Models\V1\WorkOrder::WORK_ORDER_TYPE_DISABLE_CLIENT)

                @include("partials.v1.divider_title",[
                                         "title"=>"Materiales y herramientas a utilizar"
                                 ]
                                )

                @include("partials.v1.checkbox_list",[
                                    "label"=>"Seleccione las herramientas a utilizar",
                                    "options_list"=>$toolsList,
                                    "functionChanged"=>"changeTool"
                ])

                <br>

                @if($otherTool)
                    @include("partials.v1.form.form_input_icon",[
                        "input_model"=>"tools",
                        "input_label"=>"Agregar otras herramientas a utilizar (separadas por comas)",
                        "icon_class"=>"fas fa-screwdriver-wrench",
                        "placeholder"=>"Ingrese las herramientas necesarias",
                        "col_with"=>12,
                        "input_type"=>"text",
                        "input_rows"=>2,
                        "required"=>true
               ])
                @endif

                @include("partials.v1.checkbox_list",[
                                      "label"=>"Seleccione los materiales a utilizar",
                                      "options_list"=>$materialsList,
                                      "functionChanged"=>"changeMaterials"
                  ])


                <br>

                @if($otherMaterials)
                    @include("partials.v1.form.form_input_icon",[
                         "input_model"=>"materials",
                         "input_label"=>"Agregar otros materiales a utilizar (separadas por comas)",
                         "icon_class"=>"fas fa-trowel-bricks",
                         "placeholder"=>"Ingrese los otros materiales necesarios",
                         "col_with"=>12,
                         "input_type"=>"text",
                         "input_rows"=>2,
                         "required"=>true
                ])
                @endif

                @include("partials.v1.divider_title",[
                                "title"=>"Puede agregar imagenes si es necesario"
                        ]
                       )

                @foreach($images as  $key=>$image)
                    <div class="col-md-12">
                        <div class="row">
                            <br>
                            @include("partials.v1.form.form_input_file",[
                                                 "multiple"=>false,
                                                 "input_type"=>"file",
                                                 "input_model"=>$image,
                                                 "icon_class"=>"fas fa-file",
                                                 "placeholder"=>"Selecciona la imagen ".$key+1,
                                                 "col_with"=>5,
                                                 "required"=>false,
                                                                ])

                            @if($this->{$image})
                                @include("partials.v1.form.form_input_icon",[
                                         "input_model"=>"description".$key+1,
                                         "input_label"=>"Descripción de la imagen ".$key+1,
                                         "icon_class"=>"fas fa-edit",
                                         "placeholder"=>"Ingrese la descripcion de la imagen ".$key+1,
                                         "col_with"=>5,
                                         "input_type"=>"text",
                                         "input_rows"=>2,
                                         "required"=>true
                                   ])
                            @endif

                        </div>
                    </div>
                @endforeach

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
                 "required"=>true,
                 "disabled"=>$technician_select_disabled,
                 "not_selection"=>"No seleccionar tecnico"
                ])
                @if(\App\Models\V1\User::getUserModel()::class==\App\Models\V1\SuperAdmin::class)
                    @include("partials.v1.form.form_list",[
                            "col_with"=>8,
                            "input_type"=>"text",
                            "input_label"=>"Usuario de soporte",
                            "list_model" => "support_id",
                            "list_default" => "Usuario de soporte...",
                            "list_options" => $supports,
                            "list_option_value"=>"value",
                            "list_option_view"=>"key",
                            "list_option_title"=>"",
                            "required"=>true,
                            "disabled"=>$support_select_disabled,
                            "not_selection"=>"No seleccionar usuario de soporte"
                    ])
                @endif


                @include("partials.v1.divider_title",[
                                "title"=>"Selecciona el equipo a intervenir (Opcional)"
                        ])

                @include("partials.v1.checkbox_list",[
                                                 "label"=>"Seleccione los equipos a intervenir",
                                                 "options_list"=>$equipmentsBachelor,
                                                 "functionChanged"=>"changeEquipment"
                             ])

                @include("partials.v1.divider_title",[
                               "title"=>"Ingrese la estimación de tiempo ¿ Cuanto tiempo debe ser invertido para esta orden de trabajo?"
                       ])

                <div class="col-md-4"
                     style="margin: auto;background-color: #f2f2f2;border-radius: 15px;padding: 40px">

                    <div class="row">

                        @include("partials.v1.form.form_input_icon",[
                                   "input_model"=>"days",
                                   "input_label"=>"Dias",
                                   "placeholder"=>"",
                                   "col_with"=>4,
                                   "input_type"=>"number",
                                   "required"=>false
                             ])
                        @include("partials.v1.form.form_input_icon",[
                                   "input_model"=>"hours",
                                   "input_label"=>"Horas",
                                   "placeholder"=>"",
                                   "col_with"=>4,
                                   "input_type"=>"number",
                                   "required"=>false
                             ])

                        @include("partials.v1.form.form_input_icon",[
                                   "input_model"=>"minutes",
                                   "input_label"=>"Minutos",
                                   "placeholder"=>"",
                                   "col_with"=>4,
                                   "input_type"=>"number",
                                   "required"=>false
                             ])

                    </div>
                </div>
            @endif
            @include("partials.v1.form.form_submit_button",[
                                  "button_align"=>"right" ,
                                  "button_content"=>"Crear orden de trabajo"
                      ])

        </form>

    </div>
</div>
