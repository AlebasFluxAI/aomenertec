<div class="login">
    @include("partials.v1.divider_title",["title"=>"Tipos de clientes"])

    @include("partials.v1.form.primary_form",[
                                                                    "form_toast"=>true,
                                                                    "class_container"=>"",
                                                                    "session_message"=>"message",
                                                                    "form_submit_action"=>"submitFormConfiguration",
                                                                    "form_title"=>"",
                                                                    "form_inputs"=> [

                                                                                     [
                                                                                            "input_type"=>"multiselect",
                                                                                            "input_label"=>"Asignar tipos de clientes",
                                                                                            "mb"=>2,
                                                                                            "tooltip_title"=>"Seleccione los tipos de clientes habilitados para este administrador, Pulse Guardar para confirmar.",
                                                                                            "options_list"=> $client_types,
                                                                                            "model_select"=>"admin_client_types",
                                                                                            "col_width"=>4,
                                                                                            "name_select"=>"client_types",
                                                                                            "option_value"=>"id",
                                                                                            "option_view"=>"type",
                                                                                    ],

                                                                                ]
                                                                    ])

    @include("partials.v1.divider_title",["title"=>"Canales de notificacion"])
    @include("partials.v2.table.primary-table",[
              "class_container"=>false,
              "table_pageable"=>false,
              "table_headers"=>[
                  [
                      "col_name" =>"ID",
                      "col_data" =>"id",
                      "col_filter"=>false,
                  ],
                  [
                      "col_name" =>"Canal",
                      "col_data" =>"channel",
                      "col_translate"=>"channels",
                      "col_filter"=>false,
                  ],
                  [
                      "col_name" =>"Activo",
                      "col_data" =>"enabled",
                      "col_filter"=>false,
                      "col_type"=>\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN
                  ],

               ],
                "table_actions"=>[

                                   "customs"=>[
                                        [
                                                        "function"=>"blinkChannel",
                                                        "icon"=>"fas fa-power-off",
                                                        "tooltip_title"=>"Activar/Desactivar"
                                                    ],
                                      ]
                                   ],

              "table_rows"=>$channels

          ])
</div>
