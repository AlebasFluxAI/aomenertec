
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
        <div class="col-12 py-1">
            <div id="chart"></div>
        </div>


</div>
{{--<script>
    //import ApexCharts from 'apexcharts'
    var options = {
        chart: {
            type: 'line'
        },
        series: [{
            name: 'sales',
            data: [30,40,35,50,49,60,70,91,125]
        }],
        xaxis: {
            categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
        }
    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);

    chart.render();
</script>--}}
