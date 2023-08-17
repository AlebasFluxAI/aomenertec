<div class="login">

    @include("partials.v1.title",[
          "second_title"=>"Lecturas cliente ".$model->alias,
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
                       "col_name" =>"Consumo(Kwh)",
                       "col_data" =>"accumulated_real_consumption",
                       "col_filter"=>true
                   ],
                   [
                      "col_name" =>"Consumo reactivo(Kvarh)",
                       "col_data" =>"accumulated_reactive_consumption",
                       "col_filter"=>true
                   ],
                   [
                       "col_name" =>"Fecha de lectura",
                       "col_data" =>"source_timestamp",
                       "col_filter"=>false,
                   ],
                   ],
                     "table_actions"=>[
                                "customs"=>[
                                                [
                                                   "redirect"=>[
                                                               "route"=>"v1.admin.client.hand_reading.detalle",
                                                               "binding"=>"microcontroller_data"
                                                         ],
                                                       "icon"=>"fas fa-search",
                                                       "tooltip_title"=>"Detalles",
                                                 ],


                                            ],
                                     ],

           ])
</div>

