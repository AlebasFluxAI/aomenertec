<div>
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"tipos de equipo de administrador"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.admin.listado",
                    ],
                     ["button_align"=>"right",
                         "click_action"=>"",
                         "button_icon"=>"fas fa-computer",
                         "button_content"=>"Agregar equipos",
                         "target_route"=>"administrar.v1.usuarios.admin.agregar_equipos",
                         "target_binding"=>"admin",
                         "target_binding_value"=>$model->id,
                    ],

                ]
        ])
    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.primary-card",[
            'card_title'=>"Administrador",
            'card_subtitle'=>$model->id,
            'card_body'=>[
                            [
                                   "name"=>"Nombre",
                                   "value"=>$model->name
                            ]   ,
                             [
                                   "name"=>"Identificacion",
                                   "value"=>$model->identification
                            ] ,
                                     [
                                   "name"=>"Correo",
                                   "value"=>$model->email
                            ] ,
                         ]
        ])





    @include("partials.v1.equipmentAssignation.equipment_type_assignation",[
        "typeRelated"=>$typeRelated,
        "equipmentTypes"=>$equipmentTypes,
    ])


</div>
