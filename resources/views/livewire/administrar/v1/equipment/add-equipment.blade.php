<<<<<<< HEAD:resources/views/livewire/administrar/v1/add-equipment.blade.php
<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection
=======
@section("header") {{--extended app.blade--}}
@endsection
<<<<<<< HEAD
<div>
<<<<<<< HEAD
            @include("partials.v1.title",[
                    "first_title"=>"Añadir",
                    "second_title"=>"Equipos"
                ])
>>>>>>> d2b72f5 (add table component):resources/views/livewire/administrar/v1/equipment/add-equipment.blade.php

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"Equipos"
        ])

<<<<<<< HEAD:resources/views/livewire/administrar/v1/add-equipment.blade.php
    {{--optiones de cabecera de formulario--}}
=======
            @include("partials.v1.table_nav",
                 ["nav_options"=>[
                            ["button_align"=>"right",
                            "click_action"=>"",
                            "button_icon"=>"fas fa-list",
                            "button_content"=>"Ver listado",
                            "target_route"=>"administrar.equipos.listado",
                            ],
>>>>>>> d2b72f5 (add table component):resources/views/livewire/administrar/v1/equipment/add-equipment.blade.php

=======
=======
<div class="login">
>>>>>>> 5a81a49 (default menu)
    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"Equipos"
        ])

    {{--optiones de cabecera de formulario--}}

>>>>>>> 1efc51d (alert table creared)
    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
<<<<<<< HEAD
<<<<<<< HEAD
                    "target_route"=>"administrar.v1.equipos.listado",
                    ],

<<<<<<< HEAD:resources/views/livewire/administrar/v1/add-equipment.blade.php
                ]
        ])
    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.form.primary_form",[
            "form_toast"=>false,
            "session_message"=>"message",
            "form_submit_action"=>"submitForm",
            "form_inputs"=>[
                            [
                                        "input_type"=>"text",
                                        "input_model"=>"equipmentName",
                                        "icon_class"=>"fas fa-user",
                                        "placeholder"=>"Nombre del equipo",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_type"=>"text",
                                        "input_model"=>"equipmentSerial",
                                        "icon_class"=>"fas fa-barcode",
                                        "placeholder"=>"Serial del equipo",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_type"=>"text",
                                        "input_model"=>"equipmentDescription",
                                        "icon_class"=>"fas fa-file",
                                         "placeholder"=>"Descripcion del equipo",
                                        "col_with"=>12,
                                        "input_rows"=>3,
                                        "required"=>false,
=======
            {{----------------------------------Formulario--------------------------}}
            @include("partials.v1.form.primary_form",[
                    "form_toast"=>false,
                    "session_message"=>"message",
                    "form_submit_action"=>"submitForm",
                    "form_inputs"=>[
                                    [
                                                "input_type"=>"input-text",
                                                "input_model"=>"equipmentName",
                                                "icon_class"=>"fas fa-user",
                                                "placeholder"=>"Nombre del equipo",
                                                "col_with"=>12,
                                                "required"=>true
                                    ],
                                    [
                                                "input_type"=>"input-text",
                                                "input_model"=>"equipmentSerial",
                                                "icon_class"=>"fas fa-barcode",
                                                "placeholder"=>"Serial del equipo",
                                                "col_with"=>12,
                                                "required"=>true
                                    ],
                                    [
                                                "input_type"=>"input-text",
                                                "input_model"=>"equipmentDescription",
                                                "icon_class"=>"fas fa-file",
                                                 "placeholder"=>"Descripcion del equipo",
                                                "col_with"=>12,
                                                "input_rows"=>3,
                                                "required"=>false,
>>>>>>> d2b72f5 (add table component):resources/views/livewire/administrar/v1/equipment/add-equipment.blade.php

                             ],
                             [
                                        "input_type"=>"dropdown-search",
                                        "icon_class"=>"fas fa-desktop",
                                        "placeholder"=>"Seleccione el tipo de equipo",
                                        "col_with"=>12,
                                        "dropdown_model"=>"equipmentTypeId",
                                        "dropdown_enter_function"=>"updatedEquipmentTypeId",
                                        "picked_variable"=>$picked,
                                        "dropdown_results"=>$equipmentTypes,
                                        "selected_value_function"=>"setEquipmentType",
                                        "dropdown_result_id"=>"id",
                                        "dropdown_result_value"=>"type",

                            ]

                         ]
                 ])
=======
                    "target_route"=>"administrar.equipos.listado",
=======
                    "target_route"=>"administrar.v1.equipos.listado",
>>>>>>> 5a81a49 (default menu)
                    ],

                ]
        ])
    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.form.primary_form",[
            "form_toast"=>false,
            "session_message"=>"message",
            "form_submit_action"=>"submitForm",
            "form_inputs"=>[
                            [
                                        "input_type"=>"text",
                                        "input_model"=>"equipmentName",
                                        "icon_class"=>"fas fa-user",
                                        "placeholder"=>"Nombre del equipo",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_type"=>"text",
                                        "input_model"=>"equipmentSerial",
                                        "icon_class"=>"fas fa-barcode",
                                        "placeholder"=>"Serial del equipo",
                                        "col_with"=>12,
                                        "required"=>true
                            ],
                            [
                                        "input_type"=>"text",
                                        "input_model"=>"equipmentDescription",
                                        "icon_class"=>"fas fa-file",
                                         "placeholder"=>"Descripcion del equipo",
                                        "col_with"=>12,
                                        "input_rows"=>3,
                                        "required"=>false,

                             ],
                             [
                                        "input_type"=>"dropdown-search",
                                        "icon_class"=>"fas fa-desktop",
                                        "placeholder"=>"Seleccione el tipo de equipo",
                                        "col_with"=>12,
                                        "dropdown_model"=>"equipmentTypeId",
                                        "dropdown_enter_function"=>"updatedEquipmentTypeId",
                                        "picked_variable"=>$picked,
                                        "dropdown_results"=>$equipmentTypes,
                                        "selected_value_function"=>"setEquipmentType",
                                        "dropdown_result_id"=>"id",
                                        "dropdown_result_value"=>"type",

                            ]
>>>>>>> 1efc51d (alert table creared)

                         ]
                 ])

<<<<<<< HEAD:resources/views/livewire/administrar/v1/add-equipment.blade.php
=======

>>>>>>> d2b72f5 (add table component):resources/views/livewire/administrar/v1/equipment/add-equipment.blade.php
</div>
