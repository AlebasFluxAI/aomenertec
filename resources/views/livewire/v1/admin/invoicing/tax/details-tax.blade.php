@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Detalles de",
            "second_title"=>"impuesto"
        ])

    {{--optiones de cabecera de formulario--}}
    @include("partials.v1.table_nav",
         ["mt"=>4,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.facturacion.impuestos.listado",
                    ],

                ]
        ])

    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Detalles",

                                                ],

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
                                                                             "key"=>"Descripcion",
                                                                             "value"=>$model->description

                                                                         ],
                                                                         [
                                                                             "key"=>"Percetage",
                                                                             "value"=>$model->percentage

                                                                         ],
                                                                         ]
                                                            ]
                                                ],

                                                ]
         ])


</div>
