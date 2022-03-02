<div class="login">
    @section("header") {{--extended app.blade--}}
    @include("partials.v1.title",[
            "second_title"=>"de equipos",
            "first_title"=>"Listado"
        ])

    @endsection


    @include("partials.v1.table_nav",
           ["nav_options"=>[
                      ["button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Crear nuevo",
                      "icon"=>"fa-solid fa-plus",
                      "target_route"=>"administrar.equipos.agregar",
                      ],

                  ]
          ])
    @include("partials.v1.table.primary-table",[
               "table_headers"=>["ID"=>"id",
                                 "Nombre"=>"name",
                                 "Descripcion"=>"description",
                                 "Tipo de equipo"=>"equipment_type.type",


                ],
                 "table_actions"=>[
                                   "edit"=>"editEquipment",
                                   "delete"=>"deleteEquipment"
                                    ],
                                                /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                                _edit_button=>{ruta para redireccionar a edicion}
                                                _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                                  ]*/
               "table_rows"=>$equipments

           ])

</div>
