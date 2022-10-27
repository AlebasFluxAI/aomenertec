@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
@include("partials.v1.title",[
        "first_title"=>"Ordenes de",
        "second_title"=>" trabajo"
    ])

{{--optiones de cabecera de formulario--}}

@include("partials.v1.table_nav",
     ["mt"=>4,"nav_options"=>[
                ["button_align"=>"right",
                "click_action"=>"",
                "button_icon"=>"fas fa-plus",
                "button_content"=>"Nueva orden de trabajo",
                "target_binding"=>"client",
                "target_binding_value"=>$model->id,
                "target_route"=>"v1.admin.client.work_orders.create",
                ],

            ]
    ])

@include("partials.v2.table.primary-table",[
            "table_headers"=>\App\Models\V1\WorkOrder::indexTableHeaders(),
           "table_actions"=>[

                              "customs"=>[
                                                ["redirect"=>[
                                                               "route"=>"administrar.v1.ordenes_de_servicio.detalle",
                                                               "binding"=>"workOrder"
                                                         ],
                                                       "icon"=>"fas fa-search",
                                                       "tooltip_title"=>"Detalles",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::WORK_ORDER_DETAILS],
                                                 ],
                                                ],
                                           ],
         "table_rows"=>$data

     ])
