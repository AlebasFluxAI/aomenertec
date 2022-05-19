<div class="contenedor-grande">
    <div wire:ignore class="row pt-3">

        @foreach($checks as $index => $item)
            @include("partials.v1.form.check_button",[
                                            "mt"=>0,
                                            "mb"=>0,
                                            'id' => $index,
                                            "id_button"=>$item['id_button'],
                                            "check_model" => 'checks.'.$index.'.check_model',
                                            "label_name" => $item['label_name'],
                                   ])
        @endforeach

            @include("partials.v1.form.form_input_icon",[
                            "mt"=>0,
                            "input_model"=>"date_range_report",
                            "icon_class"=>"fas fa-calendar",
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
                            "button_content" => 'Exportar CSV',

                    ])
            @include("partials.v1.primary_button",[
                            "col_with" => 'auto',
                            "button_align" => 'center',
                            "click_action" => 'reportPdf',
                            "class_button" => 'danger',
                            "button_icon" => 'fas fa-file-pdf',
                            "button_content" => 'Exportar PDF',

                    ])
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
