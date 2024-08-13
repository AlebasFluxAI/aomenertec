<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Cargar",
            "second_title"=>"Firmware"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.superadmin.firmware.listado",
                    ],

                ]
        ])


            <div class="contenedor-grande">
                @include("partials.v1.form.primary_form",[
                             "class_container"=>"",
                             "form_toast"=>false,
                             "session_message"=>"message",
                             "form_submit_action"=>"submitForm",
                             "form_submit_action_text"=>"Enviar firmware",
                             "form_inputs"=>[
                                            [
                                                 "input_type"=>"dropdown-search",
                                                 "icon_class" => "fas fa-user",
                                                 "dropdown_model" => "meter",
                                                 "placeholder" => "Medidor",
                                                 'col_with'=>12,
                                                 "required" => true,
                                                 "picked_variable" => $meter_picked,
                                                 "message_variable" => $message_meter,
                                                 "dropdown_results" => $meters,
                                                 "selected_value_function" => "assignMeter",
                                                 "dropdown_result_id" => "serial",
                                                 "dropdown_result_value" => "name",
                                                 "count_bool" => (count($meters)>0),

                             ]

                          ]
                  ])
                @if ($progress >= 0)
                    <div >
                        <p>Progreso de la carga: {{ $progress }}%</p>
                        <progress value="{{ $progress }}" max="100">{{ $progress }}%</progress>
                    </div>
                @endif

            </div>


</div>
