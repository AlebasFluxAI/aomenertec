<div>
    @section("header")
        {{--extended app.blade--}}
    @endsection
    @include("partials.v1.title",[
            "second_title"=>"de alertas",
            "first_title"=>"Tipos"
        ])


    @include("partials.v1.table_nav",
           ["nav_options"=>[
                      ["button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Crear nueva",
                      "icon"=>"fa-solid fa-plus",
                      "target_route"=>"administrar.v1.equipos.alertas.tipos.agregar",
                      ],

                  ]
          ])
    @include("partials.v1.table.primary-table",[
               "table_headers"=>["ID"=>"id",
                                 "Nombre"=>"name",
                                 "Valor de alerta"=>"value",
                                 "Unidad"=>"unit"


                ],
                 "table_actions"=>[
                                   "edit"=>"edit",
                                   "delete"=>"delete",
                                   "details"=>"details",
                                    ],

               "table_rows"=>$alertTypes

           ])

</div>
