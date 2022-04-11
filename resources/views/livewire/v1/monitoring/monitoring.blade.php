
<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Añadir",
            "second_title"=>"equipos",
        ])
        @foreach($test as $item)
            <h5>{{$item}}</h5>
        @endforeach
        <div class="container mx-auto space-y-4 p-4 sm:p-0 mt-8">
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <div style="height: 32rem;">
                    <livewire:livewire-line-chart
                        key="{{ $lineChartModel->reactiveKey() }}"
                        :line-chart-model="$lineChartModel"
                    />
                </div>
            </div>
        </div>
</div>

