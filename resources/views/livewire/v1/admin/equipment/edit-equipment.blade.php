@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"Equipos"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>2,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.equipos.listado",
                    ],
                    [
                    "button_align"=>"right",
                    "button_type"=>"dropdown",
                    "button_icon"=>"fas fa-gear",
                    "button_content"=>"Acciones",
                    "button_options"=>$equipment->navigatorDropdownOptions()
                  ]

                ]
        ])
    {{----------------------------------Formulario--------------------------}}
    <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
        <div class="contenedor-grande">
            <div class="row content p-5">

                @include("partials.v1.form.form_input_icon",[
                                "input_label"=>"Nombre del equipo",
                                "input_model"=>"equipmentName",
                                "icon_class"=>"fas fa-computer",
                                "placeholder"=>"Nombre del equipo",
                                "col_with"=>8,
                                "input_type"=>"text",
                       ])
                @include("partials.v1.form.form_input_icon",[
                             "input_label"=>"Serial del equipo",
                             "input_model"=>"equipmentSerial",
                             "icon_class"=>"fas fa-barcode",
                             "placeholder"=>"Serial del equipo",
                             "col_with"=>8,
                             "input_type"=>"text",
                    ])
                @include("partials.v1.form.form_input_icon",[
                         "input_label"=>"Descripcion del equipo",
                         "input_model"=>"equipmentDescription",
                         "icon_class"=>"fas fa-edit",
                         "placeholder"=>"Descripcion del equipo",
                         "col_with"=>8,
                         "input_rows"=>3,
                         "input_type"=>"text",
                ])

                @include("partials.v1.form.form_list",[
                               "col_with"=>8,
                               "input_label"=>"Seleccione el estado del equipo",
                               "input_type"=>"text",
                               "list_model" => "status",
                               "list_default" => "Estado de equipo ...",
                               "list_options" => $equipment_status??[],
                               "list_option_value"=>"value",
                               "list_option_view"=>"key",
                               "list_option_title"=>"",
                      ])
                <br>
                @include("partials.v1.divider_title",["title"=>"Equipos con multiples relaciones a clientes"])
                @include("partials.v1.form.check_button",[
                                       "check_label"=>"¿ Equipo con multiple conexión ?",
                                       "check_model"=>"has_multiple_connection",
                                       "icon_class"=>"fas fa-edit",
                                       "placeholder"=>"Descripcion del equipo",
                                       "col_with"=>10,
                              ])
                @include("partials.v1.form.form_submit_button",[
                                 "button_align"=>"right" ,
                                 "button_content"=>$form_submit_action_text??"Guardar"
                     ])
            </div>
        </div>
    </form>

</div>
