<div class="form-group mb-2 col-md-{{$col_with}} col-sm-{{$col_with}}">

    <div class="input-group">

        <div class="input-group-prepend">
                                    <span class="input-group-text">
                                     <i class="{{$icon_class}}"></i>
                                    </span>
        </div>

        @if($input_rows??1>1)
            <textarea wire:model="{{$input_model}}" rows="{{$input_rows}}" type="{{$input_type??"text"}}"
                      class="form-control" autocomplete="on" placeholder="{{$placeholder??""}}"
                      required="{{$required??false}}"></textarea>
        @else
            <input wire:model="{{$input_model}}" type="{{$input_type??"text"}}" class="form-control" autocomplete="on"
                   placeholder="{{$placeholder??""}}" required="{{$required??false}}">
        @endif
    </div>
    @error($input_model)
    <div class="error-container">
        <small class="form-text text-danger">{{$message}}</small>
    </div>
    @enderror
</div>
