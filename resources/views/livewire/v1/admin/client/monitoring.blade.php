@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Monitoreo",
            "second_title"=>$client->name
        ])

    {{--optiones de cabecera de formulario--}}



    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Grafica",

                                                ],

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.chart.monitoring",
                                                    "view_values"=>  [
                                                                        "cards"=>$cards,
                                                                        "variables"=>$variables,
                                                                        "last_data"=>$last_data,
                                                                        "client"=>$client,
                                                                        "variables_selected"=>$variables_selected,
                                                                        "time_id"=>$time_id,
                                                                        "chart_type"=>$chart_type

                                                                     ]
                                                            ]
                                                ],



         ])

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
                console.log(picker.startDate.format('YYYY-MM-DD HH:mm:00'))
            @this.emit('changeDateRange', picker.startDate.format('YYYY-MM-DD HH:mm:00'),picker.endDate.format('YYYY-MM-DD HH:mm:00'))
            });
        })
    </script>
</div>

