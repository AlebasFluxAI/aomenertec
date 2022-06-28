
<div class="row pt-3">

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
                                             ['id'=>3, 'display_name'=> 'Dia'],
                                             ['id'=>4, 'display_name'=> 'Mes'],


                                            ],
                          "list_option_value"=>"id",
                          "list_option_view"=>"display_name",
                          "list_option_title"=>"",
                 ])
     @include("partials.v1.form.form_input_icon_button",[
                     "mt"=>4,
                     "input_model"=>"date_range",
                     "icon_class"=>"fas fa-calendar",
                     "updated_input"=>"",
                     "placeholder"=>"Seleccione rango de fechas",
                     "col_with"=>6,
                     "input_type"=>"text",
                     "input_name"=>"datetimes",
                     "autocomplete"=> "off",
                     "button_name" => "Borrar",
                     "button_action"=> "restartDateRange"
            ])
         <div class="col-12 mt-0">
             <div class="box shadow mt-4">
                 <div wire:loading>
                     Actualizando Grafica...
                 </div>
                 <div id="chart_line">

                 </div>
             </div>
         </div>
        <script>

        $(function() {
            $('input[name="datetimes"]').daterangepicker({
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: 'YYYY-MM-DD HH:mm'
                }
            });

        });


        document.addEventListener('livewire:load', function () {
            var options = {
                chart: {
                    id: 'line_chart',
                    type: @js($chart_type),
                    height: '450px',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 300
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    }

                },
                series: [],
                xaxis: {
                    categories: []
                },
                noData: {
                    text: 'Sin datos'
                },
                stroke: {
                    curve: 'smooth'
                }


            }

            var chart_line = new ApexCharts(document.querySelector("#chart_line"), options);

            chart_line.render();
            ApexCharts.exec('line_chart', "updateOptions", {
                series: @js($series),
                xaxis: {
                    categories: @js($x_axis)
                },
                title: {
                    text: @js($chart_title),
                    align: 'center',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        fontFamily: 'sans-serif',
                        color: '#000'
                    },
                }
            });
        @this.on('changeAxis',(e) =>{

            ApexCharts.exec('line_chart', "updateOptions", {
                series: e.series,
                xaxis: {
                    categories: e.x_axis
                },
                title: {
                    text: e.title,
                }
            });
        })
        @this.on('loading',(e) =>{
            ApexCharts.exec('line_chart', "updateOptions", {
                series: [],
                xaxis: {
                    categories: []
                },
                noData: {
                    text: 'Datos no encontrados'
                }
            });
        })
            $('input[name="datetimes"]').on('apply.daterangepicker', function(ev, picker) {
            @this.emit('changeDateRange', picker.startDate.format('YYYY-MM-DD HH:mm:00'),picker.endDate.format('YYYY-MM-DD HH:mm:00'))
            });
        })
    </script>
</div>








