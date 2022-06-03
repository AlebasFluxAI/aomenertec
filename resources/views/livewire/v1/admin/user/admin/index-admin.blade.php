<div>
    @section("header") {{--extended app.blade--}}

    @endsection

    @include("partials.v1.title",[
          "second_title"=>"de administradores",
          "first_title"=>"Listado"
      ])





    @include("partials.v1.table_nav",
           ["nav_options"=>[
                      ["button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Crear nuevo",
                      "button_icon"=>"fa-solid fa-plus",
                    "target_route"=>"administrar.v1.usuarios.admin.agregar",
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
                       "col_name" =>"Apellido",
                       "col_data" =>"last_name",
                       "col_filter"=>true
                   ],
                   [
                       "col_name" =>"Correo electronico",
                       "col_data" =>"email",
                       "col_filter"=>true
                   ],
                   [
                       "col_name" =>"Telefono",
                       "col_data" =>"phone",
                       "col_filter"=>true
                   ],
                   [
                       "col_name" =>"Identificacion",
                       "col_data" =>"identification",
                       "col_filter"=>true
                   ],


                ],
                 "table_actions"=>[
                                    "details"=>"details",
                                    "edit"=>"edit",
                                    "customs"=>[
                                        [
                                                "function"=>"delete",
                                                "conditional"=>"conditionalDelete",
                                                "icon"=>"fas fa-trash",
                                                "tooltip_title"=>"Eliminar"
                                        ],
                                        [
                                           "redirect"=>[
                                                       "route"=>"administrar.v1.usuarios.admin.agregar_tipos_equipo",
                                                       "binding"=>"admin"
                                                 ],
                                               "icon"=>"fas fa-computer",
                                               "tooltip_title"=>"Asociar tipos de equipos",
                                         ],
                                            [
                                           "redirect"=>[
                                                       "route"=>"administrar.v1.usuarios.admin.agregar_equipos",
                                                       "binding"=>"admin"
                                                 ],
                                               "icon"=>"fas fa-laptop-medical",
                                               "tooltip_title"=>"Asociar equipos",
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

