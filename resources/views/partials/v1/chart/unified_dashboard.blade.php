{{-- ============================================================
     Dashboard unificado FluxAI
     - Consolida Historico + Tiempo Real + Reactivos + HeatMap
     - Toggle Tiempo Real controla RealTimeListener + firmware
       (vía emit('selectRealTime') / emit('tabChange'))
     - Compatible con firmware IpstaticV2 — no cambia topics MQTT,
       payloads ni API endpoints, solo orquesta desde la UI los
       mecanismos ya existentes.
     ============================================================ --}}
@php
    // Resolución del permiso "tiempo real" por rol:
    // - Admin / NetworkOperator / Supervisor / Technician usan UserPermissionableTrait
    //   y permisos granulares por cliente (tabPermissionConditionableExist).
    // - SuperAdmin / Seller / Support NO usan ese trait → no tienen permisos
    //   granulares y ven el toggle por defecto (mismo comportamiento histórico
    //   del patrón tab.blade.php, que filtra por clase antes de invocar el método).
    $userModel = \App\Models\V1\User::getUserModel();
    $rolesConPermisoGranular = [
        \App\Models\V1\Admin::class,
        \App\Models\V1\NetworkOperator::class,
        \App\Models\V1\Supervisor::class,
        \App\Models\V1\Technician::class,
    ];

    if (! $userModel) {
        $canSeeRealTime = false;
    } elseif (! in_array($userModel::class, $rolesConPermisoGranular, true)) {
        $canSeeRealTime = true;
    } else {
        $canSeeRealTime = $userModel->tabPermissionConditionableExist(
            \App\Models\V1\TabPermission::CLIENT_MONITORING_REAL_TIME,
            $client
        );
    }
@endphp

{{-- Estilos inline: el build de Laravel Mix solo compila resources/sass/app.scss,
     así que resources/css/app.css queda fuera del pipeline. Para garantizar
     que el dashboard se vea correctamente en producción, empaquetamos el CSS
     del feature junto a la plantilla. Colores corporativos FluxAI y clases
     de fase L1/L2/L3 compartidas con los charts hijos. --}}
