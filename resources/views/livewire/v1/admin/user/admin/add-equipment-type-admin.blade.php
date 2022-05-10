<div>
    @section("header") {{--extended app.blade--}}
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


    @include("partials.v1.equipmentAssignation.equipment_type_assignation",[
        "typeRelated"=>$typeRelated,
        "equipmentTypes"=>$equipmentTypes,
    ])


</div>
