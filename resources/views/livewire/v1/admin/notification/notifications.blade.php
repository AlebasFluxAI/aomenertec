<div class="login">
    @section("header")
        {{--extended app.blade--}}

    @endsection

    @include("partials.v1.title",[
          "second_title"=>"de notificaciones",
          "first_title"=>"Listado"
      ])


    @include("partials.v2.table.primary-table",[
                "row_color_function"=>"notificationColor",
               "table_headers"=>[
                   [
                       "col_name" =>"Notificacion",
                       "col_data" =>"data",
                       "col_filter"=>false,
                       "col_type"=>\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_ARRAY_CLIENT_NOTIFICATION,
                       "col_array_data"=>"message"
                   ],
                ],
                 "table_actions"=>[
                                    "customs"=>[
                                                    [
                                                        "conditionalModel"=>"isRead",
                                                        "function"=>"markAsRead",
                                                        "icon"=>"fas fa-bookmark",
                                                        "tooltip_title"=>"Marcar como leido",
                                                        "model_id"=>"id"
                                                    ],
                                                    [

                                                        "function"=>"deleteNotification",
                                                        "icon"=>"fas fa-trash",
                                                        "tooltip_title"=>"Archivar",
                                                        "model_id"=>"id"
                                                    ]

                                                ]
                                    ],
                                                /* Le dice al componente tabla las acciones que tendra la columna de acciones en la tabla [
                                                _edit_button=>{ruta para redireccionar a edicion}
                                                _delete_button => {boton de borrado, siempre tomando como identificador la primera colunma de la tabla - ID}
                                                  ]*/
               "table_rows"=>$data

           ])


</div>



