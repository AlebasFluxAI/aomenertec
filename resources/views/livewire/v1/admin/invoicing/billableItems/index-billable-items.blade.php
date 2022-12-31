<div class="login">
    @section("header")
        {{--extended app.blade--}}

    @endsection

    @include("partials.v1.title",[
          "second_title"=>"items facturables",
          "first_title"=>"Listado"
      ])



    @include("partials.v1.table_nav",
           ["mt"=>2,"nav_options"=>[
                      [
                      "button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Crear nuevo",
                      "icon"=>"fa-solid fa-plus",
                      "target_route"=>"administrar.v1.facturacion.items.crear",
                      ],

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
                       "col_name" =>"Nombre",
                       "col_data" =>"name",
                       "col_filter"=>true
                   ],
                    [
                       "col_name" =>"Descripcion",
                       "col_data" =>"description",
                       "col_filter"=>true
                   ],
                   [
                       "col_name" =>"Impuesto",
                       "col_data" =>"tax.percentage",
                       "col_filter"=>true
                   ],
                   ],
                     "table_actions"=>[
                                "customs"=>[
                                                [
                                                   "redirect"=>[
                                                               "route"=>"administrar.v1.facturacion.items.detalle",
                                                               "binding"=>"billable_item"
                                                         ],
                                                       "icon"=>"fas fa-search",
                                                       "tooltip_title"=>"Detalles",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::BILLABLE_ITEMS_SHOW],
                                                 ],
                                                  [
                                                           "redirect"=>[
                                                                       "route"=>"administrar.v1.facturacion.items.editar",
                                                                       "binding"=>"billable_item"
                                                                 ],
                                                               "icon"=>"fas fa-pencil",
                                                               "tooltip_title"=>"Editar",
                                                               "permission"=>[\App\Http\Resources\V1\Permissions::BILLABLE_ITEMS_EDIT],
                                                  ],
                                            ],
                                     ],

           ])
</div>

