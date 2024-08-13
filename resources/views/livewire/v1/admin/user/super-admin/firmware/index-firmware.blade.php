<div class="login">
    @section("header")
        {{--extended app.blade--}}

    @endsection

    @include("partials.v1.title",[
          "second_title"=>"Firmwares",
          "first_title"=>"Listado"
      ])



    @include("partials.v1.table_nav",
           ["mt"=>2,"nav_options"=>[
                      [
                      "button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Crear nuevo",
                      "icon"=>"fa-solid fa-plus",
                      "target_route"=>"administrar.v1.usuarios.superadmin.firmware.agregar",
                      ],

                  ]
          ])

    @include("partials.v2.table.primary-table",[
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
                       "col_name" =>"Versión",
                       "col_data" =>"version",
                       "col_filter"=>true
                   ],
                   [
                       "col_name" =>"Descripción",
                       "col_data" =>"description",
                       "col_filter"=>true
                   ]
                ],
                 "table_actions"=>[

                                     "customs"=>[
                                            [
                                               "redirect"=>[
                                                           "route"=>"administrar.v1.usuarios.superadmin.firmware.detalles",
                                                           "binding"=>"firmware"
                                                     ],
                                                   "icon"=>"fas fa-search",
                                                   "tooltip_title"=>"Detalles",
                                             ],
                                            [
                                               "redirect"=>[
                                                           "route"=>"administrar.v1.usuarios.superadmin.firmware.editar",
                                                           "binding"=>"firmware"
                                                     ],
                                                   "icon"=>"fas fa-pencil",
                                                   "tooltip_title"=>"Editar",
                                             ],
                                              [
                                                    "function"=>"downloadFile",
                                                    "icon"=>"fas fa-download",
                                                    "tooltip_title"=>"Descargar",
                                              ],
                                              [
                                                    "function"=>"otaUpload",
                                                    "icon"=>"fas fa-download",
                                                    "tooltip_title"=>"Cargar firmware",
                                              ],
                                              [
                                                    "function"=>"delete",
                                                    "icon"=>"fas fa-trash",
                                                    "tooltip_title"=>"Eliminar",
                                              ],

                                           ]
                                    ],

                                                /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                                _edit_button=>{ruta para redireccionar a edicion}
                                                _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                                  ]*/
               "table_rows"=>$data

           ])
</div>

