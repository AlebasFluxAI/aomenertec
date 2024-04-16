<div class="form-group mb-2 col-md-{{$col_with??12}} offset-{{$offset??'0'}} form-v2-input p-2">

    <div @if($select_status_input??false)class="col-md-6"@else class="col-md-8"@endif style=" border-left-color: teal;border-left-width: 2px">
        @if(!$placeholder_clickable??false)
            <li>{{$placeholder}}</li>
        @else
            <li><a>
                    <button wire:click="{{ $click_action }}" type="button" data-toggle="modal"
                            data-target="#{{ $data_target }}" class="stretched-link">{{ $placeholder??"" }}</button>
                </a></li>
        @endif
    </div>
    <div @if($select_status_input??false)class="col-md-3 input-group"@else class="col-md-4 input-group"@endif>

        @if($icon_class??null)
            <div class="input-group-prepend">
                                    <span class="input-group-text">
                                     <i class="{{$icon_class}}"></i>
                                    </span>
            </div>
        @endif
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
        @elseif($input_type=="checkbox")
            <div class="form-check form-switch">
                <input
                    wire:model.lazy="{{$input_model}}"

                    class="form-check-input" type="checkbox"
                    id="flexSwitchCheckChecked">
            </div>

        @elseif($input_type=="select")
            <select wire:model="{{$input_model}}" class="{{$aux_class??"custom-select"}} {{$background??""}} "
                    required="{{$required??false}}" @if($disabled??false)disabled @endif>
                <option disabled value="0"> {{$select_default??"Seleccione..."}} </option>
                @foreach($select_options??[] as $option)
                    <option @if($select_option_title??"" != "")title="{{ $option[$select_option_title] }}"
                            @endif value="{{ $option[$select_option_value] }}">{{ $option[$select_option_view] }}</option>

                @endforeach
            </select>
        @elseif($input_type == "multiselect")

            <div wire:ignore class="dropdown form-group mb-{{$mb??2}} mt-{{$mt??0}} col-md-{{$col_width??6}} col-sm-12"
                 id="for-picker_{{$name_select}}">

                <select wire:model.defer="{{$model_select}}" class="selectpicker" name="{{$name_select}}"
                        data-container="#for-picker_{{$name_select}}" multiple>
                    @foreach($options_list as $index => $option)
                        <option value="{{ $option[$option_value] }}">{{ $option[$option_view] }}</option>
                    @endforeach

                </select>

            </div>

        @else
            <input @if($updated_input=="lazy")
                       wire:model.lazy="{{ $input_model }}"
                   @elseif($updated_input=="defer")
                       wire:model.defer="{{ $input_model }}"
                   @else
                       wire:model="{{ $input_model }}"
                   @endif

                   type="{{$input_type??"text"}}" class="form-control" autocomplete="on"

                   required="{{$required??false}}"

                   @if($input_type??"text" == "number")
                       min="{{ $number_min??''}}" max="{{ $number_max??''}}" step="{{ $number_step??''}}"
                   @endif
                   @if($disabled??false)
                       disabled
                @endif>

        @endif


        @error($input_model)
        <div class="error-container">
            <small class="form-text text-danger">{{$message}}</small>
        </div>
        @enderror
    </div>
    @if($select_status_input ?? false)
        <div class="col-md-3">
            <label><i class="fa-solid fa-toggle-on"></i> {{$input_status_label??"Estado"}}</label>
            <select wire:model.lazy="{{$input_status_model ?? null}}" class="{{$aux_class??"custom-select"}} {{$background??""}} " required>
                <option disabled value="0"> {{$select_default??"NO ACCIÓN"}} </option>
                @foreach($select_options??[] as $option)
                    <option @if($select_option_title??"" != "")title="{{ $option[$select_option_title] }}"
                            @endif value="{{ $option[$select_option_value] }}">{{ $option[$select_option_view] }}</option>

                @endforeach
            </select>

            @error($input_status_model ?? null)
            <div class="error-container">
                <small class="form-text text-danger">{{$message}}</small>
            </div>
            @enderror
        </div>
    @endif

</div>
