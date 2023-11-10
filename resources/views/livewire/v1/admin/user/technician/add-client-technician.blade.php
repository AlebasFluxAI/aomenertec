<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"clientes de tecnico"
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
    @include("partials.v1.primary-card",[
            'card_title'=>"Tecnico",
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


    @include("partials.v1.clientAssignation.client_assignation",[
                                   'client_picked'=>$client_picked,
                                  'message_client'=>$message_client,
                                  'clients'=>$clients,
                                  'clients'=>$clients,
                                  'clientsRelated'=>$clientsRelated,
          ])

</div>
