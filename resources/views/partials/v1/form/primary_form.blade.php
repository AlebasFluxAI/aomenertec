<div class="mb-3">
    <div class="detail-table">
        <div class="contenedor-grande">
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
                                $form_input["input_type"]=="number" || $form_input["input_type"]=="email" || $form_input["input_type"]=="password")
                                @include("partials.v1.form.form_input_icon",[
                                          "input_label"=>$form_input["input_label"]??"",
                                          "input_model"=>$form_input["input_model"],
                                          "input_field"=>$form_input["input_field"]??"",
                                          "input_type"=>$form_input["input_type"],
                                          "icon_class"=>$form_input["icon_class"]??null,
                                          "placeholder"=>$form_input["placeholder"],
                                          "col_with"=>$form_input["col_with"],
                                          "required"=>$form_input["required"],
                                          "input_rows"=>$form_input["input_rows"]??0,
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
