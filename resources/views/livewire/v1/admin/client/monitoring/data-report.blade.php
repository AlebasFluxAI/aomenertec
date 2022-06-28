<div>
    <div  class="row pt-3">


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
            <div class="d-flex justify-content-center mt-4">
        @include("partials.v1.primary_button",[
                            "col_with" => 'auto',
                            "button_align" => 'center',
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
                <div wire:loading wire:target="reportCsv" >
                    <label>Generando archivo excel...</label>
                </div>
                <div wire:loading wire:target="reportPdf" >
                    <label>Generando archivo PDF...</label>
                </div>

            </div>
    </div>

    <script>

        document.addEventListener('livewire:load', function () {
            $(function() {
                $('input[name="datetime_report"]').daterangepicker({
                    timePicker: false,
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY-MM-DD',
                        cancelLabel: 'Clear'
                    }
                });

            });

            $('input[name="datetime_report"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                @this.emit('dateRangeReport', picker.startDate.format('YYYY-MM-DD 00:00:00'),picker.endDate.format('YYYY-MM-DD 23:59:59'))
            });
            $('input[name="datetime_report"]').on('cancel.daterangepicker', function(ev, picker) {
            @this.emit('dateRangeReport', '','')
                $(this).val('');
            })



        })
    </script>
</div>
