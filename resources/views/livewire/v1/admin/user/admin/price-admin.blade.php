<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Precios",
            "second_title"=>"administrador"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>2,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.admin.listado",
                    ],

                ]
        ])
    {{----------------------------------Formulario--------------------------}}
        <div class="contenedor-grande">

                <form wire:submit.prevent="submitForm" id="formulario" class=" needs-validation" role="form">
                    <div class="row p-4">
                        @include("partials.v1.divider_title",[
                                     "title"=>"Costo paquete minimo"
                                  ])
                        @include("partials.v2.form.form_input_icon",[
                                          "input_model"=>"config.min_value",
                                          "updated_input"=>"defer",
                                          "input_field"=>"",
                                          "input_type"=>"number",
                                          "icon_class"=>null,
                                          "placeholder"=>"Cobro minimo",
                                          "col_with"=>6,
                                          "required"=>true,
                                          "offset"=>'',
                                          "data_target"=>'',
                                          "placeholder_clickable"=>false,
                                          "input_rows"=>0,
                                     ])
                        @include("partials.v2.form.form_input_icon",[
                                         "input_model"=>"config.coin",
                                         "updated_input"=>"defer",
                                         "input_field"=>"",
                                         "input_type"=>"select",
                                         "icon_class"=>null,
                                         "placeholder"=>"Moneda",
                                         "col_with"=>6,
                                         "required"=>true,
                                         "offset"=>'',
                                         "data_target"=>'',
                                         "placeholder_clickable"=>false,
                                         "input_rows"=>0,
                                         "select_options"=>$coins,
                                         "select_option_value"=>"value",
                                         "select_option_view"=>"key",
                                    ])
                        @include("partials.v2.form.form_input_icon",[
                                          "input_model"=>"config.min_clients",
                                          "updated_input"=>"defer",
                                          "input_field"=>"",
                                          "input_type"=>"number",
                                          "icon_class"=>null,
                                          "placeholder"=>"Paquete minimo de clientes",
                                          "col_with"=>12,
                                          "required"=>true,
                                          "offset"=>'',
                                          "data_target"=>'',
                                          "placeholder_clickable"=>false,
                                          "input_rows"=>0,
                                     ])

                        @include("partials.v1.divider_title",[
                                     "title"=>"Costo por tipo de cliente activo"
                                  ])
                        <div class="col-sm-12 col-md-6">
                            <label>Tipo de cliente</label>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label>Costo de administracion mensual</label>
                        </div>

                        @foreach($prices as $index => $price)
                            <div wire:key="prices-field-{{ $price->id }}" class="row form-v2-input mb-2">
                                <div class="col-sm-12 col-md-6">
                                    <li>{{ $price->clientType->type }}</li>
                                </div>

                                @include('partials.v1.form.form_input_icon',[
                                            "col_with"=>6,
                                            "mt"=>2,
                                            "input_model"=>"prices.$index.value",
                                            "updated_input"=>"defer",
                                            "icon_class"=>"fa-solid fa-circle-dollar-to-slot",
                                            "disabled"=>false,
                                            "required"=>true,
                                            "input_type"=> "number",
                                            "placeholder"=> "Valor $",
                                        ])

                            </div>

                        @endforeach

                        @include('partials.v1.form.form_submit_button',[
                                    "button_align"=>"end",
                                    "button_content"=>"Guardar",

                                ])

                    </div>
    </form>
        </div>
</div>
