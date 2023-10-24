<div>
    <div class="login">

        @include("partials.v1.title",[
              "second_title"=>"pago cliente ".$model->alias ." factura ".$invoice->code,
              "first_title"=>"Registrar"
          ])

        @include("partials.v1.table_nav",
              ["mt"=>2,
              "nav_options"=>[
                         ["button_align"=>"right",
                         "click_action"=>"",
                         "button_icon"=>"fas fa-list",
                         "button_content"=>"Ver listado",
                         "target_route"=>"v1.admin.client.list.client",
                         ],
                      ]
             ])


        <div class="contenedor-grande">


            @include("partials.v1.divider_title",
                 ["title"=>"Registro de pago para factura"])

            @include("partials.v1.form.form_list",[
                      "col_with"=>6,
                      "input_label"=>"Metodo de pago",
                      "input_type"=>"text",
                      "list_model" => "payment_method",
                      "list_default" => "Metodo de pago ...",
                      "list_options" => $payment_methods,
                      "list_option_value"=>"value",
                      "list_option_view"=>"key",
                       "list_option_title"=>"",
                      ])
            @if($payment_method==\App\Models\V1\InvoicePaymentRegistration::PAYMENT_METHOD_OTHER)
                @include("partials.v1.form.form_input_icon",[
                                                    "input_model"=>"other_payment_method",
                                                    "input_label"=>"Ingrese metodo de pago",
                                                    "tooltip_title"=>"Ingrese metodo de pago",
                                                    "updated_input"=>"defer",
                                                    "icon_class"=>"fas fa-user",
                                                    "placeholder"=>"Ingrese metodo de pago",
                                                    "col_with"=>6,
                                                    "input_type"=>"text",
                                                    "required"=>true
                                           ])
            @endif
            @if($payment_method!=\App\Models\V1\InvoicePaymentRegistration::PAYMENT_METHOD_CASH)
                @include("partials.v1.form.form_input_icon",[
                                                "input_model"=>"reference",
                                                "input_label"=>"Referencia de pago",
                                                "tooltip_title"=>"Referencia de pago",
                                                "updated_input"=>"defer",
                                                "icon_class"=>"fas fa-user",
                                                "placeholder"=>"Referencia de pago...",
                                                "col_with"=>6,
                                                "input_type"=>"text",
                                                "required"=>true
                                       ])


                @include("partials.v1.form.form_input_icon",[
                                               "input_model"=>"bank",
                                               "input_label"=>"Banco o billetera virtual",
                                               "tooltip_title"=>"Banco o billetera virtual Ej: Nequi,Daviplata,Bancolombia,Davivienda, Etc...",
                                               "updated_input"=>"defer",
                                               "icon_class"=>"fas fa-user",
                                               "placeholder"=>"Banco",
                                               "col_with"=>6,
                                               "input_type"=>"text",
                                               "required"=>true
                                      ])

                @include("partials.v1.form.form_input_file",[
                  "input_type"=>"file",
                                      "input_model"=>"evidence",
                                      "icon_class"=>"fas fa-file",
                                      "placeholder"=>"Puedes cargar un soporte de pago",
                                      "col_with"=>12,
                                      "required"=>false,
                                 ])
            @endif

            @include("partials.v1.modal-confirm",
                                         [
                                             "button_content"=>"Registrar pago",
                                             "modal_content"=>"Al registrar este pago la factura pasara a estado de pago completado. ¿ Quieres continuar?",
                                             "modal_target"=>"payment_registrer",
                                             "function"=>"registerPayment",
                                         ])


        </div>
    </div>
</div>
