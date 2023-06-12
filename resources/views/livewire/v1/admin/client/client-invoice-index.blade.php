<div class="login">

    @include("partials.v1.title",[
          "second_title"=>"facturas cliente ".$model->alias,
          "first_title"=>"Listado"
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
                     "button_options"=>$client->navigatorDropdownOptions()
                     ]
                  ]
         ])
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
                       "col_name" =>"Administrador",
                       "col_data" =>"adminName",
                       "col_filter"=>false
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
                                                               "route"=>"administrar.v1.facturacion.facturas.detalle",
                                                               "binding"=>"invoice"
                                                         ],
                                                       "icon"=>"fas fa-search",
                                                       "tooltip_title"=>"Detalles",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::INVOICE_SHOW],
                                                 ],
                                                 [
                                                   "redirect"=>[
                                                               "route"=>"administrar.v1.facturacion.facturas.pdf",
                                                               "binding"=>"invoice"
                                                         ],
                                                       "icon"=>"fas fa-download",
                                                       "tooltip_title"=>"Descargar PDF",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::INVOICE_FILE],
                                                 ],

                                            ],
                                     ],

           ])
</div>

