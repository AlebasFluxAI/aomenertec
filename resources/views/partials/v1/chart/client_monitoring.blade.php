@if($type == "history_data")

    @livewire('v1.admin.client.monitoring.charts.cards-data', ['client'=>$client, 'variables' => $variables, 'data_frame'=>$data_frame])
    @livewire('v1.admin.client.monitoring.charts.data-chart', ['client'=>$client, 'variables' => $variables, 'data_frame'=>$data_frame, 'data_chart'=>$data_chart, 'time'=>$time])

@elseif($type == "real_time_data")
    @livewire('v1.admin.client.monitoring.charts.real-time-chart', ['client'=>$client, 'variables' => $real_time_variables, 'data_frame'=>$data_frame])
@elseif($type == "reactive_data")
    @livewire('v1.admin.client.monitoring.charts.reactive-chart', ['client'=>$client, 'reactive_variables' => $reactive_variables, 'data_chart_reactive'=>$data_chart, 'time'=>$time])
@elseif($type == "heatmap_data")
    @livewire('v1.admin.client.monitoring.charts.heat-map-chart', ['client'=>$client, 'reactive_variables' => $reactive_variables, 'data_chart_heat_map'=>$data_chart])
@elseif($type == "baseline_data")
    @livewire('v1.admin.client.monitoring.charts.base-line-chart', ['client'=>$client, 'variables' => $variables, 'data_frame'=>$data_frame, 'data_chart'=>$data_chart_result, 'time'=>$time])
@elseif($type == "report_data")
    @livewire('v1.admin.client.monitoring.data-report', ['client'=>$client, 'variables' => $variables, 'data_frame'=>$data_frame])
@elseif($type == "control_data")
    @livewire('v1.admin.client.monitoring.control', ['client'=>$client])
@endif
