{{-- FluxAI metric card
     Rediseñada con paleta corporativa (#0044A4 / #00C781 / #0C62DC).
     Mantiene la API de props: icon_class, color, list_variable_options,
     list_model_variable, data, id, real_time_flag. --}}
<div wire:key="cards-{{ $id }}" class="col-md-{{$col_with??4}} mb-3 grid-margin stretch-card">
    <div class="flux-metric-card flux-metric-card--{{$color??'voltage'}} {{($real_time_flag??false) ? 'flux-metric-card--rt' : ''}}">
        <div class="flux-metric-card__accent"></div>

        <div class="flux-metric-card__body">
            <div class="flux-metric-card__icon">
                <i class="{{$icon_class}}"></i>
            </div>

            <div class="flux-metric-card__content">
                @include("partials.v1.form.form_list",[
                                             "col_with"=>12,
                                             "mb"=>0,
                                             "background"=>$color??"voltage",
                                             "disabled" => false,
                                             "aux_class"=>"flux-metric-card__select no-border-card",
                                             "list_model" => $list_model_variable,
                                             "list_default" => "Variable...",
                                             "list_options" => $list_variable_options,
                                             "list_option_value"=>"id",
                                             "list_option_view"=>"display_name",
                                             "list_option_title"=>""
                                    ])

                <div class="flux-metric-card__values">
                    @foreach($data as $index=>$option)
                        <div class="flux-metric-card__value @if($real_time_flag??false) animated-element flux-metric-card__value--rt @endif">
                            <span class="flux-metric-card__number"
                                  wire:loading.remove
                                  wire:target="{{$list_model_variable}}">{{ $option['value'] }}</span>
                            <span class="flux-metric-card__unit"
                                  wire:loading.remove
                                  wire:target="{{$list_model_variable}}">{{ $option['key'] }}</span>
                        </div>
                    @endforeach
                    <div class="flux-metric-card__loading"
                         wire:loading
                         wire:target="{{$list_model_variable}}">
                        <i class="fas fa-circle-notch fa-spin"></i>
                        <span>Actualizando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
