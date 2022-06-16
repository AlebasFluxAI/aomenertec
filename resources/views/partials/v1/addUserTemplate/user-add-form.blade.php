<div class="contenedor-grande">
    <div class="row content p-5">
        <div class="row ">
            @include("partials.v1.divider_title",[
                           "title"=>$form_title
                ]
                )
            <div class="row pl-5 pr-3">

                @include("partials.v1.form.form_input_icon",[
                      "input_model"=>"name",
                      "input_label"=>"Nombre",
                      "icon_class"=>"fas fa-user",
                      "placeholder"=>"Nombre",
                      "col_with"=>8,
                      "input_type"=>"text",
                      "required"=>true
             ])

                @include("partials.v1.form.form_input_icon",[
                       "input_label"=>"Apellido",
                       "input_model"=>"last_name",
                       "icon_class"=>"fas fa-user",
                       "placeholder"=>"Apellido",
                       "col_with"=>8,
                       "input_type"=>"text",
                       "required"=>true
                                    ])

                @include("partials.v1.form.form_input_icon",[
                        "input_label"=>"Telefono (Sin indicativo)",
                        "input_model"=>"phone",
                        "icon_class"=>"fas fa-barcode",
                        "placeholder"=>"Telefono",
                        "col_with"=>8,
                        "input_type"=>"text",
               ])


                @include("partials.v1.form.form_input_icon",[
                        "input_label"=>"Correo electronico de cliente",
                        "input_model"=>"email",
                        "icon_class"=>"fas fa-envelope",
                        "placeholder"=>"E-mail",
                        "col_with"=>8,
                        "input_type"=>"email",
               ])

                @include("partials.v1.form.form_input_icon",[
                               "input_label"=>"Numero de identificación",
                               "input_model"=>"identification",
                               "icon_class"=>"fas fa-barcode",
                               "placeholder"=>"identificación",
                               "col_with"=>8,
                               "input_type"=>"text",
                               "required"=>true
                      ])

                @include("partials.v1.divider_title",[
                          "title"=>"Ubicacion"
               ]
               )

                @include("partials.v1.addUserTemplate.user-add-location-form")

                @foreach($custom_input??[] as $input)
                    @include($input["view_name"],$input["view_values"])
                @endforeach

                @include("partials.v1.divider_title")

                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>$form_submit_action_text??"Guardar"
                          ])
            </div>
        </div>
    </div>
</div>
