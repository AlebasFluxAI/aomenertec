<div class="col-12 text-left"> &nbsp;&nbsp; <strong> Seriales de componentes</strong></div>
@foreach($equipment as $index => $item)
    <div wire:key="equipment-field-{{ $index }}" class="form-group mb-2 align-content-start col-md-3 col-sm-12">
        @include("partials.v1.form.form_list",[
     "col_with"=>4,
     "mb"=>0,
     "disabled" => $item['disable'],
     "aux_class"=>"no-border",
     "list_model" => "equipment.".$index.".type_id",
     "list_default" => "Seleccione equipo...",
     "list_options" => $equipment_types,
     "list_option_value"=>"id",
     "list_option_view"=>"type",
     "list_option_title"=>""
])
        @include("partials.v1.form.form_dropdown_input_searchable",[
      "form_group" => false,
      "dropdown_model" => "equipment.".$index.".serial",
      "placeholder" => $item['type'],
      "required" => true,
      "picked_variable" => $item['picked'],
      "message_variable" => $item['post'],
      "variable_2" => $index??0,
      "dropdown_results" => $serials,
      "count_bool" => $serials->contains('equipment_type_id', $item['type_id']),
      "selected_value_function" => "assignEquipment",
      "dropdown_result_id" => "id",
      "dropdown_result_value" => "serial",
])

    </div>
@endforeach
<div class="d-flex align-items-center col-md-2 col-sm-12">
    @include("partials.v1.primary_button",[
                                 "button_align"=>"right" ,
                                 "click_action" => "addInputEquipment()",
                                 "button_content"=>"",
                                 "button_icon" => "fas fa-plus",
                                 "class_button"=>"b-success"

                     ])
    @include("partials.v1.primary_button",[
                                 "button_align"=>"right" ,
                                 "click_action" => "deleteInputEquipment()",
                                 "button_content"=>"",
                                 "button_icon" => "fas fa-minus",
                                 "class_button"=>"b-danger"
                     ])
</div>
