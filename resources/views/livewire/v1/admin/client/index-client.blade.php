<div>
    <section class="login">
        @section("header") {{--extended app.blade--}}

        @endsection

        @include("partials.v1.title",[
              "second_title"=>"de clientes",
              "first_title"=>"Listado"
          ])


        <div class="contenedor-grande">
            @include("partials.v1.table_nav",
                   ["nav_options"=>[
                              ["button_align"=>"right",
                              "click_action"=>"",
                              "button_content"=>"Crear nuevo",
                              "icon"=>"fa-solid fa-plus",
                              "target_route"=>"v1.admin.client.add.client",
                              ],

                          ]
                  ])

            @include("partials.v1.table.primary-table",[
                       "table_headers"=>["ID"=>"id",
                                         "Codigo"=>"code",
                                         "Identificación"=>"identification",
                                         "Nombre"=>"name",
                                         "Email"=>"email",


                        ],
                         "table_actions"=>[
                                            "details"=>"details",
                                            "edit"=>"edit",
                                            "delete"=>"delete"
                                            ],

                                                        /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                                        _edit_button=>{ruta para redireccionar a edicion}
                                                        _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                                          ]*/
                       "table_rows"=>$clients

                   ])
        </div>
    </section>
</div>
