<div class="login">

    @include("partials.v1.form.primary_form",[
           "form_toast"=>false,
           "session_message"=>"message",
           "form_submit_action"=>"submitForm",
           "form_inputs"=>[
                            [
                                       "input_type"=>"dropdown",
                                       "icon_class"=>"fas fa-desktop",
                                       "placeholder"=>"Seleccione el tipo de equipo",
                                       "col_with"=>12,
                                       "dropdown_model"=>"equipmentTypeId",
                                       "dropdown_values"=>$equipmentTypes,
                                       "dropdown_result_id"=>"id",
                                       "dropdown_result_value"=>"type",
                                       "dropdown_editing"=>false,
                                       "dropdown_refresh"=>"pass"

                           ]

                        ]
                ])

    @include("partials.v1.table.primary-table", [
                       "table_pageable"=>false,
                                                             "table_headers"=>[
                                                                 "ID"=>"equipmentType.id",
                                                                 "Tipo"=>"equipmentType.type",
                                                                 ],
                                                             "table_actions"=>[
                                                                    "customs"=>[
                                                                        [
                                                                         "function"=>"delete",
                                                                         "icon"=>"fas fa-trash",
                                                                         "model_id"=>"id"
                                                                        ]
                                                                         ]
                                                                    ],
                                                             "table_rows"=>$typeRelated,

         ])
</div>
