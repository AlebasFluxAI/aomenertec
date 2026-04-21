{{-- =========================================================
     FluxAI Home Shell — dashboard reutilizable por rol
     Renderiza: welcome hero + KPIs + accesos rapidos + paneles de
     actividad reciente. Los 7 roles del sistema lo incluyen pasando
     sus propios arrays. Sin logica de negocio: solo presentacion.

     Props esperados (todos opcionales excepto welcome_title):
       $welcome_title        string  - "Bienvenido, Juan"
       $welcome_subtitle     string  - "Panel de control FluxAI — ..."
       $welcome_role_chip    string  - "Super administrador"
       $kpis                 array[] - [ label, value, hint, icon, accent ]
                                        accent ∈ clients|equipment|admins|pqrs|invoices|savings
       $quick_actions        array[] - [ title, subtitle, icon, url, icon_style ]
                                        icon_style ∈ primary|accent|warn|purple|danger
       $activity_panels      array[] - [ title, icon, empty_message, rows ]
                                        rows: [ title, sub, badge, badge_class ]
    ========================================================= --}}

@once
    <style>
        .flux-home {
            font-family: var(--flux-tech-font, 'Inter', system-ui, sans-serif);
            color: var(--flux-graphite, #2D3748);
        }
        .flux-welcome {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 1rem;
            padding: 1.5rem 1.75rem; margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #F8FAFC 100%);
            border: 1px solid var(--flux-border, #E4E9F0);
            border-radius: 14px;
            position: relative; overflow: hidden;
        }
        .flux-welcome::before {
            content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, #00C781 0%, #0C62DC 55%, #0044A4 100%);
        }
        .flux-welcome__text h1 {
            font-size: 1.6rem; font-weight: 600; margin: 0 0 0.25rem;
            background: linear-gradient(90deg, #0044A4 0%, #0C62DC 60%, #00C781 100%);
            -webkit-background-clip: text; background-clip: text;
            -webkit-text-fill-color: transparent; color: transparent;
        }
        .flux-welcome__text p {
            margin: 0; color: var(--flux-muted, #7A869A);
            font-size: 0.9rem; letter-spacing: 0.01em;
        }
        .flux-welcome__meta { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .flux-chip {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.4rem 0.85rem;
            background: rgba(12, 98, 220, 0.08);
            border: 1px solid rgba(12, 98, 220, 0.18);
            color: var(--flux-primary, #0044A4);
            border-radius: 999px; font-size: 0.78rem; font-weight: 500;
        }
        .flux-chip i { font-size: 0.75rem; }
        .flux-chip--accent {
            background: rgba(0, 199, 129, 0.08);
            border-color: rgba(0, 199, 129, 0.22);
            color: #0A8D5B;
        }

        .flux-section { margin-bottom: 1.75rem; }
        .flux-section__head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 0.85rem;
        }
        .flux-section__title {
            font-size: 0.95rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--flux-graphite, #2D3748); margin: 0;
            display: inline-flex; align-items: center; gap: 0.55rem;
        }
        .flux-section__title::before {
            content: ""; width: 4px; height: 18px; border-radius: 2px;
            background: linear-gradient(180deg, #00C781 0%, #0C62DC 100%);
        }
        .flux-section__link {
            font-size: 0.82rem; color: var(--flux-secondary, #0C62DC);
            text-decoration: none; font-weight: 500;
        }
        .flux-section__link:hover { color: var(--flux-primary, #0044A4); }

        .flux-kpi-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        .flux-kpi {
            position: relative; background: #fff;
            border: 1px solid var(--flux-border, #E4E9F0); border-radius: 12px;
            padding: 1.1rem 1.25rem;
            display: flex; flex-direction: column; gap: 0.4rem;
            transition: box-shadow .2s ease, transform .15s ease, border-color .2s ease;
            overflow: hidden;
        }
        .flux-kpi::before {
            content: ""; position: absolute; inset: 0 auto 0 0; width: 3px;
            background: var(--flux-kpi-accent, #0C62DC);
        }
        .flux-kpi:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px -14px rgba(12, 98, 220, 0.35);
            border-color: rgba(12, 98, 220, 0.28);
        }
        .flux-kpi__label {
            font-size: 0.74rem; text-transform: uppercase; letter-spacing: 0.06em;
            color: var(--flux-muted, #7A869A); font-weight: 500;
        }
        .flux-kpi__value {
            font-size: 1.9rem; font-weight: 600;
            color: var(--flux-graphite, #2D3748); line-height: 1.1;
            font-variant-numeric: tabular-nums;
        }
        .flux-kpi__hint {
            font-size: 0.74rem; color: var(--flux-muted, #7A869A);
            display: inline-flex; align-items: center; gap: 0.3rem;
        }
        .flux-kpi__icon {
            position: absolute; top: 0.9rem; right: 1rem;
            width: 36px; height: 36px; border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--flux-kpi-bg, rgba(12, 98, 220, 0.08));
            color: var(--flux-kpi-accent, #0C62DC); font-size: 0.95rem;
        }
        .flux-kpi--clients   { --flux-kpi-accent: #0C62DC; --flux-kpi-bg: rgba(12, 98, 220, 0.08); }
        .flux-kpi--equipment { --flux-kpi-accent: #0044A4; --flux-kpi-bg: rgba(0, 68, 164, 0.08); }
        .flux-kpi--admins    { --flux-kpi-accent: #6B46C1; --flux-kpi-bg: rgba(107, 70, 193, 0.08); }
        .flux-kpi--pqrs      { --flux-kpi-accent: #D97706; --flux-kpi-bg: rgba(217, 119, 6, 0.09); }
        .flux-kpi--invoices  { --flux-kpi-accent: #E53935; --flux-kpi-bg: rgba(229, 57, 53, 0.08); }
        .flux-kpi--savings   { --flux-kpi-accent: #00C781; --flux-kpi-bg: rgba(0, 199, 129, 0.1); }

        .flux-quick-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.85rem;
        }
        .flux-quick {
            display: flex; align-items: center; gap: 0.9rem;
            padding: 1rem 1.15rem; background: #fff;
            border: 1px solid var(--flux-border, #E4E9F0); border-radius: 12px;
            text-decoration: none !important; color: var(--flux-graphite, #2D3748);
            transition: box-shadow .2s ease, transform .15s ease, border-color .2s ease, background-color .2s ease;
        }
        .flux-quick:hover {
            transform: translateY(-2px);
            border-color: rgba(12, 98, 220, 0.3);
            background: linear-gradient(180deg, #fff 0%, #F7FAFF 100%);
            box-shadow: 0 10px 24px -14px rgba(12, 98, 220, 0.35);
        }
        .flux-quick__icon {
            flex-shrink: 0; width: 42px; height: 42px; border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #0044A4, #0C62DC);
            color: #fff; font-size: 1rem;
        }
        .flux-quick__icon--accent  { background: linear-gradient(135deg, #0C62DC, #00C781); }
        .flux-quick__icon--warn    { background: linear-gradient(135deg, #D97706, #F59E0B); }
        .flux-quick__icon--purple  { background: linear-gradient(135deg, #6B46C1, #0C62DC); }
        .flux-quick__icon--danger  { background: linear-gradient(135deg, #E53935, #F97316); }
        .flux-quick__text { display: flex; flex-direction: column; gap: 0.1rem; }
        .flux-quick__title { font-weight: 600; font-size: 0.92rem; }
        .flux-quick__subtitle { font-size: 0.78rem; color: var(--flux-muted, #7A869A); }

        .flux-activity-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1rem;
        }
        .flux-panel {
            background: #fff; border: 1px solid var(--flux-border, #E4E9F0);
            border-radius: 12px; overflow: hidden;
        }
        .flux-panel__head {
            padding: 0.9rem 1.1rem; border-bottom: 1px solid var(--flux-border, #E4E9F0);
            display: flex; align-items: center; justify-content: space-between;
            font-weight: 600; font-size: 0.88rem; color: var(--flux-graphite, #2D3748);
        }
        .flux-panel__head i { color: var(--flux-secondary, #0C62DC); margin-right: 0.45rem; }
        .flux-panel__body { padding: 0.35rem 0; }
        .flux-panel__row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.7rem 1.1rem; border-bottom: 1px solid #F1F4F9; gap: 0.75rem;
        }
        .flux-panel__row:last-child { border-bottom: none; }
        .flux-panel__row-main { display: flex; flex-direction: column; gap: 0.15rem; min-width: 0; }
        .flux-panel__row-title { font-weight: 500; font-size: 0.88rem; color: var(--flux-graphite, #2D3748); }
        .flux-panel__row-sub { font-size: 0.74rem; color: var(--flux-muted, #7A869A); }
        .flux-panel__empty {
            padding: 1.5rem 1.1rem; text-align: center;
            color: var(--flux-muted, #7A869A); font-size: 0.85rem;
        }
        .flux-badge {
            font-size: 0.7rem; padding: 0.2rem 0.55rem; border-radius: 999px;
            font-weight: 500; letter-spacing: 0.01em; white-space: nowrap;
        }
        .flux-badge--ok    { background: rgba(0, 199, 129, 0.12); color: #0A8D5B; }
        .flux-badge--warn  { background: rgba(217, 119, 6, 0.12); color: #B45309; }
        .flux-badge--info  { background: rgba(12, 98, 220, 0.12); color: #0044A4; }
        .flux-badge--muted { background: #EEF2F7; color: #6B7280; }
        .flux-badge--danger{ background: rgba(229, 57, 53, 0.12); color: #C62828; }

        .flux-legacy-tabs {
            margin-top: 2rem; padding-top: 1.25rem;
            border-top: 1px dashed var(--flux-border, #E4E9F0);
        }
        .flux-legacy-tabs .flux-section__title { opacity: 0.85; }
    </style>
@endonce

<div class="flux-home">
    {{-- ===== Welcome ===== --}}
    <div class="flux-welcome">
        <div class="flux-welcome__text">
            <h1>{{ $welcome_title ?? 'Bienvenido' }}</h1>
            <p>{{ $welcome_subtitle ?? 'Panel de control FluxAI — resumen operacional.' }}</p>
        </div>
        <div class="flux-welcome__meta">
            @if(!empty($welcome_role_chip))
                <span class="flux-chip"><i class="fas fa-user-shield"></i> {{ $welcome_role_chip }}</span>
            @endif
            <span class="flux-chip flux-chip--accent"><i class="far fa-calendar-alt"></i> {{ now()->translatedFormat('l, d \d\e F Y') }}</span>
        </div>
    </div>

    {{-- ===== KPIs ===== --}}
    @if(!empty($kpis))
        <section class="flux-section">
            <div class="flux-section__head">
                <h2 class="flux-section__title">Indicadores</h2>
                <span class="flux-section__link">Actualizado ahora</span>
            </div>
            <div class="flux-kpi-grid">
                @foreach($kpis as $kpi)
                    <div class="flux-kpi flux-kpi--{{ $kpi['accent'] ?? 'clients' }}">
                        <span class="flux-kpi__icon"><i class="{{ $kpi['icon'] ?? 'fas fa-chart-bar' }}"></i></span>
                        <span class="flux-kpi__label">{{ $kpi['label'] ?? '' }}</span>
                        <span class="flux-kpi__value">{{ $kpi['value'] ?? '—' }}</span>
                        @if(!empty($kpi['hint']))
                            <span class="flux-kpi__hint">{{ $kpi['hint'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ===== Quick access ===== --}}
    @if(!empty($quick_actions))
        <section class="flux-section">
            <div class="flux-section__head">
                <h2 class="flux-section__title">Accesos rápidos</h2>
            </div>
            <div class="flux-quick-grid">
                @foreach($quick_actions as $qa)
                    <a href="{{ $qa['url'] ?? '#' }}" class="flux-quick">
                        <span class="flux-quick__icon flux-quick__icon--{{ $qa['icon_style'] ?? 'primary' }}"><i class="{{ $qa['icon'] ?? 'fas fa-link' }}"></i></span>
                        <span class="flux-quick__text">
                            <span class="flux-quick__title">{{ $qa['title'] ?? '' }}</span>
                            <span class="flux-quick__subtitle">{{ $qa['subtitle'] ?? '' }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ===== Recent activity ===== --}}
    @if(!empty($activity_panels))
        <section class="flux-section">
            <div class="flux-section__head">
                <h2 class="flux-section__title">Actividad reciente</h2>
            </div>
            <div class="flux-activity-grid">
                @foreach($activity_panels as $panel)
                    <div class="flux-panel">
                        <div class="flux-panel__head">
                            <span><i class="{{ $panel['icon'] ?? 'fas fa-stream' }}"></i>{{ $panel['title'] ?? '' }}</span>
                        </div>
                        <div class="flux-panel__body">
                            @forelse($panel['rows'] ?? [] as $row)
                                <div class="flux-panel__row">
                                    <div class="flux-panel__row-main">
                                        <span class="flux-panel__row-title">{{ $row['title'] ?? '' }}</span>
                                        @if(!empty($row['sub']))
                                            <span class="flux-panel__row-sub">{{ $row['sub'] }}</span>
                                        @endif
                                    </div>
                                    @if(!empty($row['badge']))
                                        <span class="flux-badge {{ $row['badge_class'] ?? 'flux-badge--muted' }}">{{ $row['badge'] }}</span>
                                    @endif
                                </div>
                            @empty
                                <div class="flux-panel__empty">{{ $panel['empty_message'] ?? 'Sin registros recientes.' }}</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
