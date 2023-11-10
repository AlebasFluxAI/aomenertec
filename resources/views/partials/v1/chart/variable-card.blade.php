<div wire:key="cards-{{ $id }}" class="col-md-{{$col_with??4}} mb-2 grid-margin stretch-card">
    <div class="card1 shadow  {{$color??"voltage"}} ">
        <div class="card-body">
            <div class="d-flex flex-md-column flex-xl-row flex-wrap  align-items-center justify-content-between">
                <div class="d-flex align-items-center icon-rounded-inverse icon-rounded-lg">
                    <i class=" {{$icon_class}} fa-2x">
                    </i>
                </div>
                <div>

                    @include("partials.v1.form.form_list",[
                                                 "col_with"=>6,
                                                 "mb"=>0,
                                                 "background"=>$color??"voltage",
                                                 "disabled" => false,
                                                 "aux_class"=>"no-border-card",
                                                 "list_model" => $list_model_variable,
                                                 "list_default" => "Variable...",
                                                 "list_options" => $list_variable_options,
                                                 "list_option_value"=>"id",
                                                 "list_option_view"=>"display_name",
                                                 "list_option_title"=>""
                                        ])
                    @foreach($data as $index=>$option)
                        <div
                            class="d-flex flex-md-column flex-xl-row align-items-baseline align-items-md-center align-items-xl-baseline justify-content-end
                                    @if($real_time_flag??false)animated-element @endif">
                            <h3 wire:loading.remove wire:target="{{$list_model_variable}}"
                                class="mb-0 mb-md-1 mb-lg-0 mr-1">{{ $option['value'] }}</h3>
                            <small wire:loading.remove wire:target="{{$list_model_variable}}"
                                   class="mb-0">{{ $option['key'] }}</small>
                        </div>
                    @endforeach
                    <div
                        class="d-flex flex-md-column flex-xl-row  align-items-baseline align-items-md-center align-items-xl-baseline justify-content-end">
                        <small wire:loading wire:target="{{$list_model_variable}}" class="mb-0">Actualizando...</small>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
