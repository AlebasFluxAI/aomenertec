<div class="login">
    <div class="contenedor-grande">
        @include("partials.v1.form.primary_form",[
                     "class_container"=>"",
                     "form_toast"=>false,
                     "session_message"=>"message",
                     "form_submit_action"=>"addClient",
                     "form_submit_action_text"=>"Agregar cliente",
                     "form_inputs"=>[
                                    [
                                         "input_type"=>"dropdown-search",
                                         "icon_class" => "fas fa-user",
                                         "dropdown_model" => "client",
                                         "placeholder" => "Cliente",
                                         'col_with'=>12,
                                         "required" => true,
                                         "picked_variable" => $client_picked,
                                         "message_variable" => $message_client,
                                         "dropdown_results" => $clients,
                                         "selected_value_function" => "assignClient",
                                         "dropdown_result_id" => "identification",
                                         "dropdown_result_value" => "name",
                                         "count_bool" => (count($clients)>0),

                     ]

                  ]
          ])


        @include("partials.v1.table.primary-table", [
                               "table_pageable"=>false,
                                                                     "table_headers"=>[
                                                                         "ID"=>"client.id",
                                                                         "Nombre"=>"client.name",
                                                                         "Apellido"=>"client.last_name",
                                                                         "Correo electronico"=>"client.email",
                                                                         "Telefono"=>"client.phone",

                                                                         ],
                                                                     "table_actions"=>[
                                                                            "customs"=>[
                                                                                [
                                                                                 "function"=>"delete",
                                                                                 "icon"=>"fas fa-trash",
                                                                                 "model_id"=>"client"
                                                                                ]
                                                                                 ]
                                                                            ],
                                                                     "table_rows"=>$clientsRelated,

                 ])
    </div>
</div>
