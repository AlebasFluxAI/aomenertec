
<div wire:ignore class="dropdown form-group mb-{{$mb??2}} mt-{{$mt??0}} col-md-{{$col_width??6}} col-sm-12" id="for-picker">
    <label>{{$input_label??""}}</label>
    <select  wire:model.defer="{{$model_select}}" class="selectpicker" name="{{$name_select}}" data-container="#for-picker" multiple>
        @if($optgroup)
            @foreach($options_list as $index => $option)
                @if($option[$option_value] === 29)
                    <optgroup label="">
                        <option value="{{ $option[$option_value] }}">{{ $option[$option_view] }}</option>
                    </optgroup>
                @break
                @endif
            @endforeach
            <optgroup label="">
            @foreach($options_list as $index => $option)
                @if($option[$option_value] != '29')
                    <option value="{{ $option[$option_value] }}">{{ $option[$option_view] }}</option>
                @endif
            @endforeach
            </optgroup>
        @else
            @foreach($options_list as $index => $option)
                <option value="{{ $option[$option_value] }}">{{ $option[$option_view] }}</option>
            @endforeach
        @endif

    </select>

</div>


