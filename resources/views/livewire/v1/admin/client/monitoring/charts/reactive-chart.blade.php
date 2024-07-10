<div>
    <div class="row pt-3">
        @include("partials.v1.form.form_label_double_input_button", [
                                "mt"=>3,
                                "mb"=>0,
                                "col_width"=>4,
                                "label"=> "kVARLh|kVARCh",
                                "model_first_input"=>"inductive_filter",
                                "model_second_input"=>"capacitive_filter",
                                "type_first_input"=>"number",
                                "type_second_input"=>"number",
                                "placeholder_first_input"=>"Inductiva",
                                "placeholder_second_input"=>"Capacitiva",
                                "button_action"=>"applyFilterReactive",
                                "button_name"=>"Filtro",
                                "disabled"=>($time_reactive_id == 1) ? true : false,
                                "number_min" => 0,
                                "number_step" => 0.1

                        ])

        @include("partials.v1.form.form_list",[
                                         "col_with" =>1,
                                         "mt"=>3,
                                         "mb"=>0,
                                         "input_type"=>"text",
                                         "list_model" => "time_reactive_id",
                                         "list_default" => "Tiempo",
                                         "list_options" => ($penalizable) ?[
                                                            ['id'=>2, 'display_name'=> 'Hora'],
                                                            ['id'=>3, 'display_name'=> 'Dia'],
                                                            ['id'=>4, 'display_name'=> 'Mes'],
                                                           ] :
                                                           [
                                                               ['id'=>1, 'display_name'=> 'Minuto'],
                                                            ['id'=>2, 'display_name'=> 'Hora'],
                                                            ['id'=>3, 'display_name'=> 'Dia'],
                                                            ['id'=>4, 'display_name'=> 'Mes']
                                                            ],

                                         "list_option_value"=>"id",
                                         "list_option_view"=>"display_name",
                                         "list_option_title"=>"",
                                ])


        @include("partials.v1.form.check_button",[
                                        "mt"=>0,
                                        "mb"=>0,
                                        "col_width"=>1,
                                        "check_model"=>"penalizable",
                                        "check_label"=>"Penalizable",
                                        "check_id"=>"penalizable",
                                        "disabled"=>($time_reactive_id == 1) ? true : false
        ])

        @include("partials.v1.form.form_input_icon_button",[
                        "mt"=>3,
                        "mb"=>0,
                        "input_model"=>"date_range_reactive",
                        "icon_class"=>"fas fa-calendar",
                        "placeholder"=>"Seleccione rango de fechas",
                        "col_with"=>5,
                        "input_type"=>"text",
                        "input_name"=>"datetime_reactive",
                        "autocomplete"=> "off",
                        "button_name" => "Borrar",
                        "button_action"=> "selectReactive"
               ])


        <div class="col-12 mt-0">
            <div class="box shadow mt-4">
                <div wire:loading>
                    Actualizando Grafica...
                </div>
                <div id="chart_reactive">

                </div>

            </div>
        </div>
    </div>
    <script>


        document.addEventListener('livewire:load', function () {
            $(function () {
                $('input[name="datetime_reactive"]').daterangepicker({
                    applyButtonClasses: 'text-primary',
                    timePicker: true,
                    timePicker24Hour: true,
                    locale: {
                        format: 'YYYY-MM-DD HH:mm'
                    }
                });

            });

            var options_reactive = {
                chart: {
                    id: 'reactive_chart',
                    type: 'bar',
                    height: '800px',
                    stacked: true,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }

                },
                title: {
                    text: 'ENERGIA ACTIVA-REACTIVA',
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        fontFamily: 'sans-serif',
                        color: '#000'
                    },

                },
                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },
                series: [],
                xaxis: {
                    categories: [],
                },
                noData: {
                    text: 'Sin datos'
                },


            }

            var chart_reactive = new ApexCharts(document.querySelector("#chart_reactive"), options_reactive);

            chart_reactive.render();

        @this.on('changeAxisReactive', (e) => {

            ApexCharts.exec('reactive_chart', "updateOptions", {
                series: e.series_reactive,
                xaxis: {
                    categories: e.x_axis_reactive
                }
            });
        })

        @this.on('loading8', (e) => {
            ApexCharts.exec('reactive_chart', "updateOptions", {
                series: [],
                xaxis: {
                    categories: []
                },
                noData: {
                    text: 'Datos no encontrados'
                }
            });
        })
            $('input[name="datetime_reactive"]').on('apply.daterangepicker', function (ev, picker) {
            @this.emit('dateRangeReactive', picker.startDate.format('YYYY-MM-DD HH:mm:00'), picker.endDate.format('YYYY-MM-DD HH:mm:00'))
            });
        })
    </script>
</div>



