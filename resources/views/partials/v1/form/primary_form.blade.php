<div class="mb-3 flex justify-center">
    <div class="detail-table col-md-12 col-sm-12">
        <div class="{{ $class_container??'contenedor-grande' }}">
            @if ($form_toast??false and session()->has($session_message))
                <div class="alert alert-success">
                    {{ session($session_message) }}
                </div>
            @endif
            <div class="row content pt-6">
                <form wire:submit.prevent="{{$form_submit_action}}" class="needs-validation" role="form">
                    <div class="row ">
                        @foreach($form_inputs as $form_input)
                            @if($form_input["input_type"]=="text" ||
                                $form_input["input_type"]=="checkbox"||
                                $form_input["input_type"]=="number" ||
                                $form_input["input_type"]=="email" ||
                                $form_input["input_type"]=="password" ||
                                $form_input["input_type"]=="select"
                                )
                                @include("partials.v1.form.form_input_icon",[
                                          "input_label"=>$form_input["input_label"]??"",
                                          "updated_input"=>$form_input["updated_input"]??"",
                                          "input_model"=>$form_input["input_model"],
                                          "input_field"=>$form_input["input_field"]??"",
                                          "input_type"=>$form_input["input_type"],
                                          "icon_class"=>$form_input["icon_class"]??null,
                                          "placeholder"=>$form_input["placeholder"],
                                          "col_with"=>$form_input["col_with"],
                                          "required"=>$form_input["required"],
                                          "input_rows"=>$form_input["input_rows"]??0,
                                          "number_min"=>$form_input["number_min"]??"",
                                          "number_max"=>$form_input["number_max"]??"",
                                          "number_step"=>$form_input["number_step"]??"",
                                          "select_options"=>$form_input["select_options"]??[],
                                              "select_option_value"=>$form_input["select_option_value"]??"",
                                              "select_option_view"=>$form_input["select_option_view"]??"",
                                     ])

                            @elseif($form_input["input_type"]=="dropdown-search")

                                @include("partials.v1.form.form_dropdown_input_searchable",[
                                              "icon_class"=>$form_input["icon_class"]??null,
                                              "placeholder"=>$form_input["placeholder"],
                                              "input_field"=>$form_input["input_field"]??"",
                                              "col_with"=>$form_input["col_with"],
                                              "dropdown_model"=>$form_input["dropdown_model"],
                                              "dropdown_enter_function"=>$form_input["dropdown_enter_function"]??null,
                                              "picked_variable"=>$form_input["picked_variable"],
                                              "message_variable"=>$form_input["message_variable"]??"",
                                              "dropdown_results"=>$form_input["dropdown_results"],
                                              "selected_value_function"=>$form_input["selected_value_function"],
                                              "dropdown_result_id"=>$form_input["dropdown_result_id"],
                                              "dropdown_result_value"=>$form_input["dropdown_result_value"],
                                              "required"=>$form_input["required"]??true,
                                              "count_bool" => $form_input['count_bool']??false  ,
                                          ])
                            @elseif($form_input["input_type"]=="dropdown")

                                @include("partials.v1.form.form_dropdown",[
                                              "input_label"=>$form_input["input_label"]??null,
                                              "icon_class"=>$form_input["icon_class"]??null,
                                              "dropdown_editing"=>$form_input["dropdown_editing"],
                                              "dropdown_refresh"=>$form_input["dropdown_refresh"]??null,
                                              "placeholder"=>$form_input["placeholder"],
                                              "input_field"=>$form_input["input_field"]??"",
                                              "col_with"=>$form_input["col_with"],
                                              "dropdown_model"=>$form_input["dropdown_model"],
                                              "dropdown_values"=>$form_input["dropdown_values"],
                            ])
                            @elseif($form_input["input_type"]=="list")

                                @include("partials.v1.form.form_list",[
                                                 "col_with"=>$form_input["col_with"],
                                                 "input_label"=>$form_input["input_label"],
                                                 "list_model" => $form_input["list_model"],
                                                 "list_default" => $form_input["list_default"],
                                                 "list_options" => $form_input["list_options"],
                                                 "list_option_value"=>$form_input["list_option_value"],
                                                 "list_option_view"=>$form_input["list_option_view"],
                                                 "list_option_title"=>$form_input["list_option_title"],


                            ])

                            @elseif($form_input["input_type"]=="file")

                                @include("partials.v1.form.form_input_file",[
                                              "input_model"=>$form_input["input_model"],
                                              "icon_class"=>$form_input["icon_class"]??null,
                                              "placeholder"=>$form_input["placeholder"],
                                              "input_field"=>$form_input["input_field"]??"",
                                              "col_with"=>$form_input["col_with"],

                                    ])
                            @elseif($form_input["input_type"] == "multiselect")
                                @include("partials.v1.form.multiselect_dropdown",[
                                            "mt"=>$form_input["mt"]??0,
                                            "mb"=>$form_input["mb"],
                                            "col_width"=>$form_input["col_width"],
                                            "options_list"=>$form_input["options_list"],
                                            "model_select"=>$form_input["model_select"],
                                            "name_select"=>$form_input["name_select"]??"",
                                            "option_value"=>$form_input["option_value"],
                                            "option_view"=>$form_input["option_view"],
                                            "tooltip_title"=>$form_input["tooltip_title"]??null,
                                            "tooltip_position"=>$form_input["tooltip_position"]??"top",
                                            "optgroup"=>$form_input["optgroup"]??false,
                                            "input_label"=> $form_input["input_label"]??""

                                   ])
                            @endif

                        @endforeach
                        @include("partials.v1.form.form_submit_button",[
                                                 "button_align"=>"right" ,
                                                 "button_content"=>$form_submit_action_text??"Guardar"
                                     ])
                    </div>
                </form>
            </div>
            <div class="mb-3">

            </div>
        </div>
    </div>
</div>
