
<div>
    <section class="top-info bg-light">
        @include("layouts.v1.app_admin_header")
        <div class="container">
            @include("partials.v1.title",[
                    "first_title"=>"Añadir",
                    "second_title"=>"Equipos"
                ])

            {{--optiones de cabecera de formulario--}}

            @include("partials.v1.table_nav",
                 ["nav_options"=>[
                            ["button_align"=>"right",
                            "click_action"=>"",
                            "button_icon"=>"fas fa-list",
                            "button_content"=>"Ver listado"
                            ],
                        ]
                ])

            {{--------------------------------------------}}



            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <div class="contenedor-grande">
                <div class="row content pt-6">
                    <form  wire:submit.prevent="submit" id="equipmentForm" class="needs-validation"   role="form">
                        <div class="row ">
                            @include("partials.v1.form.form_input_icon",[
                                    "input_model"=>"equipmentName",
                                     "icon_class"=>"fas fa-user",
                                     "placeholder"=>"Nombre del equipo",
                                     "col_with"=>12,
                                     "required"=>true
                            ])

                            @include("partials.v1.form.form_input_icon",[
                                    "input_model"=>"equipmentSerial",
                                    "icon_class"=>"fas fa-barcode",
                                    "placeholder"=>"Serial del equipo",
                                    "col_with"=>12,
                                    "required"=>true
                           ])


                            @include("partials.v1.form.form_input_icon",[
                                 "input_model"=>"equipmentDescription",
                                  "icon_class"=>"fas fa-file",
                                  "placeholder"=>"Descripcion del equipo",
                                   "col_with"=>12,
                                   "input_rows"=>3,
                                   "required"=>false,

                         ])

                        @include("partials.v1.form.form_dropdown_input_searchable",[
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

                        ])

                        @include("partials.v1.form.form_submit_button",[
                                    "button_align"=>"right" ,
                                    "button_content"=>"Guardar"
                        ])

                    </form>
                </div>
                <div class="mb-3">

                </div>
            </div>
        </div>
    </section>

</div>

