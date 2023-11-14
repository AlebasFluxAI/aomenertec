<div class="form-group mb-{{$mb??2}} mt-{{$mt??0}} col-md-{{$col_with??12}} col-sm-12">
    <label>{{$input_label??""}}</label>
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
            <input wire:model="{{$input_model}}" type="{{$input_type??"text"}}" class="form-control"
                   autocomplete="{{$autocomplete??"on"}}"
                   name="{{$input_name??""}}" placeholder="{{$placeholder??""}}" required="{{$required??false}}"
                   @if($input_type??"text" == "number")
                       min="{{ $number_min??''}}" max="{{ $number_max??''}}" step="{{ $number_step??''}}"
                @endif
            >
        @endif
        <div class="input-group-append">
            <a wire:click="{{$button_action}}" class="btn btn-outline-secondary" type="button">{{$button_name}}</a>
        </div>
    </div>
    @error($input_model)
    <div class="error-container">
        <small class="form-text text-danger">{{$message}}</small>
    </div>
    @enderror
</div>
