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
                                           "title"=>"Buscar Peticion"
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
                      "input_label"=>"Ingrese el codigo de Pqr",
                      "input_model"=>"pqr_code",
                      "icon_class"=>"fas fa-user",
                      "placeholder"=>"Ingresa el codigo de Pqr",
                      "col_with"=>6,
                      "input_type"=>"text",
             ])


                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>"Buscar petición"
                          ])

            </form>

        </div>
    </div>
    <div class="mt-3">
        @if($pqrs)
            @include("partials.v2.table.primary-table",[
            "table_headers"=>[
             [
                "col_name" =>"ID",
                "col_data" =>"id",
                "col_filter"=>false
            ],
             [
                "col_name" =>"Codigo",
                "col_data" =>"code",
                "col_filter"=>false
            ],
            [
                "col_name" =>"Asunto",
                "col_data" =>"subject",
                "col_filter"=>false
            ],
              [
                "col_name" =>"Tipo",
                "col_data" =>"type",
                "col_filter"=>false,
                "col_translate"=>"pqr"
            ],
            [
                    "col_name" =>"Categoria",
                "col_data" =>"sub_type",
                "col_filter"=>false,
                "col_translate"=>"pqr"
            ],
             [
                 "col_name" =>"Cliente",
                "col_data" =>"client.name",
                "col_filter"=>false,

            ],
            [
                 "col_name" =>"Severidad",
                "col_data" =>"severity",
                "col_filter"=>false,
                "col_translate"=>"pqr"

            ],
            [
                 "col_name" =>"Estado",
                "col_data" =>"status",
                "col_filter"=>false,
                "col_translate"=>"pqr"

            ],

            [
                 "col_name" =>"Nivel",
                "col_data" =>"level",
                "col_filter"=>false,
                "col_translate"=>"pqr"

            ],

             ],
              "table_actions"=>[

                                 "customs"=>[
                                                 [

                                                         "redirect"=>[
                                                                 "route"=>"guest.details-pqr",
                                                                 "binding"=>"pqr"
                                                           ],
                                                         "icon"=>"fa fa-comment-dots",
                                                         "tooltip_title"=>"Responder ticket",
                                                         "conditional"=>"openTicked",
                                                         "button_subdomain"=>$subdomain
                                                 ],
                                                   [

                                                         "redirect"=>[
                                                                 "route"=>"historical.details-pqr",
                                                                 "binding"=>"pqr"
                                                           ],
                                                         "icon"=>"fa fa-list",
                                                         "tooltip_title"=>"Historial de mensajes",
                                                         "button_subdomain"=>$subdomain
                                                 ],
                                                 [

                                                         "function"=>"closePqr",
                                                         "icon"=>"fas fa-check",
                                                         "tooltip_title"=>"Cerrar ticket",
                                                         "conditional"=>"resolvedTicked"
                                                 ],
                                                 [

                                                         "function"=>"rejectPqr",
                                                         "icon"=>"fas fa-square-minus",
                                                         "tooltip_title"=>"Rechazar ticket",
                                                         "conditional"=>"resolvedTicked"
                                                 ],


                                     ]
                                 ],

                                             /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                             _edit_button=>{ruta para redireccionar a edicion}
                                             _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                               ]*/
            "table_rows"=>$pqrs



        ])

        @endif
    </div>
</div>
