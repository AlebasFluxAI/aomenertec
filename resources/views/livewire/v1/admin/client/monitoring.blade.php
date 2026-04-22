@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Monitoreo",
            "second_title"=>($model->alias??$model->name),
        ])

    {{--optiones de cabecera de formulario--}}
    @include("partials.v1.table_nav",
            [ "nav_options"=>[
                       ["button_align"=>"right",
                       "click_action"=>"",
                       "button_icon"=>"fas fa-list",
                       "button_content"=>"Listado de clientes",
                       "target_route"=>"v1.admin.client.list.client",
                       ],
                       [
                        "button_align"=>"right",
                        "button_type"=>"dropdown",
                        "button_icon"=>"fas fa-gear",
                        "button_content"=>"Acciones",
                        "button_options"=>$model->navigatorDropdownOptions()
                        ]

                   ]
           ])


    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Dashboard",
                                                    "action" => "emit('selectHistory')"

                                                ],
                                                [
                                                    "title"=>"BaseLine",
                                                    "action" => "emit('selectBaseLine')"

                                                ],
                                                [
                                                    "title"=>"Reportes y tarifas",
                                                    "action" => "emit('selectReport')"

                                                ],
                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.chart.client_monitoring",
                                                    "view_values"=>  [
                                                                        "type" => "dashboard_unified",
                                                                        "variables"=>$variables,
                                                                        "reactive_variables"=>$reactive_variables,
                                                                        "real_time_variables"=>$real_time_variables,
                                                                        "client"=>$client,
                                                                        "data_frame"=>$data_frame,
                                                                        "data_chart" => $data_chart,
                                                                        "time" => $time,
                                                                        "liveMode" => $liveMode,
                                                                     ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v1.chart.client_monitoring",
                                                    "view_values"=>  [
                                                                        "type" => "baseline_data",
                                                                        "variables"=>$variables,
                                                                        "client"=>$client,
                                                                        "data_frame"=>$data_frame,
                                                                        "data_chart" => $data_chart,
                                                                        "time" => $time

                                                                     ]
                                                ],
                                                [
                                                    "view_name"=>"partials.v1.chart.client_monitoring",
                                                    "view_values"=>  [
                                                                        "type" => "report_data",
                                                                        "variables"=>$variables,
                                                                        "client"=>$client,
                                                                        "data_frame"=>$data_frame

                                                                     ]
                                                ],

                                            ],



         ])
    <script>
        window.onbeforeunload = function (e) {
            console.log("exit");
        @this.emit('tabChange')
        };

        window.rehydrateMonitoringHistoricalCharts = window.rehydrateMonitoringHistoricalCharts || function () {
            const livewireRootFor = function (selector) {
                const root = document.querySelector(selector)?.closest('[wire\\:id]');
                if (!root) return null;

                const livewireInstance = window.Livewire || window.livewire;
                return livewireInstance ? livewireInstance.find(root.getAttribute('wire:id')) : null;
            };

            requestAnimationFrame(() => {
                setTimeout(() => {
                    const dataChartWire = livewireRootFor('#chart_line');
                    const reactiveChartWire = livewireRootFor('#chart_reactive');
                    const heatMapWire = livewireRootFor('#chart_heat_map');

                    if (dataChartWire && typeof window.initDataChart === 'function') {
                        window.initDataChart(dataChartWire);
                    }

                    if (reactiveChartWire && typeof window.initReactiveChart === 'function') {
                        window.initReactiveChart(reactiveChartWire);
                    }

                    if (heatMapWire && typeof window.initHeatMapChart === 'function') {
                        window.initHeatMapChart(heatMapWire);
                    }
                }, 80);
            });
        };

        window.fluxScheduleChartResize = window.fluxScheduleChartResize || function () {
            requestAnimationFrame(() => {
                setTimeout(() => window.dispatchEvent(new Event('resize')), 120);
                setTimeout(() => window.dispatchEvent(new Event('resize')), 360);
            });
        };

        if (!window.__monitoringHistoricalRemountBound) {
            window.addEventListener('monitoring-historical-remount', () => {
                window.rehydrateMonitoringHistoricalCharts();
            });
            window.__monitoringHistoricalRemountBound = true;
        }
    </script>
</div>
