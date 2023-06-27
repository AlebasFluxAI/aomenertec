<div class="login">

    @include("partials.v1.title",[
          "second_title"=>"mensual",
          "first_title"=>"Generador de reportes"
      ])

    <form wire:submit.prevent="simulateFee" id="formulario-simulateFee" class="needs-validation" role="form">
        {{--
        <div> &nbsp;&nbsp; <strong> Agregar manualmente</strong></div>
        --}}
        <div class="row ">

            @include("partials.v2.table.primary-table",[
            "table_headers"=> [[
                "col_name" => "Mes",
                "col_data" => "month",
                "col_filter" => false,
                "col_translate"=>"months"
            ]],
           "table_actions"=>[

                              "customs"=>[
                                                  [
                                                        "function"=>"generateReport",
                                                        "icon"=>"fas fa-file",
                                                        "tooltip_title"=>"Descargar reporte",

                                                      ],
                                                ],
                                           ],
         "table_rows"=>$months

     ])


        </div>
    </form>
</div>

