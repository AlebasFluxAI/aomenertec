@if($type == "history_data")
    <div class="contenedor-grande">
        @livewire('v1.admin.client.monitoring.charts.cards-data', ['client'=>$client, 'variables' => $variables, 'data_frame'=>$data_frame])
        @livewire('v1.admin.client.monitoring.charts.data-chart', ['client'=>$client, 'variables' => $variables, 'data_frame'=>$data_frame, 'data_chart'=>$data_chart])
    </div>
@elseif($type == "reactive_data")
    @livewire('v1.admin.client.monitoring.charts.reactive-chart', ['client'=>$client, 'reactive_variables' => $reactive_variables, 'data_chart_reactive'=>$data_chart])
@elseif($type == "heatmap_data")
    @livewire('v1.admin.client.monitoring.charts.heat-map-chart', ['client'=>$client, 'reactive_variables' => $reactive_variables, 'data_chart_heat_map'=>$data_chart])
@elseif($type == "report_data")
    @livewire('v1.admin.client.monitoring.data-report', ['client'=>$client, 'variables' => $variables, 'data_frame'=>$data_frame])
@endif


