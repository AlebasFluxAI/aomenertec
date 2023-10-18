<div class="login">
    @section("header")
        {{--extended app.blade--}}

    @endsection


    @include("partials.v1.title",[
          "second_title"=>"importacion",
          "first_title"=>"Detalles de"
      ])
    @include("partials.v1.table_nav",
         ["mt"=>2,
         "nav_options"=>[
                    [
                        "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_CREATE],
                          "button_align"=>"right",
                          "click_action"=>"",
                          "button_content"=>"Importaciones",
                          "button_icon"=>"fa-solid fa-file-excel",
                          "target_route"=>"v1.admin.client.import-index.client",
                    ],
                 ]
        ])
    @include("partials.v1.tab.v1.tab",[

                             "tab_titles"=>[
                                                 [
                                                     "title"=>"Detalles",

                                                 ],
                                                 [
                                                     "title"=>"Items importados",

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
                                                                              "key"=>"Nombre",
                                                                              "value"=>$model->name
                                                                          ],
                                                                          [
                                                                              "key"=>"Tipo",
                                                                              "value"=>__("import.".$model->type)
                                                                          ],

                                                                          ]
                                                             ]
                                                 ],
    [
                                                    "view_name"=>"partials.v2.table.primary-table",
                                                    "view_values"=>[
                                                                         "class_container"=>"",
                                                                         "table_pageable"=>true,
                                                                         "table_headers"=>[
                                                                            [
                                                                                "col_name" =>"ID",
                                                                                "col_data" =>"id",
                                                                                "col_filter"=>false
                                                                            ],
                                                                             [
                                                                                "col_name" =>"Columna archivo",
                                                                                "col_data" =>"item_index",
                                                                                "col_filter"=>false
                                                                            ],
                                                                            [
                                                                                "col_name" =>"Estado",
                                                                                "col_data" =>"status",
                                                                                "col_filter"=>false,
                                                                                "col_translate"=>"import"
                                                                            ],
                                                                            [
                                                                               "col_name" =>"Cliente",
                                                                               "col_data" =>"importable.id",
                                                                               "col_redirect_url" =>"url",
                                                                               "col_filter"=>false
                                                                            ],
                                                                            [
                                                                                "col_name" =>"Errores",
                                                                                "col_data" =>"error",
                                                                                "col_type"=>\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_ARRAY,
                                                                                "col_filter"=>false
                                                                            ],
                                                                            ],
                                                                        "table_rows"=>$data,
                                                                               "table_actions"=>[
                                                                                        "customs"=>[
                                                                                                    [

                                                                                                           "conditional"=>"completedStatus",
                                                                                                           "redirect"=>[
                                                                                                                   "route"=>"v1.admin.client.detail.client",
                                                                                                                   "binding"=>"client",
                                                                                                                   "binding_value"=>"importable"

                                                                                                             ],
                                                                                                           "icon"=>"fas fa-search",
                                                                                                           "tooltip_title"=>"Ver importacion",
                                                                                                           "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_CREATE],
                                                                                                               ],
                                                                                                        ],
                                                                                                     ],
                                                                    ],

                                                 ],
                                                 ],
          ])

</div>

