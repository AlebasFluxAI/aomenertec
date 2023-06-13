<div class="login">

    @include("partials.v1.title",[
          "second_title"=>"mensual",
          "first_title"=>"Generador de reportes"
      ])

    <form wire:submit.prevent="simulateFee" id="formulario-simulateFee" class="needs-validation" role="form">
        {{--
        <div> &nbsp;&nbsp; <strong> Agregar manualmente</strong></div>
        --}}
        @include("partials.v1.divider_title",["title"=>"Generador de reportes"])

        <div class="row ">
            @include("partials.v1.form.form_input_icon",[
                        "mt"=>0,
                        "tooltip_title"=>"El generador de reportes permite exportar un archivo Excel con el resumen de los consumos generados por los clientes asignados a su administrador",
                        "input_model"=>"date_range_simulator",
                        "icon_class"=>"fas fa-calendar",
                        "updated_input"=>"defer",
                        "input_label"=>"Seleccione rango de fechas",
                        "col_with"=>12,
                        "input_type"=>"text",
                        "input_name"=>"datetime_simulator",
                        "autocomplete"=> "off",
            ])


            @include("partials.v1.form.form_submit_button",[
            "button_align"=>"right" ,
            "function"=>"generateReport",
            "button_icon"=>"fas fa-file-excel",
            "button_content"=>"Generar reporte"
            ])

        </div>
    </form>
</div>

