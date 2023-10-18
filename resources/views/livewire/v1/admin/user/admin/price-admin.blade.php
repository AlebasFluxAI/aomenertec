<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Configuracion",
            "second_title"=>"administrador"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>4,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.admin.listado",
                    ],

                ]
        ])

    @include("partials.v1.tab.v1.tab",[

                       "tab_titles"=>[
                                           [
                                               "title"=>"Configuracion inicial",

                                           ],
                                           [
                                               "title"=>"Configuracion de cobro anual a administradores",

                                           ],

                                      ],

                       "tab_contents"=>[


                                           [
                                                  "view_name"=>"livewire.v1.admin.user.admin.channels-admin",
                                                  "view_values"=>  []
                                           ],

                                         [
                                                    "view_name"=>"livewire.v1.admin.user.admin.annually-cost-config",
                                                    "view_values"=>  []
                                         ]



                              ]
    ])

</div>
