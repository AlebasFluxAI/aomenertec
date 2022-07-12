<div>
    <div style="background-color: #f2f2f2;padding: 15px;border-radius: 15px">


        @include("layouts.menu.v1.header_menu_password")

        <div class="section-title my-5">
            <h3 class="text-center p3"><b><span class="fas fa-ticket"></span> Seguimiento de peticiones para
                    clientes</b>
            </h3>
        </div>
        <div class="contenedor-grande">
            <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
                @include("partials.v1.divider_title",[
                                           "title"=>"codigo de PQR"
                                   ]
                                  )


                @include("partials.v1.form.form_input_icon",[
                        "input_label"=>"Ingrese el codigo de cliente",
                        "input_model"=>"client_code",
                        "icon_class"=>"fas fa-user",
                        "placeholder"=>"Ingresa tu codigo de cliente",
                        "col_with"=>6,
                        "input_type"=>"text",
               ])

                @include("partials.v1.form.form_input_icon",[
                      "input_label"=>"Ingrese el codigoas de Pqr",
                      "input_model"=>"pqr_code",
                      "icon_class"=>"fas fa-user",
                      "placeholder"=>"Ingresa el codigo de Pqr",
                      "col_with"=>6,
                      "input_type"=>"text",
             ])


                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>"Enviar petición"
                          ])

            </form>

        </div>
    </div>
</div>
