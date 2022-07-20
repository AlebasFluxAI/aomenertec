<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

        @include("partials.v1.title",[
                "first_title"=>"Super administrador",
                "second_title"=>$model->user->name
            ])


    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.tab.v1.tab",[

                           "tab_titles"=>[
                                               [
                                                   "title"=>"Mis datos",

                                               ],
                                                [
                                                   "title"=>"Administradores",

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
                                                                            "key"=>"Apellido",

                                                                            "value"=>$model->last_name
                                                                        ],
                                                                        [
                                                                            "key"=>"Correo electronico",

                                                                            "value"=>$model->email
                                                                        ],
                                                                        [
                                                                            "key"=>"Telefono",

                                                                            "value"=>$model->phone
                                                                        ],


                                                                    ]
                                                           ],


                                               ],
                                               [
                                                  "view_name"=>"livewire.v1.admin.user.admin.index-admin",
                                                  "view_values"=>[
                                                      "data"=>$admins,
                                                      "table_pageable"=>false,
                                                      "admin_conditional_delete"=>"deleteAdminConditional",
                                                      "table_class_container"=>"",
                                                      "view_header"=>false,
                                                      "col_filter"=>false
                                                   ],
                                               ],
                                   ]
        ])

    @include("partials.v1.table_nav",
      ["mt"=>2,"nav_options"=>[
                 ["button_align"=>"right",
                 "click_action"=>"",
                 "button_content"=>"Cerrar sesión",
                 "button_icon"=>"fa-solid fa-right-from-bracket",
                 "target_route"=>"logout",
                 ],
             ]
     ])


</div>
