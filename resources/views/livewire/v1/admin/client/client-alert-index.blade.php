@if($view_header??true)
    <div class="login">
        @section("header")
            {{--extended app.blade--}}

        @endsection


        @include("partials.v1.title",[
              "second_title"=>"de alertas cliente ".($model->alias??$model->name),
              "first_title"=>"Listado"
          ])
        @endif

        @include("partials.v1.table_nav",
                     ["mt"=>2,"nav_options"=>[
                                ["button_align"=>"right",
                                "click_action"=>"",
                                "button_icon"=>"fas fa-list",
                                "button_content"=>"Ver listado",
                                "target_route"=>"v1.admin.client.list.client",
                                ],

                            ]
                    ])


        @include("partials.v2.table.primary-table",[
                "class_container"=>$table_class_container??null,
               "table_pageable"=>$table_pageable??true,
               "table_headers"=>[
              [
                   "col_name" =>"ID",
                   "col_data" =>"id",
                   "col_filter"=>true
               ],
               [
                   "col_name" =>"Variable",
                   "col_data" =>"name",
                   "col_filter"=>true
               ],
               [
                   "col_name" =>"Limite min",
                   "col_data" =>"clientAlertConfiguration.min_alert",
                   "col_filter"=>true
               ],
               [
                   "col_name" =>"Limite max",
                   "col_data" =>"clientAlertConfiguration.max_alert",
                   "col_filter"=>true
               ],
               [
                   "col_name" =>"Valor alerta",
                   "col_data" =>"value",
                   "col_filter"=>true
               ],
               [
                   "col_name" =>"Tipo",
                   "col_data" =>"type",
                   "col_filter"=>true
               ],
               [
                    "col_name" =>"Actualizacion limites",
                    "col_data" =>"clientAlertConfiguration.updated_at",
                    "col_filter"=>false
               ],
               [
                    "col_name" =>"Fecha",
                    "col_data" =>"created_at",
                    "col_filter"=>false
               ],

                ],
               "table_actions"=>[
                                           "customs"=>[
                               [
                                     "function"=>"deleteAlert",
                                     "icon"=>"fas fa-trash",
                                     "tooltip_title"=>"Eliminar",
                               ],
                           ],
               ],
               "table_rows"=>$data
           ])
        @if($view_header??true)
    </div>
@endif
