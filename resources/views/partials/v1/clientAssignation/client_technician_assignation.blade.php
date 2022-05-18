<div class="login">


    @include("partials.v1.form.primary_form",[
                 "form_toast"=>false,
                 "form_help"=>"Dentro del este modulo es posible relacionar un tecnico a un cliente,
                            los cliente solo pueden estar relacionados a un tecnico a la  vez, si desea cambiar el tecnico relacionado primero
                            elimine la relacion usando el listado de tecnicos relacionados",
                 "session_message"=>"message",
                 "form_submit_action"=>"relateTechnician",
                 "form_submit_action_text"=>"Relacionar tecnico",
                 "form_inputs"=>[
                               [
                                       "input_type"=>"dropdown",
                                       "icon_class"=>"fas fa-desktop",
                                       "placeholder"=>"Seleccione el tipo de equipo",
                                       "col_with"=>12,
                                       "dropdown_model"=>"technicianId",
                                       "dropdown_values"=>$technicians,
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
                                                                     "ID"=>"technician.id",
                                                                     "Nombre"=>"technician.name",
                                                                     "Apellido"=>"technician.last_name",
                                                                     "Correo electronico"=>"technician.email",
                                                                     "Telefono"=>"technician.phone",

                                                                     ],
                                                                 "table_actions"=>[
                                                                        "customs"=>[
                                                                                    [
                                                                                     "function"=>"delete",
                                                                                     "icon"=>"fas fa-trash",
                                                                                     "tooltip_title"=>"Eliminar tecnico"
                                                                                    ]
                                                                             ]
                                                                        ],
                                                                 "table_rows"=>$technician_related,

             ])
</div>
