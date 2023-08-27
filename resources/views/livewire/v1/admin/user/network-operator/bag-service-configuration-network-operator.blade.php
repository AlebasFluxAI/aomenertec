@if($view_header??true)
    <div class="login">
        @section("header")
            {{--extended app.blade--}}
        @endsection

        @include("partials.v1.title",[
                "first_title"=>"Configuración",
                "second_title"=>"facturacion"
            ])

        <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
            @include("partials.v1.divider_title",[
              "title"=>"Servicios facturables para el operador"
      ])


            @include("partials.v1.form.radio_button",[
                                            "mt"=>0,
                                            "mb"=>0,
                                            "col_width"=>8,
                                            "input_model"=>"has_billable_pqr",
                                            "input_label"=>"Facturar PQR gestionada",
                                            "check_id"=>"penalizable",
            ])
            <br>
            @include("partials.v1.form.radio_button",[
                                            "mt"=>0,
                                            "mb"=>0,
                                            "col_width"=>8,
                                            "input_model"=>"has_billable_orders",
                                            "input_label"=>"Facturar ordenes de trabajo gestionada",
                                            "check_id"=>"penalizable",
            ])
            <br>
            @include("partials.v1.form.radio_button",[
                                  "mt"=>0,
                                  "mb"=>0,
                                  "col_width"=>8,
                                  "input_model"=>"has_billable_clients",
                                  "input_label"=>"Facturar uso de plataforma de clientes",
                                  "check_id"=>"penalizable",
  ])
            @include("partials.v1.divider_title",[
              "title"=>"Facturacion"
      ])

            @include("partials.v1.form.form_input_icon",[
              "input_model"=>"billing_day",
              "input_label"=>"Dia de facturacion",
               "updated_input"=>"defer",
              "icon_class"=>"fas fa-calendar",
              "placeholder"=>"Ingrese el dia de facturación",
              "col_with"=>6,
              "min_number"=>1,
              "max_number"=>31,
              "input_type"=>"number",
              "required"=>true,
     ])

            @include("partials.v1.form.form_input_icon",
            ["input_model"=>"currency",
            "updated_input"=>"defer",
            "input_field"=>"",
            "input_label"=>"Seleccione la moneda",
            "input_type"=>"select",
            "icon_class"=>null,
            "placeholder"=>"Moneda",
            "col_with"=>6,
            "required"=>true,
            "offset"=>'',
            "data_target"=>'',
            "placeholder_clickable"=>false,
            "input_rows"=>0,
            "select_options"=>$currencies,
            "select_option_value"=>"value",
            "select_option_view"=>"key",
            ])

            @include("partials.v1.divider_title",[
                               "title"=>"Paquete de clientes"
                       ])

            @include("partials.v1.form.form_input_icon",[
              "input_model"=>"min_clients",
              "input_label"=>"Numero inicial de clientes",
               "updated_input"=>"defer",
              "icon_class"=>"fas fa-list",
              "placeholder"=>"Ingrese el paquete inicial de clientes",
              "col_with"=>6,
              "min_number"=>0,
              "input_type"=>"number",
              "required"=>true,
     ])
            @include("partials.v1.form.form_input_icon",[
             "input_model"=>"min_client_value",
             "input_label"=>"Costo paquete inicial de clientes",
              "updated_input"=>"defer",
             "icon_class"=>"fas fa-money-bill",
             "placeholder"=>"Ingrese el costo del paquete inicial de clientes",
             "col_with"=>6,
             "min_number"=>0,
             "input_type"=>"number",
             "required"=>true,
    ])

            @include("partials.v1.divider_title",[
                    "title"=>"Pqrs"
            ])
            @endif

            @include("partials.v1.form.form_input_icon",[
                 "input_model"=>"pqr_bag",
                 "input_label"=>"Numero de PQR iniciales gestionados",
                  "updated_input"=>"defer",
                 "icon_class"=>"fas fa-list",
                 "placeholder"=>"Ingrese el paquete inicial de Pqr gestionado",
                 "col_with"=>6,
                 "min_number"=>0,
                 "input_type"=>"number",
                 "tooltip_title"=>"Puedes configurar la bolsa inicial de servicio que seran gratuitos para este operador de red, una vez
            superado este rango se iniciaran los cobros.",
                 "required"=>true,
        ])
            @include("partials.v1.form.form_input_icon",[
             "input_model"=>"pqr_price",
             "input_label"=>"Costo de pqr gestionada",
              "updated_input"=>"defer",
             "icon_class"=>"fas fa-money-bill",
             "placeholder"=>"Ingrese el costo del pqr gestionado",
             "col_with"=>6,
             "min_number"=>0,
             "input_type"=>"number",
             "required"=>true,
    ])

            @include("partials.v1.form.form_input_icon",[
                            "input_model"=>"initial_package_pqr_price",
                            "input_label"=>"Costo del paquete inicial de pqr",
                             "updated_input"=>"defer",
                            "icon_class"=>"fas fa-money-bill",
                            "placeholder"=>"Ingrese el costo del paquete inicial de pqr",
                            "col_with"=>6,
                            "min_number"=>0,
                            "input_type"=>"number",
                            "required"=>true,
                   ])
            @include("partials.v1.divider_title",[
                    "title"=>"Ordenes de servicio"
            ])

            @include("partials.v1.form.form_input_icon",[
                 "input_model"=>"work_order_hours",
                 "input_label"=>"Numero de horas iniciales gestionados",
                  "updated_input"=>"defer",
                 "icon_class"=>"fas fa-clock",
                 "placeholder"=>"Ingrese el paquete inicial de horas gestionado",
                 "col_with"=>6,
                 "min_number"=>0,
                 "input_type"=>"number",
                 "tooltip_title"=>"Puedes configurar la bolsa inicial de servicio que seran gratuitos para este operador de red, una vez
            superado este rango se iniciaran los cobros.",
                 "required"=>true,
        ])


            @include("partials.v1.form.form_input_icon",[
                 "input_model"=>"orders_price",
                 "input_label"=>"Costo de hora gestionada",
                  "updated_input"=>"defer",
                 "icon_class"=>"fas fa-money-bill",
                 "placeholder"=>"Ingrese el costo de la hora gestionada",
                 "col_with"=>6,
                 "min_number"=>0,
                 "input_type"=>"number",
                 "required"=>true,
        ])
            @include("partials.v1.form.form_input_icon",[
                 "input_model"=>"initial_package_orders_price",
                 "input_label"=>"Costo del paquete inicial de ordenes de servicio",
                  "updated_input"=>"defer",
                 "icon_class"=>"fas fa-money-bill",
                 "placeholder"=>"Ingrese el costo del paquete inicial de ordenes de servicio",
                 "col_with"=>6,
                 "min_number"=>0,
                 "input_type"=>"number",
                 "required"=>true,
        ])
            @include("partials.v1.divider_title",[
                "title"=>"Costo por tipo de cliente activo"
            ])

            @include("partials.v2.form.form_input_icon",[
              "input_model"=>"prices_zni_fotovoltaico",
              "input_label"=>'ZNI Sistema fotovoltaico',
              "updated_input"=>"defer",
              "icon_class"=>"fa-solid fa-circle-dollar-to-slot",
              "col_with"=>12,
              "min_number"=>0,
              "input_type"=>"number",
              "required"=>false,
              "placeholder_clickable"=>false,
              "placeholder"=>"ZNI Sistema fotovoltaico",
            ])

            @include("partials.v2.form.form_input_icon",[
                  "input_model"=>"zni_conventional",
                  "input_label"=>"ZNI Convencional",
                  "updated_input"=>"defer",
                  "icon_class"=>"fa-solid fa-circle-dollar-to-slot",
                  "col_with"=>12,
                  "min_number"=>0,
                  "input_type"=>"number",
                  "required"=>false,
                  "placeholder_clickable"=>false,
                  "placeholder"=>"ZNI Convencional",
                ])

            @include("partials.v2.form.form_input_icon",[
                  "input_model"=>"zni_rural",
                  "input_label"=>"ZNI rural",
                  "updated_input"=>"defer",
                  "icon_class"=>"fa-solid fa-circle-dollar-to-slot",
                  "col_with"=>12,
                  "min_number"=>0,
                  "input_type"=>"number",
                  "required"=>false,
                  "placeholder_clickable"=>false,
                  "placeholder"=>"ZNI rural",
                ])

            @include("partials.v2.form.form_input_icon",[
                  "input_model"=>"sin_conventional",
                  "input_label"=>"SIN Convencional",
                  "updated_input"=>"defer",
                  "icon_class"=>"fa-solid fa-circle-dollar-to-slot",
                  "col_with"=>12,
                  "min_number"=>0,
                  "input_type"=>"number",
                  "required"=>false,
                  "placeholder_clickable"=>false,
                  "placeholder"=>"SIN Convencional",
                ])

            @include("partials.v2.form.form_input_icon",[
                  "input_model"=>"monitoring",
                  "input_label"=>"Monitoreo",
                  "updated_input"=>"defer",
                  "icon_class"=>"fa-solid fa-circle-dollar-to-slot",
                  "col_with"=>12,
                  "min_number"=>0,
                  "input_type"=>"number",
                  "required"=>false,
                  "placeholder_clickable"=>false,
                  "placeholder"=>"Monitoreo"
                ])


            @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>$form_submit_action_text??"Guardar"
                          ])
        </form>
    </div>
