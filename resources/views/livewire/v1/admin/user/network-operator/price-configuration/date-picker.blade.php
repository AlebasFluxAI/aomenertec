@include("partials.v1.divider_title",[
    "title"=>"Selector de fecha"
])
<p> Selecciona el mes y año para la aplicacion de las tarifas (Debes seleccionar el mes para visualizar las tarifas)
</p>
<br>
<div>
    @include("partials.v1.form.form_input_icon",
   ["input_model"=>"month",
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

    @include("partials.v1.form.form_input_icon",
 ["input_model"=>"year",
 "updated_input"=>"defer",
 "input_field"=>"",
 "input_label"=>"Seleccione un año para aplicar la tarifa",
 "input_type"=>"select",
 "icon_class"=>null,
 "placeholder"=>"Mes de tarifa",
 "col_with"=>3,
 "required"=>true,
 "offset"=>'',
 "data_target"=>'',
 "placeholder_clickable"=>false,
 "input_rows"=>0,
 "select_options"=>$years,
 "select_option_value"=>"value",
 "select_option_view"=>"key",
 ])
    @error('date_picker_error') <span class="error">{{ $message }}</span> @enderror

    @include("partials.v1.form.form_submit_button",[
                          "button_align"=>"left" ,
                          "function"=>"pickDate",
                          "button_content"=>"Seleccionar mes"
              ])
</div>
@include("partials.v1.divider_title")
<div wire:loading wire:target="year,month" class="text-center" style="margin:0 auto">
    <div class="text-center loader"></div>

</div>
