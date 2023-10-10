<div>
    <div style="background-color: #f2f2f2;padding: 15px;border-radius: 15px;margin-bottom: 5%">
        @include("layouts.menu.v1.header_menu_password")
        <br>

        <div class="section-title my-5">
            <h3 class="text-center p3"><b><span class="fas fa-ticket"></span>Pago de facturas de cliente</b>
            </h3>
        </div>
        <div class="contenedor-grande">
            <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
                @include("partials.v1.divider_title",[
                                           "title"=>"¿ Conocer tu codigo de cliente ?"
                                   ]
                                  )
                @include("partials.v1.form.radio_button",[
                                      "input_label"=>"Usar codigo de cliente",
                                      "input_model"=>"has_client_code"
                                  ])
                @include("partials.v1.divider_title",[
                                             "title"=>"Buscar por identificación de cliente"
                                     ]
                                    )

                @if(!$has_client_code)
                    @include("partials.v1.form.form_input_icon",[
                                        "input_label"=>"Numero de identificación",
                                        "input_model"=>"contact_identification",
                                        "icon_class"=>"fas fa-barcode",
                                        "placeholder"=>"identificación",
                                        "col_with"=>6,
                                        "input_type"=>"text",
                                        "required"=>true
                               ])
                @else

                    @include("partials.v1.form.form_input_icon",[
                            "input_label"=>"Codigo de cliente",
                            "input_model"=>"client_code",
                            "icon_class"=>"fas fa-user",
                            "placeholder"=>"Ingresa tu codigo de cliente",
                            "col_with"=>6,
                            "input_type"=>"text",
                   ])
                @endif
                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>"Buscar facturas"
                          ])
                @error("clientError")
                <span class="error">{{ $message }}</span>
                @enderror
            </form>

        </div>
    </div>
</div>
