<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"clientes de vendedor"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.vendedores.listado",
                    ],

                ]
        ])
    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.primary-card",[
            'card_title'=>"Vendedor",
            'card_subtitle'=>$model->id,
            'card_body'=>[
                            [
                                   "name"=>"Nombre",
                                   "value"=>$model->name
                            ]   ,
                             [
                                   "name"=>"Identificacion",
                                   "value"=>$model->identificacion
                            ] ,
                                     [
                                   "name"=>"Correo",
                                   "value"=>$model->email
                            ] ,
                         ]
        ])
    @include("partials.v1.form.primary_form",[
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
                                        "dropdown_result_id" => "id",
                                        "dropdown_result_value" => "name",
                                        "count_bool" => (count($clients)>0),

                    ]

                 ]
         ])


    @include("partials.v1.table.primary-table", [
                           "table_pageable"=>false,
                                                                 "table_headers"=>["ID"=>"id",
                                                                     "Nombre"=>"name",
                                                                     "Apellido"=>"last_name",
                                                                     "Correo electronico"=>"email",
                                                                     "Telefono"=>"phone",

                                                                     ],
                                                                 "table_actions"=>[
                                                                        "delete"=>"delete",

                                                                        ],
                                                                 "table_rows"=>$model->clients,

             ])

</div>
