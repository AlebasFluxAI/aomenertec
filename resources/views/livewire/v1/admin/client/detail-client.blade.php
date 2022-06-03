@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"Cliente"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"v1.admin.client.list.client",
                    ],

                ]
        ])


    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],
                                                [
                                                    "title"=>"Direcion",
                                                ],
                                                [
                                                    "title"=>"Equipos",
                                                ]
                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>  [
                                                                        "table_info"=>[
                                                                         [
                                                                             "key"=>"Id",
                                                                             "value"=>$client->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Codigo",
                                                                             "value"=>$client->code
                                                                         ],
                                                                         [
                                                                             "key"=>"Nombre",
                                                                             "value"=>$client->name
                                                                         ],
                                                                         [
                                                                             "key"=>"Email",
                                                                             "value"=>$client->email
                                                                         ],
                                                                         [
                                                                             "key"=>"Operador de red",
                                                                             "value"=>$client->networkOperator->id. "- ". $client->networkOperator->name
                                                                         ],


                                                                     ]
                                                            ]
                                                ],
                                          [
                                                   "view_name"=>"partials.v1.table.primary-table",
                                                   "view_values"=>[
                                                                        "table_pageable"=>false,
                                                                       "table_headers"=>[
                                                                                         "ID"=>"id",
                                                                                         "Direccion"=>"address",
                                                                                         "Pais"=>"country",
                                                                                         "Departamento"=>"state",
                                                                                         "Ciudad"=>"city",
                                                                                         "Latitude"=>"latitude",
                                                                                         "Longitude"=>"longitude",
                                                                                         "Codigo postal"=>"postal_code"

                                                                        ],
                                                                        "table_actions"=>[
                                                                            "customs"=>[
                                                                                [
                                                                                   "popup"=>[
                                                                                               "modal_title"=>"Ubicación del cliente",
                                                                                               "view_name"=>"partials.v1.map_pin",
                                                                                               "view_data"=>[
                                                                                                   "latitude"=>$client->addresses->first()?$client->addresses->first()->latitude:null,
                                                                                                   "longitude"=>$client->addresses->first()?$client->addresses->first()->longitude:null,
                                                                                             ],
                                                                                   ],
                                                                                ]
                                                                            ],
                                                                         ],

                                                                       "table_rows"=>$client->addresses,
                                                                   ],


                                                ],
                                               [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>
                                                            ["table_info" => $equipment]

                                                ],

                                        ]
         ])


</div>

