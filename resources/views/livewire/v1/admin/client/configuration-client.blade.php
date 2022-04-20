@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Configurar",
            "second_title"=>"equipo de cliente"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Listado de clientes",
                    "target_route"=>"v1.admin.client.list.client",
                    ],

                ]
        ])

    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Configuraciones",

                                                ],

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v2.form.primary_form",
                                                    "view_values"=>  [
                                                                        "form_toast"=>true,
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitForm",
                                                                        "form_title"=>"Ajuste las configuraciones para el equipo del cliente",
                                                                        "form_inputs"=>[
                                                                                        [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"ssid",
                                                                                                    "icon_class"=>"fas fa-barcode",
                                                                                                    "placeholder"=>"Red Wifi",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>true
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"wifi_password",
                                                                                                    "icon_class"=>"fas fa-barcode",
                                                                                                    "placeholder"=>"Contraseña WiFi",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>true
                                                                                        ],
                                                                                        [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"measure_type",
                                                                                                    "icon_class"=>"fas fa-barcode",
                                                                                                    "placeholder"=>"Tipo de medida",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                            [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"mqtt_host",
                                                                                                    "icon_class"=>"fas fa-barcode",
                                                                                                    "placeholder"=>"Servidor MQTT",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                            [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"mqtt_port",
                                                                                                    "icon_class"=>"fas fa-barcode",
                                                                                                    "placeholder"=>"Puerto MQTT",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"mqtt_password",
                                                                                                    "icon_class"=>"fas fa-barcode",
                                                                                                    "placeholder"=>"Contraseña MQTT",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"text",
                                                                                                    "input_model"=>"mqtt_user",
                                                                                                    "icon_class"=>"fas fa-barcode",
                                                                                                    "placeholder"=>"Usuario MQTT",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"checkbox",
                                                                                                    "input_model"=>"real_time_flag",
                                                                                                    "icon_class"=>"fas fa-barcode",
                                                                                                    "placeholder"=>"Bandera de tiempo real",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                              [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"real_time_latency",
                                                                                                    "placeholder"=>"Tiempo de muestreo en tiempo real",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"storage_latency",
                                                                                                    "placeholder"=>"Tiempo de muestreo monitoreo normal",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"reading_latency",
                                                                                                    "placeholder"=>"Tiempo de muestreo de lecturas   para promedios",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_adc_1",

                                                                                                    "placeholder"=>"Maximo de lectura voltaje dc ADC 1",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_adc_1",

                                                                                                    "placeholder"=>"Minimo de lectura voltaje dc ADC 1",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_adc_2",
                                                                                                    "placeholder"=>"Maximo de lectura voltaje dc ADC 2",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_adc_2",
                                                                                                    "placeholder"=>"Minimo de lectura voltaje dc ADC 2",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_vol_ph_1",

                                                                                                    "placeholder"=>"Maximo de voltaje de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_vol_ph_1",

                                                                                                    "placeholder"=>"Minimo de voltaje de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_vol_ph_2",

                                                                                                    "placeholder"=>"Maximo de voltaje de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_vol_ph_2",

                                                                                                    "placeholder"=>"Minimo de voltaje de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_vol_ph_3",

                                                                                                    "placeholder"=>"Maximo de voltaje de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_vol_ph_3",

                                                                                                    "placeholder"=>"Minimo de voltaje de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_current_ph_1",

                                                                                                    "placeholder"=>"Maximo de corriente de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_current_ph_1",

                                                                                                    "placeholder"=>"Minimo de corriente de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_current_ph_2",

                                                                                                    "placeholder"=>"Maximo de corriente de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_current_ph_2",

                                                                                                    "placeholder"=>"Minimo de corriente de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_current_ph_3",

                                                                                                    "placeholder"=>"Maximo de corriente de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_current_ph_3",

                                                                                                    "placeholder"=>"Minimo de corriente de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_power_ph_1",

                                                                                                    "placeholder"=>"Maximo de potencia de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_power_ph_1",

                                                                                                    "placeholder"=>"Minimo de potencia de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_power_ph_2",

                                                                                                    "placeholder"=>"Maximo de potencia de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ]
                                                                                         ,
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_power_ph_2",

                                                                                                    "placeholder"=>"Minimo de potencia de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_power_ph_3",

                                                                                                    "placeholder"=>"Maximo de potencia de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_power_ph_3",

                                                                                                    "placeholder"=>"Minimo de potencia de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_va_ph_1",

                                                                                                    "placeholder"=>"Maximo de voltio amperio de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_va_ph_1",

                                                                                                    "placeholder"=>"Minimo de voltio amperio de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_va_ph_2",

                                                                                                    "placeholder"=>"Maximo de voltio amperio de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_va_ph_2",

                                                                                                    "placeholder"=>"Minimo de voltio amperio de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_va_ph_3",

                                                                                                    "placeholder"=>"Maximo de voltio amperio de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_va_ph_3",

                                                                                                    "placeholder"=>"Minimo de voltio amperio de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_var_ph_1",

                                                                                                    "placeholder"=>"Maximo de voltio amperio reactivo de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_var_ph_1",

                                                                                                    "placeholder"=>"Minimo de voltio amperio reactivo de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_var_ph_2",

                                                                                                    "placeholder"=>"Maximo de voltio amperio reactivo de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_var_ph_2",

                                                                                                    "placeholder"=>"Minimo de voltio amperio reactivo de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_var_ph_3",

                                                                                                    "placeholder"=>"Maximo de voltio amperio reactivo de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_var_ph_3",

                                                                                                    "placeholder"=>"Minimo de voltio amperio reactivo de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_pfp_ph_1",

                                                                                                    "placeholder"=>"Maximo de factor de potencia de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_pfp_ph_1",

                                                                                                    "placeholder"=>"Minimo de factor de potencia de la fase 1 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_pfp_ph_2",

                                                                                                    "placeholder"=>"Maximo de factor de potencia de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_pfp_ph_2",

                                                                                                    "placeholder"=>"Minimo de factor de potencia de la fase 2 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_pfp_ph_3",

                                                                                                    "placeholder"=>"Maximo de factor de potencia de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_pfp_ph_3",

                                                                                                    "placeholder"=>"Minimo de factor de potencia de la fase 3 a neutro ",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_freq",

                                                                                                    "placeholder"=>"Frecuencia minima",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_freq",
                                                                                                    "placeholder"=>"Frecuencia maxima",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"flag_wh_import",
                                                                                                    "placeholder"=>"Valor alertable de energia consumida",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"flag_wh_export",
                                                                                                    "placeholder"=>"Valor alertable de energia exportada",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"flag_wh_import_varh",
                                                                                                    "placeholder"=>"Valor alertable de energia reactiva consumida",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"flag_wh_export_varh",
                                                                                                    "placeholder"=>"Valor alertable de energia reactiva exportada",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_volt_1_2",
                                                                                                    "placeholder"=>"Maximo voltaje entre fases 1 y 2",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_volt_1_2",
                                                                                                    "placeholder"=>"Minimo voltaje entre fases 1 y 2",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_volt_3_1",
                                                                                                    "placeholder"=>"Maximo voltaje entre fases 3 y 1",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_volt_3_1",
                                                                                                    "placeholder"=>"Minimo voltaje entre fases 3 y 1",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_volt_2_3",
                                                                                                    "placeholder"=>"Maximo voltaje entre fases 2 y 3",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_volt_2_3",
                                                                                                    "placeholder"=>"Minimo voltaje entre fases 2 y 3",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_vthd_ph_1",
                                                                                                    "placeholder"=>"Maximo THD de voltaje en fase 1",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_vthd_ph_1",
                                                                                                    "placeholder"=>"Minimo THD de voltaje en fase 1",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_vthd_ph_2",
                                                                                                    "placeholder"=>"Maximo THD de voltaje en fase 2",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_vthd_ph_2",
                                                                                                    "placeholder"=>"Minimo THD de voltaje en fase 2",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_vthd_ph_3",
                                                                                                    "placeholder"=>"Maximo THD de voltaje en fase 3",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_vthd_ph_3",
                                                                                                    "placeholder"=>"Minimo THD de voltaje en fase 3",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_cthd_ph_1",
                                                                                                    "placeholder"=>"Maximo THD de corriente en fase 1",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_cthd_ph_1",
                                                                                                    "placeholder"=>"Minimo THD de corriente en fase 1",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_cthd_ph_2",
                                                                                                    "placeholder"=>"Maximo THD de corriente en fase 2",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_cthd_ph_2",
                                                                                                    "placeholder"=>"Minimo THD de corriente en fase 2",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_cthd_ph_3",
                                                                                                    "placeholder"=>"Maximo THD de corriente en fase 3",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_cthd_ph_3",
                                                                                                    "placeholder"=>"Minimo THD de corriente en fase 3",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_vthd_ph_1_2",
                                                                                                    "placeholder"=>"Maximo THD de voltaje entre fase 1 y 2",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_vthd_ph_1_2",
                                                                                                    "placeholder"=>"Minimo THD de voltaje entre fase 1 y 2",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_vthd_ph_2_3",
                                                                                                    "placeholder"=>"Maximo THD de voltaje entre fase 2 y 3",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_vthd_ph_2_3",
                                                                                                    "placeholder"=>"Minimo THD de voltaje entre fase 2 y 3",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"max_vthd_ph_3_1",
                                                                                                    "placeholder"=>"Maximo THD de voltaje entre fase 3 y 1",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],
                                                                                         [
                                                                                                    "input_type"=>"number",
                                                                                                    "input_model"=>"min_vthd_ph_3_1",
                                                                                                    "placeholder"=>"Minimo THD de voltaje entre fase 3 y 1",
                                                                                                    "col_with"=>8,
                                                                                                    "required"=>false,

                                                                                         ],





                                                                                     ]
                                                            ]
                                                ],

                                          ]
         ])


</div>
