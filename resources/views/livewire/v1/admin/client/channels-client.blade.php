`
<div class="login">
    @include("partials.v2.table.primary-table",[
              "class_container"=>false,
              "table_pageable"=>false,
              "table_headers"=>[
                  [
                      "col_name" =>"ID",
                      "col_data" =>"id",
                      "col_filter"=>false,
                  ],
                  [
                      "col_name" =>"Canal",
                      "col_data" =>"channel",
                      "col_translate"=>"channels",
                      "col_filter"=>false,
                  ],
                  [
                      "col_name" =>"Activo",
                      "col_data" =>"enabled",
                      "col_filter"=>false,
                      "col_type"=>\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN
                  ],

               ],
                "table_actions"=>[

                                   "customs"=>[
                                        [
                                                        "function"=>"blinkChannel",
                                                        "icon"=>"fas fa-power-off",
                                                        "tooltip_title"=>"Activar/Desactivar"
                                                    ],
                                      ]
                                   ],

              "table_rows"=>$channels

          ])
</div>
