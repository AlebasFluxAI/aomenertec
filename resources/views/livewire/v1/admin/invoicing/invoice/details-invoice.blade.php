@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"factura"
        ])

    {{--optiones de cabecera de formulario--}}
    @include("partials.v1.table_nav",
         ["mt"=>4,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.facturacion.facturas.listado",
                    ],

                ]
        ])

    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],
                                                [
                                                    "title"=>"Productos",

                                                ],

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>  [
                                                                        "table_info"=>[
                                                                         [
                                                                             "key"=>"Id",
                                                                             "value"=>$model->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Código",
                                                                             "value"=>$model->code
                                                                         ],
                                                                         [
                                                                             "key"=>"Subtotal",
                                                                             "value"=>$model->subtotal,
                                                                             "money"=>true,
                                                                             "currency"=>$model->currency

                                                                         ],
                                                                         [
                                                                             "key"=>"Total impuestos",
                                                                             "value"=>$model->tax_total,
                                                                             "money"=>true,
                                                                             "currency"=>$model->currency

                                                                         ],
                                                                         [
                                                                             "key"=>"Descuentos",
                                                                             "value"=>$model->discount,
                                                                             "money"=>true,
                                                                             "currency"=>$model->currency

                                                                         ],
                                                                         [
                                                                             "key"=>"Total",
                                                                             "value"=>$model->total,
                                                                             "money"=>true,
                                                                             "currency"=>$model->currency

                                                                         ],
                                                                            [
                                                                             "key"=>"Estado de pago",
                                                                             "value"=>__("invoice.".$model->payment_status),
                                                                         ],

                                                                         ]
                                                            ]
                                                ],
   [
                                                   "view_name"=>"partials.v2.table.primary-table",
                                                   "view_values"=>[
                                                                        "class_container"=>"",
                                                                        "table_pageable"=>false,
                                                                        "table_headers"=>[
                                                                           [
                                                                               "col_name" =>"ID",
                                                                               "col_data" =>"id",
                                                                               "col_filter"=>false
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Nombre",
                                                                               "col_data" =>"billableItem.name",
                                                                               "col_filter"=>false
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Notas",
                                                                               "col_data" =>"notes",
                                                                               "col_filter"=>false,
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Cantidad",
                                                                               "col_data" =>"quantity",
                                                                               "col_filter"=>false
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Valor unitario",
                                                                               "col_data" =>"unit_total",
                                                                               "col_filter"=>false,
                                                                               "col_money"=>true,
                                                                               "col_currency_custom"=>$model->currency
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Impuestos",
                                                                               "col_data" =>"tax_total",
                                                                               "col_filter"=>false,
                                                                               "col_money"=>true,
                                                                               "col_currency_custom"=>$model->currency
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Total",
                                                                               "col_data" =>"total",
                                                                               "col_filter"=>false,
                                                                               "col_money"=>true,
                                                                               "col_currency_custom"=>$model->currency
                                                                           ],
                                                                                                                    ],
                                                                       "table_rows"=>$model->items,
                                                                   ],

                                                ],
                                                ]
         ])
    @if($model->payment_status!=\App\Models\V1\Invoice::PAYMENT_STATUS_APPROVED)
        @include("partials.v1.payment.payment_button",[
             "total"=>$model->total,
             "reference"=>$model->code,
             "email"=>$model->model->email,
             "customer_last_name"=>$model->model->last_name,
             "customer_name"=>$model->model->name,
             "customer_phone"=>$model->model->phone,
             "customer_identification"=>$model->model->identification,
             "customer_identification_type"=>$model->model->identification_type,
             "public_key"=>$public_key
          ])
    @endif

</div>
