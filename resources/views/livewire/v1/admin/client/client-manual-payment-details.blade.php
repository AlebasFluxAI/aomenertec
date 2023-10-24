<div>
    <div class="login">

        @include("partials.v1.title",[
              "second_title"=>"factura ".$model->code,
              "first_title"=>"Pago registrado"
          ])

        @include("partials.v1.table_nav",
              ["mt"=>2,
              "nav_options"=>[
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
                                                    "title"=>"Pago manual registrado",

                                                ],

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v1.table.primary-details-table",
                                                    "view_values"=>  [
                                                                        "table_info"=>[
                                                                         [
                                                                             "key"=>"Id",
                                                                             "value"=>$register->id
                                                                         ],
                                                                         [
                                                                             "key"=>"Metodo de pago",
                                                                             "value"=>__("payment_method.".$register->payment_method)
                                                                         ],
                                                                         [
                                                                             "key"=>"Referencia de pago",
                                                                             "value"=>$register->reference
                                                                         ],
                                                                         [
                                                                             "key"=>"Banco",
                                                                             "value"=>$register->bank
                                                                         ],
                                                                         [
                                                                             "key"=>"Otro medio de pago",
                                                                             "value"=>$register->other_method
                                                                         ],
                                                                         [
                                                                             "key"=>"Total",
                                                                             "value"=>$register->total
                                                                         ],
                                                                         [
                                                                             "key"=>"Evidencia",
                                                                             "type"=>"image",
                                                                             "value"=>$register->evidence?$register->evidence->url:""
                                                                         ],

                                                                         ]
                                                            ]
                                                ],

                                                ]
         ])

    </div>
