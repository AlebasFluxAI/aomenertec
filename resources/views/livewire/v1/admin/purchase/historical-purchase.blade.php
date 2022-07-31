@if($view_header??true)
    <div class="login">
        @section("header")
            {{--extended app.blade--}}

        @endsection


        @include("partials.v1.title",[
              "second_title"=>"recargas",
              "first_title"=>"Historico de"
          ])
        @endif
        @include("partials.v2.table.primary-table",[
                       "class_container"=>$table_class_container??null,
                       "table_pageable"=>$table_pageable??true,
                       "table_headers"=>[
                           [
                               "col_name" =>"ID",
                               "col_data" =>"id",
                               "col_filter"=>$col_filter??true
                           ],
                        ],
                       "table_rows"=>$data
                   ])

    </div>
