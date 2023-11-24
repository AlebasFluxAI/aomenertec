<div class="login">
    @section("header")
        {{--extended app.blade--}}

    @endsection


    @include("partials.v1.title",[
          "second_title"=>$model->code,
          "first_title"=>"Historico de cambios de equipo Pqr"
      ])

    @include("partials.v1.table_nav",
             ["mt"=>4,"nav_options"=>[
                        ["button_align"=>"right",
                        "click_action"=>"",
                        "button_icon"=>"fas fa-list",
                        "button_content"=>"Ver listado",
                        "target_route"=>"administrar.v1.peticiones.listado",
                        ],
                        [
                        "button_align"=>"right",
                        "button_type"=>"dropdown",
                        "button_icon"=>"fas fa-gear",
                        "button_content"=>"Acciones",
                        "button_options"=>$model->navigatorDropdownOptions()
                        ]

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
              "col_name" =>"Cliente",
              "col_data" =>"client.name",
              "col_filter"=>false
          ],
          [
              "col_name" =>"Equipo reemplazado",
              "col_data" =>"beforeEquipment.getNameSerial",
              "col_filter"=>false,
              "col_data_function"=>true,
          ],
          [
              "col_name" =>"Equipo actual",
              "col_data" =>"equipment.getNameSerial",
              "col_data_function"=>true,
              "col_filter"=>false
          ],
           [
              "col_name" =>"Fecha de reemplazo",
              "col_data" =>"getCreationDate",
              "col_data_function"=>true,
              "col_filter"=>false
          ],
          [
              "col_name" =>"Reemplazado por",
              "col_data" =>"assignedBy.name",
              "col_filter"=>false
          ],
           ],
          "table_rows"=>$model->equipmentChangeHistorical


      ])

</div>
