<div class="form-group mb-2 col-md-10  offset-1 form-v2-input p-2">

    <div class="col-md-6">
        <div class="col-md-12">
            @if(!$placeholder_clickable??false)
                <li>{{$placeholder}}</li>
            @else
                <li><a type="button" data-toggle="modal" data-target="#{{ $data_target }}" class="stretched-link">{{ $placeholder }}</a></li>
            @endif
        </div>
    </div>
    <div class="col-md-6" style=" border-left-color: teal;border-left-width: 2px">
        <div class="row float-right">
            <div class="col-md-4">
                <label><i class="fa-solid fa-arrow-turn-down"></i> {{$input_min_label??"Minimo"}}</label>
                <input wire:model="{{$input_min_model}}" type="number" class="form-control"
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
                <input wire:model="{{$input_max_model}}" type="number" class="form-control"
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
