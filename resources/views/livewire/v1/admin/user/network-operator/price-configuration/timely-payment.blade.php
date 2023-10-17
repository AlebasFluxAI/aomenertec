@include("partials.v1.divider_title",[
    "title"=>"Selector de fecha"
])


<div>
    @include("partials.v1.form.form_input_icon",[
                 "input_model"=>"deadline_days",
                 "input_label"=>"Dias de para pago oportuno despues de fecha de corte",
                  "updated_input"=>"defer",
                 "icon_class"=>"fas fa-calendar",
                 "placeholder"=>"Ingrese dias para pago oportuno",
                 "col_with"=>6,
                 "min_number"=>1,
                 "max_number"=>31,
                 "input_type"=>"number",
                 "required"=>true,
        ])


    @include("partials.v1.form.form_input_icon",[
                "input_model"=>"deadline_days",
                "input_label"=>"Numero de dias de para desconexion",
                 "updated_input"=>"defer",
                "icon_class"=>"fas fa-calendar",
                "placeholder"=>"Ingrese dias para desconexion",
                "col_with"=>6,
                "min_number"=>1,
                "max_number"=>31,
                "input_type"=>"number",
                "required"=>true,
       ])

    @include("partials.v1.form.form_input_icon",[
            "input_model"=>"min_client_value",
            "input_label"=>"Costo de reconexion",
             "updated_input"=>"defer",
            "icon_class"=>"fas fa-money-bill",
            "placeholder"=>"Ingrese el costo de reconexion",
            "col_with"=>6,
            "min_number"=>0,
            "input_type"=>"number",
            "required"=>true,
   ])


    @include("partials.v1.form.form_submit_button",[
                              "button_align"=>"right" ,
                              "button_content"=>$form_submit_action_text??"Guardar"
                  ])
</div>