<style>
    .flux-dashboard { padding-top: 1rem; }

    .flux-dashboard-controlbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: #fff;
        border: 1px solid #E4E9F0;
        border-radius: 10px;
        margin-bottom: 1.25rem;
    }
    .flux-dashboard-controlbar__left,
    .flux-dashboard-controlbar__right {
        display: flex; align-items: center; gap: 0.7rem;
    }
    .flux-dashboard-title {
        font-family: 'Poppins', system-ui, sans-serif;
        font-weight: 600; font-size: 0.98rem;
        color: #0044A4;
        display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .flux-dashboard-title i { font-size: 1.05rem; color: #00C781; }

    .flux-dashboard-section { margin-bottom: 1.5rem; }
    .flux-dashboard-subheader {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-family: 'Poppins', system-ui, sans-serif;
        font-weight: 600; font-size: 0.88rem;
        color: #0044A4;
        margin: 0.25rem 0 0.6rem 0.25rem;
        text-transform: uppercase; letter-spacing: 0.04em;
    }
    .flux-dashboard-subheader i { color: #00C781; }

    /* Toggle Tiempo Real */
    .flux-rt-toggle {
        display: inline-flex; align-items: center; gap: 0.55rem;
        background: #F5F7FA; border: 1px solid #E4E9F0;
        color: #4A5568;
        padding: 0.35rem 0.85rem 0.35rem 0.5rem;
        border-radius: 999px;
        font-family: 'Inter', system-ui, sans-serif;
        font-size: 0.8rem; font-weight: 500;
        cursor: pointer;
        transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
    }
    .flux-rt-toggle:hover { background: #fff; border-color: #0044A4; }
    .flux-rt-toggle--on { background: #00C781; border-color: #00C781; color: #fff; }
    .flux-rt-toggle--on:hover { background: #00A56B; border-color: #00A56B; }
    .flux-rt-toggle__track {
        position: relative; width: 2em; height: 1.05em;
        background: #E4E9F0; border-radius: 999px;
        transition: background 0.2s ease;
    }
    .flux-rt-toggle--on .flux-rt-toggle__track { background: rgba(255,255,255,0.4); }
    .flux-rt-toggle__thumb {
        position: absolute; top: 0.12em; left: 0.12em;
        width: 0.8em; height: 0.8em;
        background: #fff; border-radius: 50%;
        transition: transform 0.2s cubic-bezier(0.4,0,0.2,1);
        box-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }
    .flux-rt-toggle--on .flux-rt-toggle__thumb { transform: translateX(0.95em); }

    /* Badge LIVE / Histórico */
    .flux-rt-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        font-size: 0.7rem; font-weight: 700;
        letter-spacing: 0.08em; text-transform: uppercase;
        transition: all 0.25s ease;
    }
    .flux-rt-badge--idle { background: #F5F7FA; color: #4A5568; }
    .flux-rt-badge--live { background: rgba(0,199,129,0.12); color: #00A56B; }
    .flux-rt-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: currentColor; opacity: 0.5;
    }
    .flux-rt-badge--live .flux-rt-dot {
        background: #00C781; opacity: 1;
        animation: flux-rt-pulse 1.6s cubic-bezier(0.4,0,0.6,1) infinite;
    }
    @keyframes flux-rt-pulse {
        0%   { box-shadow: 0 0 0 0 rgba(0,199,129,0.7); }
        70%  { box-shadow: 0 0 0 8px rgba(0,199,129,0); }
        100% { box-shadow: 0 0 0 0 rgba(0,199,129,0); }
    }

    /* Transiciones LIVE → Histórico */
    .flux-rt-dimmable { transition: opacity 0.35s ease, filter 0.35s ease; }
    .flux-rt-dim {
        opacity: 0.35;
        filter: grayscale(0.7);
        pointer-events: none;
    }

    /* Metric cards superiores */
    .flux-metric-card {
        position: relative;
        width: 100%;
        background: #fff;
        border: 1px solid #E4E9F0;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        overflow: hidden;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
    }
    .flux-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(0,68,164,0.10);
    }
    .flux-metric-card__accent {
        position: absolute; top: 0; left: 0; bottom: 0;
        width: 4px; background: #0044A4;
    }
    .flux-metric-card--voltage .flux-metric-card__accent,
    .flux-metric-card--primary .flux-metric-card__accent { background: #0044A4; }
    .flux-metric-card--current .flux-metric-card__accent,
    .flux-metric-card--secondary .flux-metric-card__accent { background: #0C62DC; }
    .flux-metric-card--power .flux-metric-card__accent,
    .flux-metric-card--accent .flux-metric-card__accent { background: #00C781; }

    .flux-metric-card__body {
        display: flex; align-items: center; gap: 1rem;
        padding: 0.9rem 1.1rem 0.9rem 1.3rem;
    }
    .flux-metric-card__icon {
        flex: 0 0 auto;
        width: 48px; height: 48px;
        border-radius: 10px;
        background: #F5F7FA;
        color: #0044A4;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
    }
    .flux-metric-card--current .flux-metric-card__icon,
    .flux-metric-card--secondary .flux-metric-card__icon { color: #0C62DC; }
    .flux-metric-card--power .flux-metric-card__icon,
    .flux-metric-card--accent .flux-metric-card__icon {
        color: #00A56B; background: rgba(0,199,129,0.12);
    }
    .flux-metric-card__content {
        flex: 1 1 auto; min-width: 0;
        display: flex; flex-direction: column; align-items: flex-end; gap: 0.2rem;
    }
    .flux-metric-card__select { width: 100%; margin-bottom: 0.2rem; }
    .flux-metric-card__values {
        display: flex; flex-direction: column; align-items: flex-end;
        gap: 0.1rem; width: 100%;
    }
    .flux-metric-card__value {
        display: inline-flex; align-items: baseline; gap: 0.3rem;
        line-height: 1.1;
    }
    .flux-metric-card__number {
        font-family: 'Poppins', system-ui, sans-serif;
        font-weight: 700; font-size: 1.4rem;
        color: #1A202C;
        letter-spacing: -0.01em;
    }
    .flux-metric-card__unit {
        font-family: 'Inter', system-ui, sans-serif;
        font-weight: 500; font-size: 0.72rem;
        color: #4A5568;
        text-transform: uppercase; letter-spacing: 0.06em;
    }
    .flux-metric-card__loading {
        display: inline-flex; align-items: center; gap: 0.35rem;
        font-size: 0.75rem; color: #A0AEC0;
    }
    .flux-metric-card--rt {
        border-color: rgba(0,199,129,0.35);
        box-shadow: 0 0 0 1px rgba(0,199,129,0.12), 0 4px 12px rgba(0,199,129,0.10);
    }
    .flux-metric-card__value--rt .flux-metric-card__number { color: #00A56B; }

    /* Colores de fase L1 / L2 / L3 */
    .flux-phase-l1, .flux-phase-l2, .flux-phase-l3 {
        font-weight: 600;
        border-bottom: 2px solid transparent;
    }
    .flux-phase-l1 {
        background: rgba(245,158,11,0.12) !important;
        color: #B45309;
        border-bottom-color: #F59E0B;
    }
    th.flux-phase-l1 { background: #F59E0B !important; color: #fff; }
    .flux-phase-l2 {
        background: rgba(0,68,164,0.10) !important;
        color: #003380;
        border-bottom-color: #0044A4;
    }
    th.flux-phase-l2 { background: #0044A4 !important; color: #fff; }
    .flux-phase-l3 {
        background: rgba(229,57,53,0.10) !important;
        color: #B71C1C;
        border-bottom-color: #E53935;
    }
    th.flux-phase-l3 { background: #E53935 !important; color: #fff; }

    @media (max-width: 768px) {
        .flux-dashboard-controlbar { padding: 0.6rem 0.85rem; }
        .flux-metric-card__icon { width: 40px; height: 40px; font-size: 1.1rem; }
        .flux-metric-card__number { font-size: 1.15rem; }
    }
</style>

{{-- ============================================================
     Render SSR puro — Livewire reemplaza el DOM en cada toggle.
     - NO usamos x-data + @entangle + x-show para evitar bugs de Alpine
       dentro de componentes Livewire 2 con múltiples root children
       (Alpine.disableEffectScheduling crasheaba en producción).
     - El estado $liveMode vive solo en el backend; cada click en el
       toggle hace wire:click => toggleLiveMode() => re-render SSR.
     - El DOM solo incluye los @livewire hijos que efectivamente se
       muestran; cuando live=true, los componentes de histórico NO
       se montan (y viceversa). Esto elimina duplicados y multi-root.
     - Responsive: todas las media queries del bloque <style> quedan
       intactas — solo cambia la semántica de visibilidad, no la
       presentación de cada subsección.
     ============================================================ --}}
<div class="flux-dashboard">

    {{-- ------------ Barra de control del dashboard ------------ --}}
    <div class="flux-dashboard-controlbar">
        <div class="flux-dashboard-controlbar__left">
            <span class="flux-dashboard-title">
                <i class="fas fa-gauge-high"></i>
                Panel de monitoreo
            </span>
        </div>

        <div class="flux-dashboard-controlbar__right">
            @if($canSeeRealTime)
                <span class="flux-rt-badge {{ $liveMode ? 'flux-rt-badge--live' : 'flux-rt-badge--idle' }}">
                    <span class="flux-rt-dot"></span>
                    <span>{{ $liveMode ? 'LIVE' : 'Histórico' }}</span>
                </span>

                <button type="button"
                        wire:click="toggleLiveMode"
                        class="flux-rt-toggle {{ $liveMode ? 'flux-rt-toggle--on' : 'flux-rt-toggle--off' }}">
                    <span class="flux-rt-toggle__track">
                        <span class="flux-rt-toggle__thumb"></span>
                    </span>
                    <span class="flux-rt-toggle__label">{{ $liveMode ? 'Tiempo real activo' : 'Activar tiempo real' }}</span>
                </button>
            @endif
        </div>
    </div>

    @if($liveMode && $canSeeRealTime)
        {{-- ============ MODO TIEMPO REAL ============ --}}
        {{-- real-time-chart trae sus propias cards con valores en vivo,
             la gráfica con streaming y el diagrama de fasores. --}}
        <div class="flux-dashboard-section">
            @livewire('v1.admin.client.monitoring.charts.real-time-chart', [
                'client'=>$client,
                'variables' => $real_time_variables,
                'data_frame'=>$data_frame
            ], key('rt-chart'))
        </div>

        {{-- Reactivos + HeatMap se muestran atenuados en LIVE (contexto
             visual: el foco está en el streaming, pero el operador
             sigue viendo la calidad de energía y el heatmap). --}}
        <div class="flux-dashboard-section flux-rt-dimmable flux-rt-dim">
            <div class="flux-dashboard-subheader">
                <i class="fas fa-bolt"></i>
                <span>Reactivos</span>
            </div>
            @livewire('v1.admin.client.monitoring.charts.reactive-chart', [
                'client'=>$client,
                'reactive_variables' => $reactive_variables,
                'data_chart_reactive'=>$data_chart,
                'time'=>$time
            ], key('reactive-chart-live'))
        </div>

        <div class="flux-dashboard-section flux-rt-dimmable flux-rt-dim">
            <div class="flux-dashboard-subheader">
                <i class="fas fa-table-cells"></i>
                <span>HeatMap</span>
            </div>
            @livewire('v1.admin.client.monitoring.charts.heat-map-chart', [
                'client'=>$client,
                'reactive_variables' => $reactive_variables,
                'data_chart_heat_map'=>$data_chart
            ], key('heatmap-chart-live'))
        </div>
    @else
        {{-- ============ MODO HISTÓRICO (default) ============ --}}
        {{-- Cards superiores con últimos valores persistidos. --}}
        <div class="flux-dashboard-section">
            @livewire('v1.admin.client.monitoring.charts.cards-data', [
                'client'=>$client,
                'variables' => $variables,
                'data_frame'=>$data_frame
            ], key('cards-hist'))
        </div>

        {{-- Gráfica histórica principal. --}}
        <div class="flux-dashboard-section">
            @livewire('v1.admin.client.monitoring.charts.data-chart', [
                'client'=>$client,
                'variables' => $variables,
                'data_frame'=>$data_frame,
                'data_chart'=>$data_chart,
                'time'=>$time
            ], key('data-chart'))
        </div>

        {{-- Reactivos + HeatMap a brillo normal cuando NO es live. --}}
        <div class="flux-dashboard-section">
            <div class="flux-dashboard-subheader">
                <i class="fas fa-bolt"></i>
                <span>Reactivos</span>
            </div>
            @livewire('v1.admin.client.monitoring.charts.reactive-chart', [
                'client'=>$client,
                'reactive_variables' => $reactive_variables,
                'data_chart_reactive'=>$data_chart,
                'time'=>$time
            ], key('reactive-chart-hist'))
        </div>

        <div class="flux-dashboard-section">
            <div class="flux-dashboard-subheader">
                <i class="fas fa-table-cells"></i>
                <span>HeatMap</span>
            </div>
            @livewire('v1.admin.client.monitoring.charts.heat-map-chart', [
                'client'=>$client,
                'reactive_variables' => $reactive_variables,
                'data_chart_heat_map'=>$data_chart
            ], key('heatmap-chart-hist'))
        </div>
    @endif

</div>
