<div class="login">
    @section("header")
        {{--extended app.blade--}}

    @endsection

    @include("partials.v1.title",[
          "second_title"=>"de impuestos",
          "first_title"=>"Listado"
      ])



    @include("partials.v1.table_nav",
           ["mt"=>2,"nav_options"=>[
                      [
                      "button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Crear nuevo",
                      "icon"=>"fa-solid fa-plus",
                      "target_route"=>"administrar.v1.facturacion.impuestos.crear",
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
                       "col_name" =>"Nombre",
                       "col_data" =>"name",
                       "col_filter"=>false
                   ],
                    [
                       "col_name" =>"Descripcion",
                       "col_data" =>"description",
                       "col_filter"=>false
                   ],
                   [
                       "col_name" =>"Porcentage",
                       "col_data" =>"percentage",
                       "col_filter"=>false
                   ],
                   ],
                     "table_actions"=>[
                                "customs"=>[
                                                [
                                                   "redirect"=>[
                                                               "route"=>"administrar.v1.facturacion.impuestos.detalle",
                                                               "binding"=>"tax"
                                                         ],
                                                       "icon"=>"fas fa-search",
                                                       "tooltip_title"=>"Detalles",
                                                       "permission"=>[\App\Http\Resources\V1\Permissions::TAX_SHOW],
                                                 ],
                                                  [
                                                           "redirect"=>[
                                                                       "route"=>"administrar.v1.facturacion.impuestos.editar",
                                                                       "binding"=>"tax"
                                                                 ],
                                                               "icon"=>"fas fa-pencil",
                                                               "tooltip_title"=>"Editar",
                                                               "permission"=>[\App\Http\Resources\V1\Permissions::TAX_EDIT],
                                                  ],
                                            ],
                                     ],

           ])
</div>

