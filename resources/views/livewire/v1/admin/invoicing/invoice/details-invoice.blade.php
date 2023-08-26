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
                                                                             "key"=>"Administrador",
                                                                             "value"=>$model->model->id." -".$model->model->name

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
                                                                               "col_name" =>"Subtotal",
                                                                               "col_data" =>"subtotal",
                                                                               "col_filter"=>false,
                                                                               "col_money"=>true,
                                                                               "col_currency_custom"=>$model->currency
                                                                           ],
                                                                           [
                                                                               "col_name" =>"Descuentos",
                                                                               "col_data" =>"discount",
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
    @if($model->payment_status!=\App\Models\V1\Invoice::PAYMENT_STATUS_PAID)
        <div class="text-center"
             style="background-color: green;padding: 10px;margin-left: 20%;margin-right: 20%;border-radius: 15px">
            <button id="add"
                    class="mb-2 py-2 px-4" data-toggle="modal" data-target="#exampleModal">
                <b>
                    <i class="fas fa-check"></i> Pagar factura
                </b>
            </button>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <p>Confirmar pago</p>
                    </div>
                    <div class="modal-body">

                        <p> ¿Estas seguro de realizar el pago ?</p>
                        <p style="color: teal"> <span
                                style="color: teal;font-size: 20px"> ${{\App\Http\Resources\V1\Formatter::currencyFormat($model->total)}}</span>
                        </p>

                        <div class="text-right">

                            <form action="https://checkout.wompi.co/p/"
                                  method="GET">
                                <!-- OBLIGATORIOS -->
                                <input type="hidden" name="public-key"
                                       value="pub_test_knPE3DSMREXJQgxqle2QgpGDEs7x3wJT"/>
                                <input type="hidden" name="currency" value="COP"/>
                                <input type="hidden" name="amount-in-cents" value="{{$model->total."00"}}"/>
                                <input type="hidden" name="reference" value="{{$model->code}}"/>
                                <input type="hidden" name="customer-data.email" value="{{$model->model->email}}"/>
                                <input type="hidden" name="customer-data.full-name"
                                       value="{{$model->model->name." ".$model->model->last_name}}"/>
                                <input type="hidden" name="customer-data.phone-number"
                                       value="{{$model->model->phone}}"/>
                                <input type="hidden" name="customer-data.phone-number-prefix"
                                       value="+57"/>
                                <input type="hidden" name="customer-data.legal-id"
                                       value="{{$model->model->identification}}"/>
                                <input type="hidden" name="customer-data.legal-type"
                                       value="{{$model->model->identification_type}}"/>
                                <button wire:click="confirmRecharge" type="submit">Pagar factura</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

</div>
