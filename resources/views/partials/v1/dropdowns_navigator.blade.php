{{-- FluxAI Actions Dropdown --}}
{{-- Redesigned with energy/tech aesthetic. Functionality (routes, permissions, actions) preserved 1:1. --}}

<div class="text-{{$button_align??"center"}} custom-nav-button">
    {{-- Style embebido dentro del root del dropdown para evitar pares
         top-level <style> + <div> que Livewire reporta como multi-root
         cuando este partial se incluye dentro de un componente. --}}
    <style>
        .flux-actions-wrapper { position: relative; display: inline-block; }
        .flux-actions-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px 9px 14px;
            border: 1px solid rgba(12, 98, 220, 0.35);
            border-radius: 12px;
            background: linear-gradient(135deg, #0044A4 0%, #0C62DC 55%, #1E88E5 100%);
            color: #fff !important;
            font-family: var(--flux-tech-font, 'Inter', system-ui, sans-serif);
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.01em;
            text-transform: none;
            line-height: 1.2;
            cursor: pointer;
            box-shadow: 0 6px 18px -10px rgba(0, 68, 164, 0.55), inset 0 0 0 1px rgba(255, 255, 255, 0.08);
            transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
        }
        .flux-actions-btn:hover,
        .flux-actions-btn:focus {
            color: #fff !important;
            filter: brightness(1.08);
            box-shadow: 0 10px 24px -10px rgba(0, 68, 164, 0.75), inset 0 0 0 1px rgba(255, 255, 255, 0.14);
            transform: translateY(-1px);
            text-decoration: none;
        }
        .flux-actions-btn::after { display: none; }
        .flux-actions-btn__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 13px;
        }
        .flux-actions-btn__caret {
            font-size: 10px;
            opacity: 0.9;
            margin-left: 2px;
            transition: transform .2s ease;
        }
        .flux-actions-wrapper.show .flux-actions-btn__caret,
        .dropdown.show .flux-actions-btn__caret,
        .flux-actions-btn[aria-expanded="true"] .flux-actions-btn__caret { transform: rotate(180deg); }
    
        .flux-actions-menu.dropdown-menu {
            min-width: 270px;
            padding: 10px;
            margin-top: 10px;
            background: #FFFFFF;
            border: 1px solid var(--flux-border, #E4E9F0);
            border-radius: 14px;
            box-shadow: 0 18px 40px -16px rgba(9, 30, 66, 0.25), 0 2px 6px -2px rgba(9, 30, 66, 0.08);
            font-family: var(--flux-tech-font, 'Inter', system-ui, sans-serif);
        }
        .flux-actions-menu::before {
            content: "";
            display: block;
            height: 3px;
            margin: -10px -10px 10px -10px;
            border-radius: 14px 14px 0 0;
            background: linear-gradient(90deg, #00C781 0%, #0C62DC 50%, #0044A4 100%);
        }
        .flux-actions-menu__header {
            padding: 2px 8px 8px 8px;
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: none;
            color: #7A869A;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .flux-actions-menu__header i { color: var(--flux-accent, #00C781); font-size: 10px; }
    
        .flux-actions-item.dropdown-item {
            display: flex !important;
            align-items: center;
            gap: 12px;
            padding: 9px 12px;
            margin: 2px 0;
            border-radius: 10px;
            color: var(--flux-graphite, #2D3748);
            font-family: var(--flux-tech-font, 'Inter', system-ui, sans-serif);
            font-weight: 500;
            font-size: 14px;
            letter-spacing: 0.005em;
            text-decoration: none;
            background: transparent;
            border: 1px solid transparent;
            transition: background-color .15s ease, border-color .15s ease, color .15s ease, transform .1s ease;
            white-space: nowrap;
        }
        .flux-actions-item.dropdown-item:hover,
        .flux-actions-item.dropdown-item:focus {
            background: linear-gradient(90deg, rgba(12, 98, 220, 0.08) 0%, rgba(0, 199, 129, 0.06) 100%);
            border-color: rgba(12, 98, 220, 0.18);
            color: var(--flux-primary, #0044A4);
            text-decoration: none;
            transform: translateX(2px);
        }
        .flux-actions-item__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            flex-shrink: 0;
            border-radius: 9px;
            background: rgba(12, 98, 220, 0.08);
            color: var(--flux-primary, #0044A4);
            font-size: 14px;
            transition: background-color .15s ease, color .15s ease, box-shadow .2s ease;
        }
        .flux-actions-item.dropdown-item:hover .flux-actions-item__icon {
            background: linear-gradient(135deg, #0044A4 0%, #0C62DC 100%);
            color: #fff;
            box-shadow: 0 6px 14px -6px rgba(12, 98, 220, 0.55);
        }
        .flux-actions-item__label {
            flex: 1;
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }
        .flux-actions-item__label small {
            display: block;
            font-size: 11px;
            font-weight: 400;
            color: #7A869A;
            letter-spacing: 0.04em;
            text-transform: none;
        }
        .flux-actions-item__arrow {
            color: #B0BAC9;
            font-size: 11px;
            opacity: 0;
            transform: translateX(-4px);
            transition: opacity .15s ease, transform .15s ease, color .15s ease;
        }
        .flux-actions-item.dropdown-item:hover .flux-actions-item__arrow {
            opacity: 1;
            transform: translateX(0);
            color: var(--flux-primary, #0044A4);
        }
    
        {{-- Hook to style function-based actions (render as simple items) --}}
        .flux-actions-menu .btn.dropdown-item,
        .flux-actions-menu .btn-group > .btn.dropdown-item,
        .flux-actions-menu form .btn.dropdown-item {
            width: 100%;
            text-align: left;
            background: transparent;
            border: 1px solid transparent;
            color: var(--flux-graphite, #2D3748);
            font-family: var(--flux-tech-font, 'Inter', system-ui, sans-serif);
            font-weight: 500;
            border-radius: 10px;
        }
        .flux-actions-menu .btn.dropdown-item:hover {
            background: linear-gradient(90deg, rgba(12, 98, 220, 0.08) 0%, rgba(0, 199, 129, 0.06) 100%);
            color: var(--flux-primary, #0044A4);
        }
    </style>

    <div class="dropdown dropleft flux-actions-wrapper">

        <button class="flux-actions-btn dropdown-toggle" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                title="Acciones disponibles">
            <span class="flux-actions-btn__icon">
                <span class="{{$icon??""}} {{$button_icon??""}}"></span>
            </span>
            <span>{{$button_content}}</span>
            <span class="flux-actions-btn__caret fas fa-chevron-down" aria-hidden="true"></span>
        </button>
        <div class="dropdown-menu flux-actions-menu" aria-labelledby="dropdownMenuButton">
            <div class="flux-actions-menu__header">
                <i class="fas fa-bolt"></i>
                <span>Acciones disponibles</span>
            </div>
            @foreach($dropdown_options as $option)
                @if(!array_key_exists("actionable",$option))
                    @continue
                @endif

                @if(isset($option["actionable"]["permission"]) and \Illuminate\Support\Facades\Auth::hasUser() and
                        !array_intersect($option["actionable"]["permission"],\App\Models\V1\User::getUserModel()->getPermissions()))
                    @continue
                @endif
                @php($__flux_icon = $option["actionable"]["icon"] ?? "fas fa-circle-chevron-right")
                @php($__flux_tooltip = $option["actionable"]["tooltip_title"] ?? null)
                @if(array_key_exists("redirect",$option["actionable"]))
                    <a class="btn btn-redirect btn-sm dropdown-item flux-actions-item"
                       href="{{route($option["actionable"]["redirect"]["route"],
                                [$option["actionable"]["redirect"]["binding"]=>$option["actionable"]["redirect"]["value"]])}}"
                       @if($__flux_tooltip) title="{{ $__flux_tooltip }}" @endif>
                        <span class="flux-actions-item__icon"><i class="{{ $__flux_icon }}"></i></span>
                        <span class="flux-actions-item__label">
                            <span>{{$option["title"]}}</span>
                            @if($__flux_tooltip && $__flux_tooltip !== $option["title"])
                                <small>{{ $__flux_tooltip }}</small>
                            @endif
                        </span>
                        <span class="flux-actions-item__arrow fas fa-arrow-right"></span>
                    </a>

                @elseif(array_key_exists("function",$option["actionable"]))
                    <div class="flux-actions-item flux-actions-item--function">
                        <span class="flux-actions-item__icon"><i class="{{ $__flux_icon }}"></i></span>
                        <span class="flux-actions-item__label">
                            @include("partials.v1.table.table-action-button",[
                                    "modal"=>$option["actionable"]["modal"]?? null,
                                    "button_content"=>$option["actionable"]["title"]?? $option["title"],
                                    "button_action"=>$option["actionable"]["function"],
                                    "model_id"=>$option["actionable"]["value"]?? 1,
                            ])
                        </span>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
