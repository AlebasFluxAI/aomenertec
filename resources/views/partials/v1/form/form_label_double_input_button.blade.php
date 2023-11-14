<div class="form-group mx-0 mb-{{$mb??2}} mt-{{$mt??0}} col-md-{{$col_width??6}} col-sm-12 ">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text" id="">{{ $label }}</span>
        </div>
        <input wire:model.defer="{{ $model_first_input }}" type="{{ $type_first_input }}" class="form-control"
               placeholder="{{ $placeholder_first_input }}" @if($disabled??false) disabled @endif
               @if($input_type??"text" == "number")
                   min="{{ $number_min??''}}" max={{ $number_max??''}} step="{{ $number_step??''}}"
            @endif>
        <input wire:model.defer="{{ $model_second_input }}" type="{{ $model_second_input }}" class="form-control"
               placeholder="{{ $placeholder_second_input }}" @if($disabled??false) disabled @endif
               @if($input_type??"text" == "number")
                   min="{{ $number_min??''}}" max="{{ $number_max??''}}" step="{{ $number_step??''}}"
            @endif>
        <div class="input-group-append">
            <a wire:click="{{$button_action}}" class="btn btn-outline-secondary @if($disabled??false)disabled @endif"
               type="button">{{$button_name}}</a>
        </div>
    </div>
</div>

