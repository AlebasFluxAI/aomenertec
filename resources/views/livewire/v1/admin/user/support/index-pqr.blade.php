@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
@include("partials.v1.title",[
        "first_title"=>"Cola PQRS",
        "second_title"=>""
    ])

{{--optiones de cabecera de formulario--}}


@include("partials.v2.table.primary-table",[
            "table_headers"=>\App\Models\V1\Pqr::indexTableHeaders(),
           "table_actions"=>[

                              "customs"=>[

                                                  [
                                                       "function"=>"takePqr",
                                                       "icon"=>"fas fa-ticket",
                                                       "tooltip_title"=>"Tomar pqr",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::SUPPORT_WORK_ORDER_QUEUE],
                                                 ],

                                               ],
                                           ],
         "table_rows"=>$data

     ])
