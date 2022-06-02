<div>
    <section class="login">
        @section("header") {{--extended app.blade--}}

        @endsection

        @include("partials.v1.title",[
              "second_title"=>"de equipos",
              "first_title"=>"Listado"
          ])


        <div class="contenedor-grande">
            @include("partials.v1.table_nav",
                   ["nav_options"=>[
                              ["button_align"=>"right",
                              "click_action"=>"",
                              "button_content"=>"Crear nuevo",
                              "icon"=>"fa-solid fa-plus",
                              "target_route"=>"administrar.v1.equipos.agregar",
                              ],

                          ]
                  ])

            @include("partials.v2.table.primary-table",[
                       "table_headers"=>[
                               [
                                   "col_name" =>"ID",
                                   "col_data" =>"id",
                                   "col_filter"=>false
                               ],
                               [
                                   "col_name" =>"Nombre",
                                   "col_data" =>"equipmentType.type",
                                   "col_filter"=>false
                               ],
                               [
                                   "col_name" =>"Descripcion",
                                   "col_data" =>"description",
                                   "col_filter"=>false
                               ],
                               [
                                   "col_name" =>"Asignado",
                                   "col_data" =>"assigned",
                                   "col_filter"=>false,
                                   "col_type"=>\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN
                               ],
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
                       "table_rows"=>$data

                   ])
        </div>
    </section>
</div>

