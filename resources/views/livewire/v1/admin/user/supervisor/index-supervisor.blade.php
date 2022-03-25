<div>
    @section("header") {{--extended app.blade--}}

    @endsection

    @include("partials.v1.title",[
          "second_title"=>"de supervisores",
          "first_title"=>"Listado"
      ])



    @include("partials.v1.table_nav",
           ["nav_options"=>[
                      ["button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Crear nuevo",
                      "icon"=>"fa-solid fa-plus",
                      "target_route"=>"administrar.v1.usuarios.supervisores.agregar",
                      ],

                  ]
          ])

    @include("partials.v1.table.primary-table",[
               "table_headers"=>["ID"=>"id",
                                 "Nombre"=>"name",
                                 "Apellido"=>"last_name",
                                 "Correo electronico"=>"email",
                                 "Telefono"=>"phone",
                                  "Operador de red"=>"networkOperator.name",


                ],
                 "table_actions"=>[
                                    "details"=>"details",
                                    "edit"=>"edit",
                                    ],
                                                /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                                _edit_button=>{ruta para redireccionar a edicion}
                                                _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                                  ]*/
               "table_rows"=>$data

           ])
</div>

