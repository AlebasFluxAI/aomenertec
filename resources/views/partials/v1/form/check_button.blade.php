<div class="form-check mx-2 mb-{{$mb??2}} mt-{{$mt??0}} col-md-{{$col_width??6}} col-sm-12 ">
    <input wire:model="{{$check_model}}" class="form-check-input" type="checkbox" value=""
           id="Check.{{ $check_id??'' }}" @if($disabled??false)disabled @endif>
    <label class="form-check-label" for="Check.{{ $check_id??'' }}">
        {{ $check_label }}
    </label>
</div>

