<div class="row pt-3">
    @if($last_data)
        @foreach($cards as $index => $item)

            @include('partials.v1.chart.variable-card', [
                        "icon_class" => $item['icon'],
                        "color"=>$item['color'],
                        "list_variable_options" => $variables,
                        "list_model_variable" => 'cards.'.$index.'.list_model_variable',
                        "data" => $item['variables_selected'],
                        "id"=>$index,
                ])

        @endforeach
    @endif
</div>








