
<section class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"equipos",
        ])
        <div class="contenedor-grande">
            <div class="row pt-3">
                @foreach($cards as $index => $item)
                    @include('partials.v1.cards.variable-card', [
                                "icon_class" => $item['icon'],
                                "color"=>$item['color'],
                                "list_variable_options" => $variables,
                                "list_model_variable" => 'cards.'.$index.'.list_model_variable',
                                "data" => $item['variables_selected'],
                                "last_data" => $last_data,
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
                    @include("partials.v1.form.form_input_icon",[
                                    "mt"=>4,
                                    "input_model"=>"date_range",
                                    "icon_class"=>"fas fa-calendar",
                                    "placeholder"=>"fechas",
                                    "col_with"=>4,
                                    "input_type"=>"text",
                                    "input_name"=>"datetimes",
                                    "autocomplete"=> "off"
                           ])

            </div>
            <div class="col-12 mt-0">
                @livewire('v1.monitoring.charts.line-chart', ['client'=>$client, 'variables_selected' => $variables_selected, 'time'=>$time_id])
            </div>




        </div>
        <script>
            $(function() {
                $('input[name="datetimes"]').daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    startDate: moment().startOf('hour'),
                    endDate: moment().startOf('hour').add(1, 'hour'),
                    locale: {
                        format: 'YYYY-MM-DD HH:mm'
                    }
                });
            });


            document.addEventListener('livewire:load', function () {
                $('input[name="datetimes"]').on('apply.daterangepicker', function(ev, picker) {
                    @this.changeDateRange(picker.startDate.format('YYYY-MM-DD HH:mm:00'),picker.endDate.format('YYYY-MM-DD HH:mm:00'))
                });
            })
        </script>
</section>


