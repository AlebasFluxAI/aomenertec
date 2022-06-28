<div class="form-group mb-{{$mb??2}} mt-{{$mt??0}} col-md-{{$col_with??12}} col-sm-12">
    <label>{{$input_label??""}}</label>
    <div class="input-group">

        <div class="input-group-prepend">
                                    <span class="input-group-text">
                                     <i class="{{$icon_class}}"></i>
                                    </span>
        </div>

        @if($input_rows??1>1)
            <textarea @if($updated_input=="lazy")
                      wire:model.lazy="{{ $input_model }}"
                      @elseif($updated_input=="defer")
                      wire:model.defer="{{ $input_model }}"
                      @else
                      wire:model="{{ $input_model }}"
                      @endif
                      rows="{{$input_rows}}" type="{{$input_type??"text"}}"
                      class="form-control" autocomplete="on" placeholder="{{$placeholder??""}}"
                      required="{{$required??false}}"></textarea>
        @else
            <input @if($updated_input=="lazy")
                   wire:model.lazy="{{ $input_model }}"
                   @elseif($updated_input=="defer")
                   wire:model.defer="{{ $input_model }}"
                   @else
                   wire:model="{{ $input_model }}"
                   @endif
                   id="{{$input_id??""}}" type="{{$input_type??"text"}}"
                   class="form-control" autocomplete="{{$autocomplete??"on"}}"
                   name="{{$input_name??""}}" onchange="{{$input_on_change??""}}()" placeholder="{{$placeholder??""}}"
                   required="{{$required??false}}">
        @endif
    </div>
    @error($input_model)
    <div class="error-container">
        <small class="form-text text-danger">{{$message}}</small>
    </div>
    @enderror
</div>
