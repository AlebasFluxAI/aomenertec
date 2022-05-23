<div>
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"tipos de equipo de tecnico"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>
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
                                   "value"=>$model->identification
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
