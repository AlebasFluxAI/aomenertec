<div class="row pl-5 pr-3">

    @include("partials.v1.map",[
             "input_label"=>"Seleccione la ubicacion del cliente en el mapa o ingrese una direccion"
       ])
    @include("partials.v1.form.form_input_icon",[
                                   "input_label"=>"Detalles de dirección (Ingrese los detalles que puedan ser relevantes de la direccion)",
                                   "input_model"=>"address_details",
                                   "updated_input"=>"defer",
                                   "icon_class"=>"fas fa-map",
                                   "placeholder"=>"Vereda, Caserio, Resguardo .. etc",
                                   "col_with"=>8,
                                   "input_type"=>"text",
                                   "required"=>false
                             ])
</div>
