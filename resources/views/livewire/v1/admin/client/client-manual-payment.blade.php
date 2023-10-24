<div class="login">

    @include("partials.v1.title",[
          "second_title"=>"pagp cliente ".$model->alias,
          "first_title"=>"Registrar"
      ])

    @include("partials.v1.table_nav",
          ["mt"=>2,
          "nav_options"=>[
                     ["button_align"=>"right",
                     "click_action"=>"",
                     "button_icon"=>"fas fa-list",
                     "button_content"=>"Ver listado",
                     "target_route"=>"v1.admin.client.list.client",
                     ],
                     [
                     "button_align"=>"right",
                     "button_type"=>"dropdown",
                     "button_icon"=>"fas fa-gear",
                     "button_content"=>"Acciones",
                     "button_options"=>$model->navigatorDropdownOptions()
                     ]
                  ]
         ])
    @include("partials.v1.divider_title",
         ["title"=>"Facturas con pago pendiente"])

    @include("partials.v2.table.primary-table",[
               "table_rows"=>$data,
               "table_headers"=>[
                  [
                       "col_name" =>"ID",
                       "col_data" =>"id",
                       "col_filter"=>true
                   ],
                   [
                       "col_name" =>"Codigo",
                       "col_data" =>"code",
                       "col_filter"=>true
                   ],
                   [
                       "col_name" =>"Estado",
                       "col_data" =>"payment_status",
                       "col_translate"=>"invoice",
                       "col_filter"=>false
                   ],
                   [
                       "col_name" =>"Total",
                       "col_data" =>"total",
                       "col_filter"=>false,
                       "col_money"=>true,
                       "col_currency"=>"currency"
                   ],
                   [
                       "col_name" =>"Moneda",
                       "col_data" =>"currency",
                       "col_filter"=>false,
                   ],
                   ],
                     "table_actions"=>[
                                "customs"=>[
                                                [
                                                   "redirect"=>[
                                                               "route"=>"v1.admin.client.manual_payment.register",
                                                               "binding"=>"invoice"
                                                         ],
                                                       "icon"=>"fas fa-cash-register",
                                                       "tooltip_title"=>"Registrar pago",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::INVOICE_SHOW],
                                                 ],
                                            ],
                                     ],

           ])
</div>

