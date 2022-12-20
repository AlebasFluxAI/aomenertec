<div wire:ignore class="dropdown form-group mb-{{$mb??2}} mt-{{$mt??0}} col-md-{{$col_width??6}} col-sm-12"
     id="for-picker_{{$name_select}}">
    <label>{{$input_label??""}} @if($tooltip_title??false)
            <span class="fas fa-circle-question"
                  data-toggle="tooltip" data-placement="{{$tooltip_position??"top"}}"
                  title="{{$tooltip_title??""}}"
            ></span></label>
    @endif
    <select wire:model.defer="{{$model_select}}" class="selectpicker" name="{{$name_select}}"
            data-container="#for-picker_{{$name_select}}" multiple>
        @if($optgroup)
            @foreach($options_list as $index => $option)
                @if($option[$option_value] === 33)
                    <optgroup label="">
                        <option value="{{ $option[$option_value] }}">{{ $option[$option_view] }}</option>
                    </optgroup>
                    @break
                @endif
            @endforeach
            <optgroup label="">
                @foreach($options_list as $index => $option)
                    @if($option[$option_value] != '33')
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


