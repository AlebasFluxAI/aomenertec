{{-- ============================================================
     BaseLine Chart — FluxAI redesign
     Compara dos rangos de tiempo y cuantifica ahorro / sobreconsumo.
     La lógica del componente PHP no cambia; solo el layout visual.
     ============================================================ --}}


<div class="flux-baseline"
     x-data="{
        ref: '—',
        cmp: '—',
        pct: 0,
        diff: 0,
        isSaving: true,
        hasData: false,
        fmt(v) { return (v === null || v === undefined || isNaN(v)) ? '—' : Number(v).toFixed(2); }
     }"
     x-on:baseline-updated.window="
        hasData = $event.detail.hasData;
        ref = fmt($event.detail.ref);
        cmp = fmt($event.detail.cmp);
        diff = fmt($event.detail.diff);
        pct = Math.abs($event.detail.pct).toFixed(2);
        isSaving = $event.detail.isSaving;
     ">

    {{-- Style embebido dentro del root <div class="flux-baseline">
         para respetar single-root de Livewire 2. --}}
    <style>
        .flux-baseline { padding-top: 1rem; }
    
        /* Reutiliza flux-dashboard-controlbar + subheader del dashboard unificado.
           Si el navegador no cargó esos estilos (navegación directa), aquí hay
           respaldo mínimo. */
        .flux-baseline .flux-dashboard-controlbar {
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: #fff; border: 1px solid #E4E9F0;
            border-radius: 10px; margin-bottom: 1.25rem;
        }
        .flux-baseline .flux-dashboard-title {
            font-family: 'Poppins', system-ui, sans-serif;
            font-weight: 600; font-size: 0.98rem;
            color: #0044A4;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .flux-baseline .flux-dashboard-title i { font-size: 1.05rem; color: #00C781; }
    
        /* Cards de resumen */
        .flux-bl-card {
            position: relative;
            background: #fff;
            border: 1px solid #E4E9F0;
            border-radius: 12px;
            padding: 1rem 1.15rem 1rem 1.35rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            overflow: hidden;
            transition: transform 0.22s ease, box-shadow 0.22s ease;
            height: 100%;
        }
        .flux-bl-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0,68,164,0.10);
        }
        .flux-bl-card::before {
            content: ''; position: absolute; top: 0; left: 0; bottom: 0;
            width: 4px; background: #0044A4;
        }
        .flux-bl-card--comparison::before { background: #4A5568; }
        .flux-bl-card--savings::before { background: #00C781; }
        .flux-bl-card--overconsumption::before { background: #E53935; }
    
        .flux-bl-card__header {
            display: flex; align-items: center; gap: 0.55rem;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.72rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: #4A5568;
            margin-bottom: 0.4rem;
        }
        .flux-bl-card__header i {
            width: 28px; height: 28px; border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
            background: rgba(0,68,164,0.08); color: #0044A4;
        }
        .flux-bl-card--comparison .flux-bl-card__header i {
            background: rgba(74,85,104,0.10); color: #4A5568;
        }
        .flux-bl-card--savings .flux-bl-card__header i {
            background: rgba(0,199,129,0.12); color: #00A56B;
        }
        .flux-bl-card--overconsumption .flux-bl-card__header i {
            background: rgba(229,57,53,0.12); color: #B71C1C;
        }
    
        .flux-bl-card__value {
            display: flex; align-items: baseline; gap: 0.35rem;
            line-height: 1.15;
        }
        .flux-bl-card__number {
            font-family: 'Poppins', system-ui, sans-serif;
            font-weight: 700; font-size: 1.65rem;
            color: #1A202C;
            letter-spacing: -0.01em;
        }
        .flux-bl-card--savings .flux-bl-card__number { color: #00A56B; }
        .flux-bl-card--overconsumption .flux-bl-card__number { color: #B71C1C; }
        .flux-bl-card__unit {
            font-family: 'Inter', system-ui, sans-serif;
            font-weight: 500; font-size: 0.72rem; color: #4A5568;
            text-transform: uppercase; letter-spacing: 0.06em;
        }
        .flux-bl-card__hint {
            display: block;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 0.72rem; color: #A0AEC0;
            margin-top: 0.2rem;
        }
        .flux-bl-card__percent {
            display: inline-flex; align-items: center; gap: 0.25rem;
            margin-left: 0.45rem;
            padding: 0.1rem 0.45rem;
            border-radius: 999px;
            font-size: 0.7rem; font-weight: 600;
        }
        .flux-bl-card--savings .flux-bl-card__percent {
            background: rgba(0,199,129,0.12); color: #00A56B;
        }
        .flux-bl-card--overconsumption .flux-bl-card__percent {
            background: rgba(229,57,53,0.12); color: #B71C1C;
        }
    
        /* Form inputs row */
        .flux-baseline__form {
            background: #fff;
            border: 1px solid #E4E9F0;
            border-radius: 12px;
            padding: 0.5rem 1rem 0.75rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
    
        /* Chart container */
        .flux-baseline__chart {
            background: #fff;
            border: 1px solid #E4E9F0;
            border-radius: 12px;
            padding: 0.5rem 0.5rem 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .flux-baseline__chart-loading {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1rem;
            color: #4A5568; font-size: 0.85rem;
        }
    
        @media (max-width: 768px) {
            .flux-bl-card__number { font-size: 1.3rem; }
        }
    </style>


    {{-- ------------ Header ------------ --}}
    <div class="flux-dashboard-controlbar">
        <span class="flux-dashboard-title">
            <i class="fas fa-chart-column"></i>
            Comparativa BaseLine — ahorro vs sobreconsumo
        </span>
    </div>

    {{-- ------------ Summary cards (reactivos al chart) ------------ --}}
    <div class="row g-3 mb-2">
        <div class="col-md-4 col-sm-12 mb-3">
            <div class="flux-bl-card flux-bl-card--reference">
                <div class="flux-bl-card__header">
                    <i class="fas fa-bookmark"></i>
                    <span>Referencia</span>
                </div>
                <div class="flux-bl-card__value">
                    <span class="flux-bl-card__number" x-text="ref"></span>
                    <span class="flux-bl-card__unit" x-text="'{{ $chart_title }}'.replace(/.*\(/,'(').replace(/[^(]*/, '')"></span>
                </div>
                <span class="flux-bl-card__hint">Acumulado del período base</span>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 mb-3">
            <div class="flux-bl-card flux-bl-card--comparison">
                <div class="flux-bl-card__header">
                    <i class="fas fa-arrows-left-right"></i>
                    <span>Comparación</span>
                </div>
                <div class="flux-bl-card__value">
                    <span class="flux-bl-card__number" x-text="cmp"></span>
                    <span class="flux-bl-card__unit" x-text="'{{ $chart_title }}'.replace(/.*\(/,'(').replace(/[^(]*/, '')"></span>
                </div>
                <span class="flux-bl-card__hint">Acumulado del período actual</span>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 mb-3">
            <div class="flux-bl-card"
                 :class="isSaving ? 'flux-bl-card--savings' : 'flux-bl-card--overconsumption'">
                <div class="flux-bl-card__header">
                    <i :class="isSaving ? 'fas fa-leaf' : 'fas fa-bolt'"></i>
                    <span x-text="isSaving ? 'Ahorro' : 'Sobreconsumo'"></span>
                </div>
                <div class="flux-bl-card__value">
                    <span class="flux-bl-card__number" x-text="(diff === '—' ? '—' : (isSaving ? '' : '+') + diff)"></span>
                    <span class="flux-bl-card__percent" x-show="hasData">
                        <i :class="isSaving ? 'fas fa-arrow-trend-down' : 'fas fa-arrow-trend-up'"></i>
                        <span x-text="pct + '%'"></span>
                    </span>
                </div>
                <span class="flux-bl-card__hint"
                      x-text="isSaving
                              ? 'Comparación consume menos que referencia'
                              : 'Comparación consume más que referencia'"></span>
            </div>
        </div>
    </div>

    {{-- ------------ Formulario: rangos + variable + muestreo ------------ --}}
    <div class="row flux-baseline__form">
        @include("partials.v1.form.form_input_icon_button",[
                    "mt"=>4,
                    "input_model"=>"date_range_reference",
                    "icon_class"=>"fas fa-calendar",
                    "updated_input"=>"",
                    "placeholder"=>"Seleccione rango de fechas",
                    "col_with"=>6,
                    "input_type"=>"text",
                    "input_label"=>"Datos de referencia",
                    "input_name"=>"datetimes_baseline_reference",
                    "autocomplete"=> "off",
                    "button_name" => "Borrar",
                    "button_action"=> "restartDateRange"
       ])
        @include("partials.v1.form.form_input_icon_button",[
                    "mt"=>4,
                    "input_model"=>"date_range_result",
                    "icon_class"=>"fas fa-calendar",
                    "updated_input"=>"",
                    "placeholder"=>"Seleccione rango de fechas",
                    "col_with"=>6,
                    "input_type"=>"text",
                    "input_label"=>"Datos a comparar",
                    "input_name"=>"datetimes_baseline_result",
                    "autocomplete"=> "off",
                    "button_name" => "Borrar",
                    "button_action"=> "restartDateRange"
       ])
        @include("partials.v1.form.form_list",[
                     "col_with"=>4,
                     "mt"=> 4,
                     "mb"=>0,
                     "input_type"=>"text",
                     "list_model" => "variable_chart_id",
                     "list_default" => "Variable...",
                     "list_options" => [
                                            ['id'=>2, 'display_name'=>'Activa (kWh)'],
                                            ['id'=>14, 'display_name'=>'Reactiva Inductiva (kVArLh)'],
                                            ['id'=>10, 'display_name'=>'Reactiva Capacitiva (kVArCh)'],
                                                     ],
                     "list_option_value"=>"id",
                     "list_option_view"=>"display_name",
                     "list_option_title"=>"",
            ])
        @include("partials.v1.form.form_list",[
                     "col_with"=>2,
                     "mt"=>4,
                     "mb"=>0,
                     "input_type"=>"text",
                     "list_model" => "time_id_baseline",
                     "list_default" => "Muestreo...",
                     "list_options" => [
                                        ['id'=>2, 'display_name'=> 'Hora'],
                                        ['id'=>3, 'display_name'=> 'Dia'],
                                        ['id'=>4, 'display_name'=> 'Mes'],
                                       ],
                     "list_option_value"=>"id",
                     "list_option_view"=>"display_name",
                     "list_option_title"=>"",
            ])
    </div>

    {{-- ------------ Chart ------------ --}}
    <div class="flux-baseline__chart">
        <div class="flux-baseline__chart-loading" wire:loading>
            <i class="fas fa-circle-notch fa-spin"></i>
            <span>Actualizando gráfica...</span>
        </div>
        <div id="chart_baseline"
             x-data
             x-init="$nextTick(() => window.initBaseLineChart($wire))"></div>
    </div>

    <script>
        /* Paleta FluxAI para BaseLine */
        const FLUX_BL_COLORS = {
            reference: '#0044A4',    // azul FluxAI primary
            comparison: '#4A5568',   // gris neutro
            savings: '#00C781',      // verde FluxAI accent
            overconsumption: '#E53935'
        };

        function fluxBlDispatchUpdate({ refTotal, cmpTotal }) {
            const ref = Number(refTotal);
            const cmp = Number(cmpTotal);
            const hasData = !isNaN(ref) && !isNaN(cmp) && (ref !== 0 || cmp !== 0);
            const diff = cmp - ref;                      // positivo → sobreconsumo
            const isSaving = diff <= 0;
            const pct = ref === 0 ? 0 : (diff * 100 / ref);
            window.dispatchEvent(new CustomEvent('baseline-updated', {
                detail: { ref, cmp, diff, pct, isSaving, hasData }
            }));
        }

        window.initBaseLineChart = window.initBaseLineChart || function ($wire) {
            // Guard against double-init if the component remounts or x-init fires twice.
            var _el = document.querySelector('#chart_baseline');
            if (!_el || _el.__chartInitialized) return;
            _el.__chartInitialized = true;

            $('input[name="datetimes_baseline_reference"]').daterangepicker({
                applyButtonClasses: 'text-primary',
                timePicker: true,
                timePicker24Hour: true,
                locale: { format: 'YYYY-MM-DD HH:mm' }
            });
            $('input[name="datetimes_baseline_result"]').daterangepicker({
                applyButtonClasses: 'text-primary',
                timePicker: true,
                timePicker24Hour: true,
                locale: { format: 'YYYY-MM-DD HH:mm' }
            });

            var options = {
                chart: {
                    id: 'baseline_chart',
                    type: @js($chart_type),
                    height: '520px',
                    animations: { enabled: false },
                    toolbar: { show: true },
                    events: {
                        click: function (event, chartContext, config) {
                            var s0 = config.config.series[0].data[config.dataPointIndex];
                            var s1 = config.config.series[1].data[config.dataPointIndex];
                            if (s0 === undefined || s1 === undefined) return;
                            var delta = (s1 - s0).toFixed(2);
                            var saving = delta <= 0;
                            var text = (saving ? 'Ahorro: ' : 'Extra consumo: ') + Math.abs(delta);
                            var color = saving ? FLUX_BL_COLORS.savings : FLUX_BL_COLORS.overconsumption;
                            chart_baseline.addXaxisAnnotation({
                                x: config.globals.categoryLabels[config.dataPointIndex],
                                borderColor: color,
                                label: {
                                    text: text,
                                    borderColor: color,
                                    style: { color: '#fff', background: color, fontFamily: 'Inter, sans-serif' },
                                },
                            }, false);
                        }
                    }
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'center',
                    fontFamily: 'Inter, sans-serif',
                    fontWeight: 500,
                    markers: { radius: 4 }
                },
                colors: [FLUX_BL_COLORS.reference, FLUX_BL_COLORS.comparison],
                series: [],
                xaxis: {
                    categories: [],
                    labels: { style: { fontFamily: 'Inter, sans-serif', fontSize: '11px' } }
                },
                yaxis: {
                    labels: { style: { fontFamily: 'Inter, sans-serif', fontSize: '12px' } }
                },
                grid: { borderColor: '#E4E9F0', strokeDashArray: 3 },
                noData: { text: 'Sin datos', style: { fontFamily: 'Inter, sans-serif', fontSize: '14px' } },
                stroke: { curve: 'smooth', width: 2 },
                tooltip: { style: { fontFamily: 'Inter, sans-serif', fontSize: '12px' } }
            };

            var chart_baseline = new ApexCharts(document.querySelector("#chart_baseline"), options);
            chart_baseline.render();

            /* Render inicial con los datos que trae Livewire */
            var initialSeries = @js($series);
            if (initialSeries && initialSeries.length >= 2 && initialSeries[0].data && initialSeries[0].data.length) {
                var pos1 = Math.max.apply(null, initialSeries[0].data.filter(x => x !== null));
                var pos2 = Math.min.apply(null, initialSeries[1].data.filter(x => x !== null));
                if (!isFinite(pos1)) pos1 = 0;
                if (!isFinite(pos2)) pos2 = 0;
                var sumRef = initialSeries[0].data.reduce((a, b) => a + (b || 0), 0);
                var sumCmp = initialSeries[1].data.reduce((a, b) => a + (b || 0), 0);
                var isSaving = sumCmp <= sumRef;
                var diffColor = isSaving ? FLUX_BL_COLORS.savings : FLUX_BL_COLORS.overconsumption;
                var pct = sumRef === 0 ? 0 : ((sumCmp - sumRef) * 100 / sumRef);

                ApexCharts.exec('baseline_chart', "updateOptions", {
                    series: initialSeries,
                    xaxis: { categories: @js($x_axis) },
                    title: {
                        text: @js($chart_title),
                        align: 'center',
                        style: {
                            fontSize: '14px', fontWeight: '600',
                            fontFamily: 'Poppins, sans-serif', color: '#0044A4'
                        }
                    },
                    annotations: {
                        yaxis: [
                            {
                                y: pos1,
                                borderColor: FLUX_BL_COLORS.reference,
                                label: {
                                    borderColor: FLUX_BL_COLORS.reference,
                                    style: { color: '#fff', background: FLUX_BL_COLORS.reference, fontFamily: 'Inter, sans-serif', padding: { left: 6, right: 6, top: 3, bottom: 3 } },
                                    text: 'Σ ref: ' + sumRef.toFixed(2)
                                }
                            },
                            {
                                y: (pos1 + pos2) / 2,
                                borderColor: diffColor,
                                label: {
                                    borderColor: diffColor,
                                    style: { color: '#fff', background: diffColor, fontFamily: 'Inter, sans-serif', padding: { left: 6, right: 6, top: 3, bottom: 3 } },
                                    text: (isSaving ? 'Ahorro: ' : 'Sobreconsumo: ') + Math.abs(pct).toFixed(2) + '%'
                                }
                            },
                            {
                                y: pos2,
                                borderColor: FLUX_BL_COLORS.comparison,
                                label: {
                                    borderColor: FLUX_BL_COLORS.comparison,
                                    style: { color: '#fff', background: FLUX_BL_COLORS.comparison, fontFamily: 'Inter, sans-serif', padding: { left: 6, right: 6, top: 3, bottom: 3 } },
                                    text: 'Σ cmp: ' + sumCmp.toFixed(2)
                                }
                            }
                        ]
                    }
                });
                fluxBlDispatchUpdate({ refTotal: sumRef, cmpTotal: sumCmp });
            }

            $wire.on('changeAxis', (e) => {
                if (!e.series || e.series.length < 2 || !e.series[0].data || !e.series[0].data.length) {
                    fluxBlDispatchUpdate({ refTotal: 0, cmpTotal: 0 });
                    ApexCharts.exec('baseline_chart', "updateOptions", {
                        series: [], xaxis: { categories: [] },
                        title: { text: e.title }, annotations: { yaxis: [] },
                        noData: { text: 'Sin datos en el rango seleccionado' }
                    });
                    return;
                }
                var pos1 = Math.max.apply(null, e.series[0].data.filter(x => x !== null));
                var pos2 = Math.min.apply(null, e.series[1].data.filter(x => x !== null));
                if (!isFinite(pos1)) pos1 = 0;
                if (!isFinite(pos2)) pos2 = 0;

                /* El backend emite accumulated_result (comparación) y
                   accumulated_reference como [inicio, fin] del período. */
                var sumRef = (e.accumulated_reference && e.accumulated_reference.length === 2)
                    ? (e.accumulated_reference[1] - e.accumulated_reference[0])
                    : e.series[0].data.reduce((a, b) => a + (b || 0), 0);
                var sumCmp = (e.accumulated_result && e.accumulated_result.length === 2)
                    ? (e.accumulated_result[1] - e.accumulated_result[0])
                    : e.series[1].data.reduce((a, b) => a + (b || 0), 0);
                var isSaving = sumCmp <= sumRef;
                var diffColor = isSaving ? FLUX_BL_COLORS.savings : FLUX_BL_COLORS.overconsumption;
                var pct = sumRef === 0 ? 0 : ((sumCmp - sumRef) * 100 / sumRef);

                ApexCharts.exec('baseline_chart', "updateOptions", {
                    series: e.series,
                    xaxis: { categories: e.x_axis },
                    title: { text: e.title },
                    annotations: {
                        yaxis: [
                            {
                                y: pos1,
                                borderColor: FLUX_BL_COLORS.reference,
                                label: {
                                    borderColor: FLUX_BL_COLORS.reference,
                                    style: { color: '#fff', background: FLUX_BL_COLORS.reference, fontFamily: 'Inter, sans-serif', padding: { left: 6, right: 6, top: 3, bottom: 3 } },
                                    text: 'Σ ref: ' + Number(sumRef).toFixed(2)
                                }
                            },
                            {
                                y: (pos1 + pos2) / 2,
                                borderColor: diffColor,
                                label: {
                                    borderColor: diffColor,
                                    style: { color: '#fff', background: diffColor, fontFamily: 'Inter, sans-serif', padding: { left: 6, right: 6, top: 3, bottom: 3 } },
                                    text: (isSaving ? 'Ahorro: ' : 'Sobreconsumo: ') + Math.abs(pct).toFixed(2) + '%'
                                }
                            },
                            {
                                y: pos2,
                                borderColor: FLUX_BL_COLORS.comparison,
                                label: {
                                    borderColor: FLUX_BL_COLORS.comparison,
                                    style: { color: '#fff', background: FLUX_BL_COLORS.comparison, fontFamily: 'Inter, sans-serif', padding: { left: 6, right: 6, top: 3, bottom: 3 } },
                                    text: 'Σ cmp: ' + Number(sumCmp).toFixed(2)
                                }
                            }
                        ]
                    }
                });
                fluxBlDispatchUpdate({ refTotal: sumRef, cmpTotal: sumCmp });
            });

            $wire.on('loading', (e) => {
                ApexCharts.exec('baseline_chart', "updateOptions", {
                    series: [], xaxis: { categories: [] },
                    noData: { text: 'Datos no encontrados' }
                });
                fluxBlDispatchUpdate({ refTotal: 0, cmpTotal: 0 });
            });

            $('input[name="datetimes_baseline_result"]').on('apply.daterangepicker', function (ev, picker) {
                $wire.emit('changeDateRangeResult', picker.startDate.format('YYYY-MM-DD 00:00:00'), picker.endDate.format('YYYY-MM-DD 23:59:59'));
            });
            $('input[name="datetimes_baseline_reference"]').on('apply.daterangepicker', function (ev, picker) {
                $wire.emit('changeDateRangeReference', picker.startDate.format('YYYY-MM-DD 00:00:00'), picker.endDate.format('YYYY-MM-DD 23:59:59'));
            });
        };
    </script>
</div>
