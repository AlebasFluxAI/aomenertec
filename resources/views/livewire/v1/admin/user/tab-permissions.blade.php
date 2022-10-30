<div class="login">
    @include("partials.v1.title",[
      "second_title"=>"de pestañas {$model_class::getRole()} - $model->name",
      "first_title"=>"Permisos"
  ])
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
                      "col_name" =>"Permiso",
                      "col_data" =>"permission",
                      "col_translate"=>"tab_permission",
                      "col_filter"=>false,
                  ],
                   [
                      "col_name" =>"Descripcion",
                      "col_data" =>"description",
                      "col_filter"=>false,
                  ],
                  [
                      "col_name" =>"Activo",
                      "col_data_component_function"=>true,
                      "col_data" =>"enabled",
                      "col_filter"=>false,
                      "col_type"=>\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN
                  ],
                  [
                      "col_name" =>"Clientes",
                      "col_data_component_function"=>true,
                      "col_data" =>"clients",
                      "col_filter"=>false,
                  ],

               ],
                "table_actions"=>[

                                   "customs"=>[
                                        [
                                                        "function"=>"blinkTabPermission",
                                                        "icon"=>"fas fa-power-off",
                                                        "tooltip_title"=>"Activar/Desactivar"
                                        ],
                                      ]
                                   ],

              "table_rows"=>$tab_permissions

          ])
</div>
