<div class="form-group mb-2 col-md-{{$col_with??12}} offset-2 form-v2-input p-2">

    <div class="col-md-9">
        <li>{{$placeholder}}</li>
    </div>
    <div class="col-md-3">
        @if($input_rows??1>1)
            <textarea wire:model="{{$input_model}}" rows="{{$input_rows}}" type="{{$input_type??"text"}}"
                      class="form-control" autocomplete="on" placeholder="{{$placeholder??""}}"
                      required="{{$required??false}}"></textarea>
        @elseif($input_type=="checkbox")
            <div class="form-check form-switch">
                <input wire:model="{{$input_model}}" class="form-check-input" type="checkbox"
                       id="flexSwitchCheckChecked">
            </div>
        @else
            <input wire:model="{{$input_model}}" type="{{$input_type??"text"}}" class="form-control" autocomplete="on"
                   placeholder="{{$default??""}}" required="{{$required??false}}">

        @endif

        @error($input_model)
        <div class="error-container">
            <small class="form-text text-danger">{{$message}}</small>
        </div>
        @enderror
    </div>

</div>
