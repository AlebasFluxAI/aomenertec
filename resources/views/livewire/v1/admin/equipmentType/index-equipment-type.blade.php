<div>
    @section("header") {{--extended app.blade--}}
    @endsection
    @include("partials.v1.title",[
            "second_title"=>"de equipos",
            "first_title"=>"Tipos"
        ])


    @include("partials.v1.table_nav",
           ["nav_options"=>[
                      ["button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Crear nuevo",
                      "icon"=>"fa-solid fa-plus",
                      "target_route"=>"administrar.v1.equipos.tipos.agregar",
                      ],

                  ]
          ])
    @include("partials.v1.table.primary-table",[
               "table_headers"=>["ID"=>"id",
                                 "Nombre"=>"type",
                                 "Descripción"=>"description",



                ],
                 "table_actions"=>[
                                   "edit"=>"edit",
                                   "details"=>"details",
                                    "customs"=>[
                                                    [
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
