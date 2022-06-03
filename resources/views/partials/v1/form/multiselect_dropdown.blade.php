
<div wire:ignore class="dropdown form-group mb-{{$mb??2}} mt-{{$mt??0}} col-md-{{$col_width??6}} col-sm-12" id="for-picker">

    <select  wire:model="{{$model_select}}" class="selectpicker" name="{{$name_select}}" data-container="#for-picker" multiple>

        @foreach($options_list as $index => $option)
            @if($option['id'] === 29)
                <optgroup label="">
                    <option value="{{$option['id']}}">{{ $option['display_name'] }}</option>
                </optgroup>
            @break
            @endif
        @endforeach
        <optgroup label="">
        @foreach($options_list as $index => $option)
            @if($option['id'] != '29')
                <option value="{{$option['id']}}">{{ $option['display_name'] }}</option>
                @endif
            @endforeach
        </optgroup>


    </select>

</div>


