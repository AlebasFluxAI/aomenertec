<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"equipos de operador de red"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.operadores.listado",
                    ],

                ]
        ])
    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.primary-card",[
            'card_title'=>"Operador de red",
            'card_subtitle'=>$model->id,
            'card_body'=>[
                            [
                                   "name"=>"Nombres",
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


    @include("partials.v1.equipmentAssignation.equipment_assignation",[
         "equipmentRelated"=>$equipmentRelated,
         "equipments"=>$equipments,

     ])


</div>
