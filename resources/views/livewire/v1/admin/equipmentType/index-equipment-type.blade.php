<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection
    @include("partials.v1.title",[
            "second_title"=>"de equipos",
            "first_title"=>"Tipos"
        ])


    @include("partials.v1.table_nav",
           ["mt"=>2,"nav_options"=>[
                      [
                      "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_TYPE_CREATE],
                      "button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Crear nuevo",
                      "icon"=>"fa-solid fa-plus",
                      "target_route"=>"administrar.v1.equipos.tipos.agregar",
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
                               "col_data" =>"type",
                               "col_filter"=>false
                           ],
                              [
                               "col_name" =>"Descripcion",
                               "col_data" =>"description",
                               "col_filter"=>false
                           ],



                ],
                 "table_actions"=>[

                                        "customs"=>[
                                                 [

                                                         "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_TYPE_SHOW],
                                                     "function"=>"details",
                                                        "icon"=>"fas fa-search",
                                                        "tooltip_title"=>"Detalles"
                                                ],
                                                [

                                                         "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_TYPE_EDIT],
                                                         "function"=>"edit",
                                                        "icon"=>"fas fa-pencil",
                                                        "tooltip_title"=>"Editar"
                                                ],
                                                [
                                                     "permission"=>[\App\Http\Resources\V1\Permissions::EQUIPMENT_TYPE_DELETE],
                                                        "function"=>"delete",
                                                        "conditional"=>"conditionalDelete",
                                                        "icon"=>"fas fa-trash",
                                                        "tooltip_title"=>"Eliminar"
                                                ],
                                            ],
                                    ],

               "table_rows"=>$data

           ])

</div>
