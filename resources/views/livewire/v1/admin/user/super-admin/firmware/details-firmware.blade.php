@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Firmware",
            "second_title"=>$model->name
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.superadmin.firmware.listado",
                    ],

                ]
        ])
    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Firmware",

                                                ]

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>  [
                                                                        "table_info"=>[
                                                                         [
                                                                             "key"=>"Id",
                                                                             "value"=>$model->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Nombre",
                                                                             "value"=>$model->name
                                                                         ],
                                                                         [
                                                                             "key"=>"Versión",
                                                                             "value"=>$model->version
                                                                         ],
                                                                         [
                                                                             "key"=>"Descripción",
                                                                             "value"=>$model->description
                                                                         ],
                                                                         [
                                                                             "key"=>"path",
                                                                             "value"=>$model->evidence()->path
                                                                         ]

                                                                     ]
                                                            ]
                                                ]
                                                                                        ]
         ])


</div>
