@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Generar factura de prueba",
            "second_title"=>""
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["mt"=>4,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"v1.admin.client.list.client",
                    ]

                ]
        ])


    <div class="contenedor-grande">
        <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
            <div class="row ">
                @include("partials.v1.divider_title",[
                                "title"=>"Datos del cliente  "
                        ]
                       )

                @include("partials.v1.form.form_input_icon",[
                      "input_model"=>"client.code",
                      "input_label"=>"Codigo de cliente",
                      "icon_class"=>"fas fa-code",
                      "placeholder"=>"Codigo de cliente",
                      "col_with"=>2,
                      "input_type"=>"text",
                      "required"=>true,
                      "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                   "input_model"=>"client.identification",
                   "input_label"=>"Identificación",
                   "icon_class"=>"fas fa-card",
                   "placeholder"=>"Identificación",
                   "col_with"=>2,
                   "input_type"=>"text",
                   "required"=>true,
                    "disabled"=>true
             ])
                @include("partials.v1.form.form_input_icon",[
                      "updated_input"=>"lazy",
                      "input_model"=>"client.name",
                      "input_label"=>"Nombre",
                      "icon_class"=>"fas fa-user",
                      "placeholder"=>"Nombre",
                      "col_with"=>4,
                      "input_type"=>"text",
                      "required"=>true,
                      "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                      "updated_input"=>"lazy",
                      "input_model"=>"client.stratum.acronym",
                      "input_label"=>"Estrato",
                      "icon_class"=>"fas fa-user",
                      "placeholder"=>"Estrato",
                      "col_with"=>2,
                      "input_type"=>"text",
                      "required"=>true,
                      "disabled"=>true
                ])


                @include("partials.v1.divider_title",[
                               "title"=>"Información de operador de red"
                       ]
                      )
                @include("partials.v1.form.form_input_icon",[
                                      "input_model"=>"network_operator.identification",
                                      "input_label"=>$network_operator->identification_type,
                                      "icon_class"=>"fas fa-code",
                                      "placeholder"=>$network_operator->identification_type,
                                      "col_with"=>2,
                                      "input_type"=>"text",
                                      "required"=>true,
                                      "disabled"=>true
                                ])
                @include("partials.v1.form.form_input_icon",[
                      "input_model"=>"network_operator.name",
                      "input_label"=>"Nombre",
                      "icon_class"=>"fas fa-user",
                      "placeholder"=>"Nombre",
                      "col_with"=>5,
                      "input_type"=>"text",
                      "required"=>true,
                      "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                      "input_model"=>"network_operator.last_name",
                      "input_label"=>"Apellido",
                      "icon_class"=>"fas fa-user",
                      "placeholder"=>"Apellido",
                      "col_with"=>5,
                      "input_type"=>"text",
                      "required"=>true,
                      "disabled"=>true
                ])


                @include("partials.v1.divider_title",[
                                                         "title"=>"Componente costo unitario"
                                                 ]
                                                )
                @include("partials.v1.form.form_input_icon",[
                         "input_model"=>"fees.generation",
                         "input_label"=>"Generación (G)",
                         "icon_class"=>"fas fa-screwdriver-wrench",
                         "placeholder"=>"Generación",
                         "col_with"=>3,
                         "input_type"=>"number",
                         "required"=>true,
                         "disabled"=>true

                ])
                @include("partials.v1.form.form_input_icon",[
                         "input_model"=>"fees.transmission",
                         "input_label"=>"Transmisión (T)",
                         "icon_class"=>"fas fa-screwdriver-wrench",
                         "placeholder"=>"Transmisión",
                         "col_with"=>3,
                         "input_type"=>"number",
                         "required"=>true,
                         "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                         "input_model"=>"fees.distribution",
                         "input_label"=>"Distribución (D)",
                         "icon_class"=>"fas fa-screwdriver-wrench",
                         "placeholder"=>"Distribución",
                         "col_with"=>3,
                         "input_type"=>"number",
                         "required"=>true,
                         "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                         "input_model"=>"fees.commercialization",
                         "input_label"=>"Comercialización (Cv)",
                         "icon_class"=>"fas fa-screwdriver-wrench",
                         "placeholder"=>"Comercialización",
                         "col_with"=>3,
                         "input_type"=>"number",
                         "required"=>true,
                         "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                         "input_model"=>"fees.lost",
                         "input_label"=>"Perdidas (P)",
                         "icon_class"=>"fas fa-screwdriver-wrench",
                         "placeholder"=>"Perdidas",
                         "col_with"=>3,
                         "input_type"=>"number",
                         "required"=>true,
                         "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                         "input_model"=>"fees.restriction",
                         "input_label"=>"Restricción (R)",
                         "icon_class"=>"fas fa-screwdriver-wrench",
                         "placeholder"=>"Restricción",
                         "col_with"=>3,
                         "input_type"=>"number",
                         "required"=>true,
                         "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                         "input_model"=>"fees.unit_cost",
                         "input_label"=>"Costo unitario (CU)",
                         "icon_class"=>"fas fa-screwdriver-wrench",
                         "placeholder"=>"Costo unitario",
                         "col_with"=>3,
                         "input_type"=>"number",
                         "required"=>true,
                         "disabled"=>true
                ])
                @include("partials.v1.form.form_input_icon",[
                         "input_model"=>"fees.optional_fee",
                         "input_label"=>"Costo opcional",
                         "icon_class"=>"fas fa-screwdriver-wrench",
                         "placeholder"=>"Costo opcional",
                         "col_with"=>3,
                         "input_type"=>"number",
                         "disabled"=>true
                ])

                @include("partials.v1.divider_title",[
                                                    "title"=>"Periodo de facturación"
                                            ]
                                           )
                @include("partials.v1.form.form_input_icon",
   ["input_model"=>"month",
   "updated_input"=>"lazy",
   "input_field"=>"",
   "input_label"=>"Seleccione el mes a facturar",
   "input_type"=>"select",
   "icon_class"=>null,
   "placeholder"=>"Mes de tarifa",
   "col_with"=>4,
   "required"=>true,
   "offset"=>'',
   "data_target"=>'',
   "placeholder_clickable"=>false,
   "input_rows"=>0,
   "select_options"=>$months,
   "select_option_value"=>"value",
   "select_option_view"=>"key",
   ])

                @include("partials.v1.form.form_input_icon",
             ["input_model"=>"year",
             "updated_input"=>"lazy",
             "input_field"=>"",
             "input_label"=>"Seleccione un año a facturar",
             "input_type"=>"select",
             "icon_class"=>null,
             "placeholder"=>"Mes de tarifa",
             "col_with"=>4,
             "required"=>true,
             "offset"=>'',
             "data_target"=>'',
             "placeholder_clickable"=>false,
             "input_rows"=>0,
             "select_options"=>$years,
             "select_option_value"=>"value",
             "select_option_view"=>"key",
             ])
                @error('date_picker_error') <span class="error">{{ $message }}</span> @enderror


                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>"Generar factura"
                          ])
            </div>
        </form>
        <div wire:ignore id="chart"></div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('livewire:load', function () {
        var options = {
            chart: {
                id: 'chart',
                type: 'bar',
                height: '400px',
                animations: {
                    enabled: false,
                }
            },
            series: [{
                name: 'Consumo kWh',
                data: []
            }],
            yaxis: {
                show: true,
                labels: {
                    show: true,
                    style: {
                        fontSize: '14px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'normal',
                        cssClass: 'apexcharts-yaxis-label',
                    },
                }
            },
            xaxis: {
                categories: [],
                labels: {
                    show: true,
                    style: {
                        fontSize: '14px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 'normal',
                        cssClass: 'apexcharts-xaxis-label',
                    },
                }
            },
            title: {
                text: 'Historico de consumos (kWh)',
                align: 'center',
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold',
                    fontFamily: 'sans-serif',
                    color: '#000'
                },
            },

            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '14px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 'normal',
                }
            },

        }

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render()


    @this.on('setChartData', (e) => {

        ApexCharts.exec('chart', "updateOptions", {
            series: [{
                data: e.series
            }],
            xaxis: {
                categories: e.x_axis
            },
        }).then(function () {
            chart.dataURI().then(function (uri) {
            @this.emit('setImageChart', uri)
            });
        });


    })


    })
</script>

