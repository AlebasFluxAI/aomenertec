
        <div class="contenedor-grande">
            <div class="row pt-3">
                @foreach($cards as $index => $item)
                    @include('partials.v1.chart.variable-card', [
                                "icon_class" => $item['icon'],
                                "color"=>$item['color'],
                                "list_variable_options" => $variables,
                                "list_model_variable" => 'cards.'.$index.'.list_model_variable',
                                "data" => $item['variables_selected'],
                                "id"=>$item['id'],
                        ])
                @endforeach
                    @include("partials.v1.form.form_list",[
                                         "col_with"=>4,
                                         "mt"=> 4,
                                         "mb"=>0,
                                         "input_type"=>"text",
                                         "list_model" => "variable_chart_id",
                                         "list_default" => "Variable...",
                                         "list_options" => $variables,
                                         "list_option_value"=>"id",
                                         "list_option_view"=>"display_name",
                                         "list_option_title"=>"",
                                ])
                    @include("partials.v1.form.form_list",[
                                         "col_with"=>2,
                                         "mt"=>4,
                                         "mb"=>0,
                                         "input_type"=>"text",
                                         "list_model" => "time_id",
                                         "list_default" => "Muestreo...",
                                         "list_options" => [
                                                            ['id'=>1, 'display_name'=> 'Minuto'],
                                                            ['id'=>2, 'display_name'=> 'Hora'],


                                                           ],
                                         "list_option_value"=>"id",
                                         "list_option_view"=>"display_name",
                                         "list_option_title"=>"",
                                ])
                    @include("partials.v1.form.form_input_icon_button",[
                                    "mt"=>4,
                                    "input_model"=>"date_range",
                                    "icon_class"=>"fas fa-calendar",
                                    "placeholder"=>"Seleccione rango de fechas",
                                    "col_with"=>6,
                                    "input_type"=>"text",
                                    "input_name"=>"datetimes",
                                    "autocomplete"=> "off",
                                    "button_name" => "Borrar",
                                    "button_action"=> "restartDateRange"
                           ])

            </div>

            <div class="col-12 mt-0">
                @livewire('v1.monitoring.charts.line-chart', ['client'=>$client, 'variables_selected' => $variables_selected, 'time'=>$time_id, 'chart_type'=>$chart_type, 'data_chart'=>$data_chart])
            </div>
            <script>

                $(function() {
                    $('input[name="datetimes"]').daterangepicker({
                        timePicker: true,
                        timePicker24Hour: true,
                        showDropdowns:true,
                        locale: {
                            format: 'YYYY-MM-DD HH:mm'
                        }
                    });

                });


                document.addEventListener('livewire:load', function () {
                    $('input[name="datetimes"]').on('apply.daterangepicker', function(ev, picker) {
                    @this.emit('changeDateRange', picker.startDate.format('YYYY-MM-DD HH:mm:00'),picker.endDate.format('YYYY-MM-DD HH:mm:00'))
                    });
                })
            </script>
        </div>




