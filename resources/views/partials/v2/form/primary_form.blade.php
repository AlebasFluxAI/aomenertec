<div class="mb-3">
    <div class="detail-table">
        <h5>{{$form_title??''}} </h5>

        @if ($form_toast??false and session()->has($session_message))
            <div class="alert alert-success">
                {{ session($session_message) }}
            </div>
        @endif
        <div class="row content pt-6">
            <form wire:submit.prevent="{{$form_submit_action}}" class="needs-validation" role="form">
                <div class="row ">
                    @foreach($form_inputs as $form_input)
                        @if(array_key_exists("data_foreach", $form_input))
                            @foreach($form_input['data_foreach'] as $index => $data)
                                @foreach($form_input['foreach_inputs'] as $foreach_input)
                                    @if($foreach_input["input_type"]=="custom")
                                        @include($foreach_input["template_name"],$foreach_input["template_values"])
                                    @endif
                                    @if($foreach_input["input_type"]=="divider")
                                        @include("partials.v1.divider_title",[
                                             "title"=>$foreach_input["title"]
                                          ])
                                    @endif
                                    @if($foreach_input["input_type"]=="text"
                                        || $foreach_input["input_type"]=="checkbox"
                                        || $foreach_input["input_type"]=="number"
                                        || $foreach_input["input_type"]=="email"
                                        || $foreach_input["input_type"]=="password"
                                        || $foreach_input["input_type"]=="select")
                                        @include("partials.v2.form.form_input_icon",[
                                                  "input_model"=>$form_input["model_foreach"].$index.$foreach_input["input_model"],
                                                  "input_field"=>$foreach_input["input_field"]??"",
                                                  "input_type"=>$foreach_input["input_type"],
                                                  "icon_class"=>$foreach_input["icon_class"]??null,
                                                  "placeholder"=>$data->{explode(".",$foreach_input["placeholder"])[0]}->{explode(".",$foreach_input["placeholder"])[1]},
                                                  "placeholder_input"=>$foreach_input["placeholder_input"]??"",
                                                  "col_with"=>$foreach_input["col_with"],
                                                  "required"=>$foreach_input["required"],
                                                  "offset"=>$foreach_input["offset"]??'',
                                                  "data_target"=>$foreach_input["data_target"]??'',
                                                  "placeholder_clickable"=>$foreach_input["placeholder_clickable"]??false,
                                                  "input_rows"=>$foreach_input["input_rows"]??0,
                                                  "updated_input"=>$foreach_input['updated_input']??"",
                                                  "click_action" => $foreach_input['click_action']??"",
                                                  "number_min"=>$foreach_input["number_min"]??"",
                                                  "number_max"=>$foreach_input["number_max"]??"",
                                                  "number_step"=>$foreach_input["number_step"]??"",
                                                  "select_options"=>$foreach_input["select_options"]??[],
                                                  "select_option_value"=>$foreach_input["select_option_value"]??"",
                                                  "select_option_view"=>$foreach_input["select_option_view"]??"",
                                                  "select_status_input"=>$foreach_input["select_status_input"]??false,
                                                  "input_status_model"=>$foreach_input["input_status_model"]??"",
                                                  "select_option_title"=>$foreach_input["select_option_title"]??"",
                                             ])
                                    @elseif($foreach_input["input_type"]=="input_min_max")
                                        @include("partials.v2.form.form_input_max_min",[
                                                "input_min_label"=>$foreach_input["input_min_label"]??"Minimo",
                                                "input_max_label"=>$foreach_input["input_max_label"]??"Maximo",
                                                "input_min_model"=>$foreach_input["input_min_model"],
                                                "input_max_model"=>$foreach_input["input_max_model"],
                                                "input_field"=>$foreach_input["input_field"]??"",
                                                "input_type"=>$foreach_input["input_type"],
                                                "icon_class"=>$foreach_input["icon_class"]??null,
                                                "placeholder"=>$foreach_input["placeholder"],
                                                "placeholder_clickable"=>$foreach_input["placeholder_clickable"]??false,
                                                "data_target"=>$foreach_input["data_target"]??'',
                                                "col_with"=>$foreach_input["col_with"],
                                                "required"=>$foreach_input["required"],
                                                "input_rows"=>$foreach_input["input_rows"]??0,
                                                "updated_input"=>$foreach_input['updated_input']??"",
                                                "click_action" => $foreach_input['click_action']??"",
                                                "input_min_number_min"=>$foreach_input["input_min_number_min"]??"",
                                                  "input_min_number_max"=>$foreach_input["input_min_number_max"]??"",
                                                  "input_min_number_step"=>$foreach_input["input_min_number_step"]??"",
                                                "input_max_number_min"=>$foreach_input["input_max_number_min"]??"",
                                                  "input_max_number_max"=>$foreach_input["input_max_number_max"]??"",
                                                  "input_max_number_step"=>$foreach_input["input_max_number_step"]??"",
                                                  "select_status_input"=>$foreach_input["select_status_input"]??false,
                                                  "input_status_model"=>$foreach_input["input_status_model"]??"",
                                                  "select_options"=>$foreach_input["select_options"]??[],
                                                  "select_option_title"=>$foreach_input["select_option_title"]??"",
                                                  "select_option_value"=>$foreach_input["select_option_value"]??"",
                                                  "select_option_view"=>$foreach_input["select_option_view"]??"",
                                           ])
                                    @elseif($foreach_input["input_type"]=="dropdown-search")

                                        @include("partials.v1.form.form_dropdown_input_searchable",[
                                                      "icon_class"=>$foreach_input["icon_class"]??null,
                                                      "placeholder"=>$foreach_input["placeholder"],
                                                      "input_field"=>$foreach_input["input_field"]??"",
                                                      "col_with"=>$foreach_input["col_with"],
                                                      "dropdown_model"=>$foreach_input["dropdown_model"],
                                                      "dropdown_enter_function"=>$foreach_input["dropdown_enter_function"]??null,
                                                      "picked_variable"=>$foreach_input["picked_variable"],
                                                      "message_variable"=>$foreach_input["message_variable"]??"",
                                                      "dropdown_results"=>$foreach_input["dropdown_results"],
                                                      "selected_value_function"=>$foreach_input["selected_value_function"],
                                                      "dropdown_result_id"=>$foreach_input["dropdown_result_id"],
                                                      "dropdown_result_value"=>$foreach_input["dropdown_result_value"],
                                                      "required"=>$foreach_input["required"]??true,
                                                      "count_bool" => $foreach_input['count_bool']??false  ,
                                                  ])
                                    @elseif($foreach_input["input_type"]=="dropdown")

                                        @include("partials.v1.form.form_dropdown",[
                                                      "icon_class"=>$foreach_input["icon_class"]??null,
                                                      "dropdown_editing"=>$foreach_input["dropdown_editing"],
                                                      "dropdown_refresh"=>$foreach_input["dropdown_refresh"],
                                                      "placeholder"=>$foreach_input["placeholder"],
                                                      "input_field"=>$foreach_input["input_field"]??"",
                                                      "col_with"=>$foreach_input["col_with"],
                                                      "dropdown_model"=>$foreach_input["dropdown_model"],
                                                      "dropdown_values"=>$foreach_input["dropdown_values"],
                                    ])
                                    @elseif($foreach_input["input_type"]=="list")

                                        @include("partials.v1.form.form_list",[
                                                         "col_with"=>$foreach_input["col_with"],
                                                         "list_model" => $foreach_input["list_model"],
                                                         "list_default" => $foreach_input["list_default"],
                                                         "list_options" => $foreach_input["list_options"],
                                                         "list_option_value"=>$foreach_input["list_option_value"],
                                                         "list_option_view"=>$foreach_input["list_option_view"],
                                                         "list_option_title"=>$foreach_input["list_option_title"],


                                    ])

                                    @elseif($foreach_input["input_type"]=="file")

                                        @include("partials.v1.form.foreach_input_file",[
                                                      "input_model"=>$foreach_input["input_model"],
                                                      "icon_class"=>$foreach_input["icon_class"]??null,
                                                      "placeholder"=>$foreach_input["placeholder"],
                                                      "input_field"=>$foreach_input["input_field"]??"",
                                                      "col_with"=>$foreach_input["col_with"],

                                            ])
                                    @endif
                                @endforeach
                            @endforeach
                        @else
                            @if($form_input["input_type"]=="custom")
                                @include($form_input["template_name"],$form_input["template_values"])
                            @endif
                            @if($form_input["input_type"]=="divider")
                                @include("partials.v1.divider_title",[
                                     "title"=>$form_input["title"]
                                  ])
                            @endif
                            @if($form_input["input_type"]=="text"
                                || $form_input["input_type"]=="checkbox"
                                || $form_input["input_type"]=="number"
                                || $form_input["input_type"]=="email"
                                || $form_input["input_type"]=="password"
                                || $form_input["input_type"]=="select"
                                || $form_input["input_type"]=="multiselect"
                                )
                                @include("partials.v2.form.form_input_icon",[
                                          "input_model"=>$form_input["input_model"]??"",
                                          "input_field"=>$form_input["input_field"]??"",
                                          "input_type"=>$form_input["input_type"],
                                          "icon_class"=>$form_input["icon_class"]??null,
                                          "placeholder"=>$form_input["placeholder"],
                                          "placeholder_input"=>$form_input["placeholder_input"]??"",
                                          "col_with"=>$form_input["col_with"]??"",
                                          "required"=>$form_input["required"],
                                          "offset"=>$form_input["offset"]??'',
                                          "data_target"=>$form_input["data_target"]??'',
                                          "placeholder_clickable"=>$form_input["placeholder_clickable"]??false,
                                          "input_rows"=>$form_input["input_rows"]??0,
                                          "updated_input"=>$form_input['updated_input']??"",
                                          "click_action" => $form_input['click_action']??"",
                                          "number_min"=>$form_input["number_min"]??"",
                                          "number_max"=>$form_input["number_max"]??"",
                                          "number_step"=>$form_input["number_step"]??"",
                                          "select_options"=>$form_input["select_options"]??[],
                                          "select_option_value"=>$form_input["select_option_value"]??"",
                                          "select_option_view"=>$form_input["select_option_view"]??"",
                                          "options_list"=>$form_input["options_list"]??[],
                                            "model_select"=>$form_input["model_select"]??"",
                                            "name_select"=>$form_input["name_select"]??"",
                                            "option_value"=>$form_input["option_value"]??"",
                                            "option_view"=>$form_input["option_view"]??"",
                                               "select_status_input"=>$form_input["select_status_input"]??false,
                                                  "input_status_model"=>$form_input["input_status_model"]??"",
                                                  "select_option_title"=>$form_input["select_option_title"]??"",
                                     ])
                            @elseif($form_input["input_type"]=="input_min_max")
                                @include("partials.v2.form.form_input_max_min",[
                                        "input_min_label"=>$form_input["input_min_label"]??"Minimo",
                                        "input_max_label"=>$form_input["input_max_label"]??"Maximo",
                                        "input_min_model"=>$form_input["input_min_model"],
                                        "input_max_model"=>$form_input["input_max_model"],
                                        "input_field"=>$form_input["input_field"]??"",
                                        "input_type"=>$form_input["input_type"],
                                        "icon_class"=>$form_input["icon_class"]??null,
                                        "placeholder"=>$form_input["placeholder"],
                                        "placeholder_clickable"=>$form_input["placeholder_clickable"]??false,
                                        "data_target"=>$form_input["data_target"]??'',
                                        "col_with"=>$form_input["col_with"],
                                        "required"=>$form_input["required"],
                                        "input_rows"=>$form_input["input_rows"]??0,
                                        "updated_input"=>$form_input['updated_input']??"",
                                        "click_action" => $form_input['click_action']??"",
                                        "input_min_number_min"=>$form_input["input_min_number_min"]??"",
                                          "input_min_number_max"=>$form_input["input_min_number_max"]??"",
                                          "input_min_number_step"=>$form_input["input_min_number_step"]??"",
                                        "input_max_number_min"=>$form_input["input_max_number_min"]??"",
                                          "input_max_number_max"=>$form_input["input_max_number_max"]??"",
                                          "input_max_number_step"=>$form_input["input_max_number_step"]??"",
                                          "select_status_input"=>$form_input["select_status_input"]??false,
                                                  "input_status_model"=>$form_input["input_status_model"]??"",
                                                  "select_options"=>$form_input["select_options"]??[],
                                                  "select_option_title"=>$form_input["select_option_title"]??"",
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
                                              "icon_class"=>$form_input["icon_class"]??null,
                                              "dropdown_editing"=>$form_input["dropdown_editing"],
                                              "dropdown_refresh"=>$form_input["dropdown_refresh"],
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
                                                 "input_label"=> $form_input["input_label"]??""


                            ])

                            @elseif($form_input["input_type"]=="file")

                                @include("partials.v1.form.form_input_file",[
                                              "input_model"=>$form_input["input_model"],
                                              "icon_class"=>$form_input["icon_class"]??null,
                                              "placeholder"=>$form_input["placeholder"],
                                              "input_field"=>$form_input["input_field"]??"",
                                              "col_with"=>$form_input["col_with"],

                                    ])
                            @elseif($form_input["input_type"] == "multioselect")
                                @include("partials.v1.form.multiselect_dropdown",[
                                            "mt"=>$form_input["mt"]??0,
                                            "mb"=>$form_input["mb"],
                                            "col_width"=>$form_input["col_width"],
                                            "options_list"=>$form_input["options_list"],
                                            "model_select"=>$form_input["model_select"],
                                            "name_select"=>$form_input["name_select"]??"",
                                            "option_value"=>$form_input["option_value"],
                                            "option_view"=>$form_input["option_view"],
                                            "optgroup"=>$form_input["optgroup"]??false,
                                            "input_label"=> $form_input["input_label"]??""

                                   ])
                            @endif

                        @endif
                    @endforeach
                    @if($loading_state??false)
                        <div wire:loading wire:target="{{ $form_submit_action }}"
                             class="justify-content-end  mx-2 form-group mb-0 mt-0 ">

                            <span class="">Conectando...</span>
                            <div class="spinner-grow" role="status">
                            </div>
                        </div>
                    @endif
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
