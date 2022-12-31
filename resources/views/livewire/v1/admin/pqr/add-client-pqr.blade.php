<div class="login">
    @include("partials.v1.title",[
      "second_title"=>"PQR ".$model->code,
      "first_title"=>"Asociar cliente a",
  ])


    <div class="contenedor-grande">
        <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
            @include("partials.v1.divider_title",[
                                       "title"=>"Seleccione el cliente a vincular"
                               ]
                              )


            @include("partials.v1.form.form_dropdown",[
                                      "input_label"=>"Seleccione el cliente",
                                            "input_type"=>"dropdown",
                                            "icon_class"=>"fas fa-table-list",
                                            "placeholder"=>"Seleccione el cliente",
                                            "col_with"=>6,
                                            "dropdown_model"=>"client_id",
                                            "dropdown_values"=>$clients,
                                            "dropdown_editing"=>false,
                              ])


            @include("partials.v1.form.form_submit_button",[
                                  "button_align"=>"right" ,
                                  "button_content"=>"Enviar petición"
                      ])

        </form>

    </div>
</div>
</div>
