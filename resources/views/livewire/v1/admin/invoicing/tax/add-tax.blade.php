<div>
    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"impuesto"
        ])

    @include("partials.v1.table_nav",
         ["mt"=>4,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.facturacion.impuestos.listado",
                    ],

                ]
        ])


    <div class="contenedor-grande">
        <div class="row content p-5">


            <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
                {{--<div> &nbsp;&nbsp; <strong> Agregar manualmente</strong></div>--}}
                <div class="row ">
                    @include("partials.v1.divider_title",[
                                   "title"=>"Información de impuesto"
                        ]
                        )
                    <div class="row pl-5 pr-3">

                        @include("partials.v1.form.form_input_icon",[
                              "input_model"=>"name",
                              "updated_input"=> "defer",
                              "input_label"=>"Nombre del impuesto",
                              "icon_class"=>"fas fa-money-bill",
                              "placeholder"=>"Nombre",
                              "col_with"=>8,
                              "input_type"=>"text",
                              "required"=>true
                        ])

                        @include("partials.v1.form.form_input_icon",[
                            "input_model"=>"description",
                            "updated_input"=> "defer",
                            "input_label"=>"Descripcion del impuesto",
                            "icon_class"=>"fas fa-money-bill",
                            "placeholder"=>"Descripcion del impuesto",
                            "col_with"=>8,
                            "input_type"=>"text",
                            "required"=>true
                        ])

                        @include("partials.v1.form.form_input_icon",[
                            "input_model"=>"percentage",
                            "updated_input"=> "defer",
                            "input_label"=>"Porcentage de impuesto",
                            "icon_class"=>"fas fa-money-bill",
                            "placeholder"=>"Porcentage de impuesto",
                            "col_with"=>8,
                            "input_type"=>"number",
                            "required"=>true
                        ])
                    </div>
                </div>
                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>"Guardar"
                          ])
            </form>
        </div>
    </div>
</div>
