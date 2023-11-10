<div>
    <div class="row pt-3">
        @include("partials.v1.divider_title",["title"=>"Generador de reportes"])


        @include("partials.v1.form.multiselect_dropdown",[
                        "mt"=>0,
                        "mb"=>2,
                        "col_width"=>3,
                        "options_list"=>$variables,
                        "model_select"=>"variables_selected",
                        "name_select"=>"select_report",
                        "option_value"=>"id",
                        "option_view"=>"display_name",
                        "optgroup"=>true,

               ])

        @include("partials.v1.form.form_list",[
                                                 "col_with" =>3,
                                                 "mt"=>0,
                                                 "mb"=>2,
                                                 "input_type"=>"text",
                                                 "list_model" => "time_report_id",
                                                 "list_default" => "Muestreo...",
                                                 "list_options" => [
                                                                    ['id'=>1, 'display_name'=> 'Minuto'],
                                                                    ['id'=>2, 'display_name'=> 'Hora'],
                                                                    ['id'=>3, 'display_name'=> 'Dia'],
                                                                    ['id'=>4, 'display_name'=> 'Mes'],
                                                                   ],
                                                 "list_option_value"=>"id",
                                                 "list_option_view"=>"display_name",
                                                 "list_option_title"=>"",
                                        ])



        @include("partials.v1.form.form_input_icon",[
                        "mt"=>0,
                        "input_model"=>"date_range_report",
                        "icon_class"=>"fas fa-calendar",
                       "updated_input"=>"defer",
                        "placeholder"=>"Seleccione rango de fechas",
                        "col_with"=>6,
                        "input_type"=>"text",
                        "input_name"=>"datetime_report",
                        "autocomplete"=> "off",
               ])
        <div class="mt-4">
            @include("partials.v1.primary_button",[
                                "col_with" => 'auto',
                                "button_align" => 'right',
                                "click_action" => 'reportCsv',
                                "class_button" => 'success',
                                "button_icon" => 'fas fa-file-excel',
                                "button_content" => 'Exportar XLSX',

                        ])
            @include("partials.v1.primary_button",[
                            "col_with" => 'auto',
                            "button_align" => 'center',
                            "click_action" => 'reportPdf',
                            "class_button" => 'danger',
                            "button_icon" => 'fas fa-file-pdf',
                            "button_content" => 'Exportar PDF',

                    ])
            <div wire:loading wire:target="reportCsv">
                <label>Generando archivo excel...</label>
            </div>
            <div wire:loading wire:target="reportPdf">
                <label>Generando archivo PDF...</label>
            </div>

        </div>
    </div>

    @include("partials.v1.divider_title",["title"=>"Simulador de tarifa"])
    <form wire:submit.prevent="simulateFee" id="formulario-simulateFee" class="needs-validation" role="form">
        {{--
        <div> &nbsp;&nbsp; <strong> Agregar manualmente</strong></div>
        --}}
        <div class="row ">
            @include("partials.v1.form.form_input_icon",[
                        "mt"=>0,
                        "tooltip_title"=>"El simulador de tarifa le dara un costo aproximado dentro de un periodo de consumo en base a
                        un costo de Kw/h",
                        "input_model"=>"date_range_simulator",
                        "icon_class"=>"fas fa-calendar",
                        "updated_input"=>"defer",
                        "input_label"=>"Seleccione rango de fechas",
                        "col_with"=>6,
                        "input_type"=>"text",
                        "input_name"=>"datetime_simulator",
                        "autocomplete"=> "off",
            ])

            @include("partials.v1.form.form_input_icon",[
                        "input_model"=>"kwh_cost",
                        "updated_input"=> "defer",
                        "input_label"=>"Costo del Kw/h",
                        "icon_class"=>"fas fa-money-bill",
                        "placeholder"=>"Ingrese costo de Kw/h",
                        "col_with"=>6,
                        "input_type"=>"number",
                        "required"=>false
            ])
            @include("partials.v1.form.form_submit_button",[
            "button_align"=>"right" ,
            "function"=>"simulateFee",
            "button_content"=>"Simular tarifa"
            ])
            <div wire:loading>
                <div class="clock-loader"></div>
            </div>
            @if($total_simulation==null)
            @else
                <div class="col-4 bg-secondary text-center" style="border-radius: 15px;padding: 20px;margin: auto">
                    <p><b>Resultado:</b></p>
                    <hr>
                    s
                    <div class="row bg-gradient-gray m-1">
                        <div class="col-md-5">
                            <p style="text-align: left;margin-top: 5px"><b>Fecha inicial</b></p>
                        </div>
                        <div class="col-md-7">
                            <p style="text-align: right;margin-top: 5px"><b>{{$start_simululator}}</b></p>
                        </div>
                    </div>
                    <div class="row bg-gradient-gray m-1">
                        <div class="col-md-5">
                            <p style="text-align: left;margin-top: 5px"><b>Fecha Final</b></p>
                        </div>
                        <div class="col-md-7">
                            <p style="text-align: right;margin-top: 5px"><b>{{$end_simululator}}</b></p>
                        </div>
                    </div>
                    <div class="row bg-gradient-gray m-1">
                        <div class="col-md-5">
                            <p style="text-align: left;margin-top: 5px"><b>Kw/h Consumidos</b></p>
                        </div>
                        <div class="col-md-7">
                            <p style="text-align: right;margin-top: 5px">
                                <b>{{\App\Http\Resources\V1\Formatter::numberFormat($total_consumption,3)}}</b></p>
                        </div>
                    </div>
                    <div class="row bg-gradient-gray m-1">
                        <div class="col-md-6">
                            <p style="text-align: left;margin-top: 5px"><b>Costo Kw/h</b></p>
                        </div>
                        <div class="col-md-6">
                            <p style="text-align: right;margin-top: 5px"><b>{{$kwh_cost}}</b></p>
                        </div>
                    </div>
                    <br>
                    <p style="text-align: left;margin-top: 10px"><b>Tarifa calculada:</b></p>
                    <hr>
                    <p style="text-align: right">
                        <b>{{\App\Http\Resources\V1\Formatter::currencyFormat($total_simulation)}}</b></p>
                </div>
        </div>
    </form>
    @endif
    @include("partials.v1.divider_title",["title"=>"Tarifa para cliente monitoreo"])
    <form wire:submit.prevent="submitMonitoringFeeForm" id="submitMonitoringFeeForm" class="needs-validation"
          role="form">
        @include("partials.v1.form.form_input_icon",[
         "input_model"=>"monitoring_fee",
         "input_label"=>"Tarifa para cliente monitoreo",
          "updated_input"=>"defer",
         "icon_class"=>"fas fa-money-bill",
         "placeholder"=>"Ingrese tarifa para cliente monitoreo",
         "col_with"=>6,
         "min_number"=>0,
         "input_type"=>"number",
         "required"=>true,
])

        @include("partials.v1.form.form_submit_button",[
                                  "button_align"=>"right" ,
                                  "function"=>"submitMonitoringFeeForm",
                                  "button_content"=>"Guardar tarifa"
                      ])
    </form>
    <script>

        document.addEventListener('livewire:load', function () {
            $(function () {
                $('input[name="datetime_report"]').daterangepicker({
                    applyButtonClasses: 'text-primary',
                    timePicker: false,
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY-MM-DD',
                        cancelLabel: 'Clear'
                    }
                });

            });

            $('input[name="datetime_report"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            @this.emit('dateRangeReport', picker.startDate.format('YYYY-MM-DD 00:00:00'), picker.endDate.format('YYYY-MM-DD 23:59:59'))
            });
            $('input[name="datetime_report"]').on('cancel.daterangepicker', function (ev, picker) {
            @this.emit('dateRangeReport', '', '')
                $(this).val('');
            })


            $(function () {
                $('input[name="datetime_simulator"]').daterangepicker({
                    applyButtonClasses: 'text-primary',
                    timePicker: false,
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY-MM-DD',
                        cancelLabel: 'Clear'
                    }
                });

            });

            $('input[name="datetime_simulator"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            @this.emit('dateRangeSimulator', picker.startDate.format('YYYY-MM-DD 00:00:00'), picker.endDate.format('YYYY-MM-DD 23:59:59'))
            });
            $('input[name="datetime_simulator"]').on('cancel.daterangepicker', function (ev, picker) {
            @this.emit('dateRangeSimulator', '', '')
                $(this).val('');
            })


        })

    </script>
</div>
