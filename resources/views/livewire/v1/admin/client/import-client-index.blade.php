<div class="login">

    @include("partials.v1.title",[
          "second_title"=>"importaciones",
          "first_title"=>"Listado"
      ])


    @include("partials.v1.table_nav",
           [
               "nav_options"=>[
                      [
                      "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_CREATE],
                      "button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Nueva importación",
                      "button_icon"=>"fa-solid fa-file-excel",
                      "target_route"=>"v1.admin.client.import.client",
                      ],

                  ]
          ])

    @include("partials.v2.table.primary-table",[
            "class_container"=>$table_class_container??null,
           "table_pageable"=>$table_pageable??true,
           "table_headers"=>[
                  [
                       "col_name" =>"ID",
                       "col_data" =>"id",
                       "col_filter"=>true
                   ],
                   [
                       "col_name" =>"Nombre",
                       "col_data" =>"name",
                       "col_filter"=>true
                   ],
                   [
                       "col_name" =>"Archivo",
                       "col_data" =>"file_name",
                       "col_redirect_url" =>"url",
                       "col_filter"=>false
                   ],
                   [
                       "col_name" =>"Tipo",
                       "col_data" =>"type",
                       "col_translate"=>"import",
                       "col_filter"=>false
                   ],
           ],

             "table_actions"=>[

                                "customs"=>[
                                            [
                                               "redirect"=>[
                                                           "route"=>"v1.admin.client.import-details.client",
                                                           "binding"=>"import"
                                                     ],
                                                   "icon"=>"fas fa-search",
                                                   "tooltip_title"=>"Detalles",
                                                   "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_SHOW],
                                             ],


                                ]
                                ],

                                            /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                            _edit_button=>{ruta para redireccionar a edicion}
                                            _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                              ]*/
           "table_rows"=>$data



       ])

    @if($view_header??true)

</div>
@endif
