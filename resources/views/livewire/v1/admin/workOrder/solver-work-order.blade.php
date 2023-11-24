@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Gestionar orden",
            "second_title"=>" de trabajo"
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

    @include("partials.v1.divider_title",[
                          "title"=>"Datos de la orden de trabajo  "
                  ]
                 )
    @include("partials.v1.table.primary-details-table",[
                                                                    "table_info"=>[
                                                                     [
                                                                         "key"=>"Id",
                                                                         "value"=>$model->id
                                                                     ],
                                                                     [
                                                                         "key"=>"Descripción",
                                                                         "value"=>$model->description
                                                                     ],
                                                                     [
                                                                         "key"=>"Cliente",
                                                                         "value"=>$model->client?($model->client->name." ". $model->client->last_name."-". $model->client->identification):"Sin cliente relacionado",
                                                                         "redirect_route"=>"v1.admin.client.detail.client",
                                                                         "redirect_binding"=>"client",
                                                                         "redirect_value"=>$model->client_id,
                                                                     ],
                                                                     [
                                                                             "key"=>"Tecnico asignado",
                                                                             "value"=>$model->technician?$model->technician->name." ".$model->technician->last_name."-".$model->technician->identification:"",
                                                                             "redirect_route"=>"administrar.v1.usuarios.tecnicos.detalles",
                                                                             "redirect_binding"=>"technician",
                                                                             "redirect_value"=>$model->technician_id
                                                                     ],
                                                                     [
                                                                          "key"=>"Imagenes adjuntas",
                                                                          "type"=>"image_multiple",
                                                                           "value"=>$model->images,
                                                                     ],
                            ]]
                                             )


    @include("partials.v1.divider_title",[
                         "title"=>"Gestionar orden de trabajo"
                 ]
                )
    <div class="contenedor-grande">
        <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">


            @include("partials.v1.form.form_input_icon",[
                  "input_model"=>"solution_description",
                  "input_label"=>"Danos una descripcion del trabajo realizado sobre esta orden",
                  "icon_class"=>"fas fa-edit",
                  "placeholder"=>"Ingrese la descripcion de la orden de trabajo",
                  "col_with"=>12,
                  "input_type"=>"text",
                  "input_rows"=>6,
                  "required"=>true
         ])

            @include("partials.v1.divider_title",[
                      "title"=>"Puedes especificar el tiempo de ejecucion o dejar que se calcule automaticamente"
              ]
             )
            @include("partials.v1.form.form_input_icon",[
                 "input_model"=>"execution_time_hours",
                 "input_label"=>"Numero de horas ejecutadas",
                 "icon_class"=>"fas fa-clock",
                 "placeholder"=>"Ingrese el numero de horas ejecutadas en la orden de servicio",
                 "col_with"=>3,
                 "min_number"=>0,
                 "input_type"=>"number",
                 "required"=>false,
           ])

            @include("partials.v1.form.form_input_icon",[
                 "input_model"=>"execution_time_minutes",
                 "input_label"=>"Numero de minutos ejecutados",
                 "icon_class"=>"fas fa-clock",
                 "placeholder"=>"Ingrese el numero de minutos ejecutadas en la orden de servicio",
                 "col_with"=>3,
                 "min_number"=>0,
                 "input_type"=>"number",
                 "required"=>false,
           ])

            @include("partials.v1.divider_title",[
                            "title"=>"Agrega aqui evidencias de tu trabajo"
                    ]
                   )
            @include("partials.v1.form.form_input_file",[
                                 "multiple"=>true,
                                 "input_type"=>"file",
                                 "input_model"=>"evidences",
                                 "icon_class"=>"fas fa-file",
                                 "file_accepted"=>".png,.jpg,.gif,.webp,.bmp,.pdf,.docx",
                                 "placeholder"=>"Puedes seleccionar varias imagenes",
                                 "col_with"=>12,
                                 "required"=>false,
                                                ])
            @if(false)
                <div class="row pl-5 pr-3">

                    @include("partials.v1.divider_title",[
                                             "title"=>"Equipo intervenido"
                                     ]
                                    )
                    <div class="form-group mb-2 align-content-start col-md-3 col-sm-12">
                        @include("partials.v1.form.form_list",[
                                 "col_with"=>8,
                                 "mb"=>0,
                                 "aux_class"=>"no-border",
                                 "list_model" => "equipment_type_id",
                                 "list_default" => "Seleccione equipo...",
                                 "list_options" => $equipment_types??[],
                                 "list_option_value"=>"id",
                                 "list_option_view"=>"type",
                                 "list_option_title"=>""
                        ])
                        @include("partials.v1.form.form_dropdown_input_searchable",[
                                  "form_group" => false,
                                   "col_with"=>8,
                                  "dropdown_model" => "equipment_serial",
                                  "required" => false,
                                  "picked_variable" => $equipment_picked,
                                  "dropdown_results" => $bachelors_equipments,
                                  "count_bool" => count($bachelors_equipments)>0,
                                  "selected_value_function" => "assignEquipment",
                                  "dropdown_result_id" => "id",
                                  "dropdown_result_value" => "serial",
                        ])
                    </div>
            @endif

            @include("partials.v1.form.form_input_icon",[
             "input_model"=>"final_recommendations",
             "input_label"=>"Si tienes recomendaciones finales por favor escribelas aqui",
             "icon_class"=>"fas fa-edit",
             "placeholder"=>"Ingrese las sugerencias finales",
             "col_with"=>12,
             "input_type"=>"text",
             "input_rows"=>6,
             "required"=>true
    ])
            @if($model->type==\App\Models\V1\WorkOrder::WORK_ORDER_TYPE_DISABLE_CLIENT or $model->type==\App\Models\V1\WorkOrder::WORK_ORDER_TYPE_ENABLE_CLIENT)
                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>"Cerrar orden de trabajo",
                                      "modal_content"=>"Al completar esta orden de trabajo cambiara
                                      el estado del cliente ".$model->client->alias,
                                      "function"=>"submitForm"
                          ])
            @else

                @include("partials.v1.form.form_submit_button",[
                                "button_align"=>"right" ,
                                "button_content"=>"Cerrar orden de trabajo",
                                "modal_content"=>"Cerrar orden de trabajo",
                                "function"=>"submitForm"
                    ])
            @endif

        </form>

    </div>
</div>
