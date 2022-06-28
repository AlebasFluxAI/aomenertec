<div class="form-group mb-2 col-md-10  offset-1 form-v2-input p-2">

    <div class="col-md-6">
        <div class="col-md-12">
            @if(!$placeholder_clickable??false)
                <li>{{$placeholder}}</li>
            @else
                <li><a wire:click="{{ $click_action }}" type="button" data-toggle="modal" data-target="#{{ $data_target }}" class="stretched-link">{{ $placeholder }}</a></li>
            @endif
        </div>
    </div>
    <div class="col-md-6" style=" border-left-color: teal;border-left-width: 2px">
        <div class="row float-right">
            <div class="col-md-4">
                <label><i class="fa-solid fa-arrow-turn-down"></i> {{$input_min_label??"Minimo"}}</label>
                <input  @if($updated_input=="lazy")
                            wire:model.lazy="{{ $input_min_model }}"
                        @elseif($updated_input=="defer")
                            wire:model.defer="{{ $input_min_model }}"
                        @else
                            wire:model="{{ $input_min_model }}"
                        @endif

                        type="number" class="form-control"
                       autocomplete="on"
                       placeholder="{{$default??""}}" required="{{$required??false}}">

                @error($input_min_model)
                <div class="error-container">
                    <small class="form-text text-danger">{{$message}}</small>
                </div>
                @enderror
            </div>
            <div class="col-md-4">
                <label><i class="fa-solid fa-turn-up"></i> {{$input_max_label??"Maximo"}}</label>
                <input  @if($updated_input=="lazy")
                            wire:model.lazy="{{ $input_max_model }}"
                        @elseif($updated_input=="defer")
                            wire:model.defer="{{ $input_max_model }}"
                        @else
                            wire:model="{{ $input_max_model }}"
                        @endif
                       type="number" class="form-control"
                       autocomplete="on"
                       placeholder="{{$default??""}}" required="{{$required??false}}">

                @error($input_max_model)
                <div class="error-container">
                    <small class="form-text text-danger">{{$message}}</small>
                </div>
                @enderror
            </div>
        </div>
    </div>
</div>
