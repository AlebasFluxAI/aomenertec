<div class="login">
    @include("partials.v1.divider_title",["title"=>"Ajuste de cobro mensual"])
    <form wire:submit.prevent="submitAnnuallyForm" id="formulario" class="needs-validation" role="form">

        @include("partials.v1.form.form_input_icon",
         ["input_model"=>"annually_client_invoicing_month",
         "updated_input"=>"defer",
         "input_field"=>"",
         "input_label"=>"Seleccione el mes para aplicar la tarifa",
         "input_type"=>"select",
         "icon_class"=>null,
         "placeholder"=>"Mes de tarifa",
         "col_with"=>3,
         "required"=>true,
         "offset"=>'',
         "data_target"=>'',
         "placeholder_clickable"=>false,
         "input_rows"=>0,
         "select_options"=>$months,
         "select_option_value"=>"value",
         "select_option_view"=>"key",
         ])
        @include("partials.v1.form.form_input_icon",[
                   "input_model"=>"annually_client_cost",
                   "input_label"=>"Costo por cliente activo",
                    "updated_input"=>"defer",
                   "icon_class"=>"fas fa-money-bill",
                   "placeholder"=>"Ingrese el costo por cliente activo",
                   "col_with"=>6,
                   "min_number"=>0,
                   "input_type"=>"number",
                   "required"=>true,
          ])
        @include("partials.v1.form.form_submit_button",[
                                       "button_align"=>"right" ,
                                       "button_content"=>$form_submit_action_text??"Guardar"
                           ])
    </form>
</div>
