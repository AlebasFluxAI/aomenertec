<div class="form-group mb-{{$mb??2}} mt-{{$mt??0}} col-md-{{$col_with??12}} col-sm-12">
    <label>{{$input_label??""}} @if($tooltip_title??false)
            <span class="fas fa-circle-question"
                  data-toggle="tooltip" data-placement="{{$tooltip_position??"top"}}"
                  title="{{$tooltip_title??""}}"
            ></span>
        @endif</label>
    <div class="input-group">
        @isset($icon_class)
            <div class="input-group-prepend">
                                    <span class="input-group-text">
                                     <i class="{{$icon_class}}"></i>
                                    </span>
            </div>
        @endisset
        @if($input_rows??1>1)
            <textarea @if($updated_input??""=="lazy")
                          wire:model.lazy="{{ $input_model }}"
                      @elseif($updated_input??""=="defer")
                          wire:model.defer="{{ $input_model }}"
                      @else

                          wire:model="{{ $input_model }}"
                      @endif
                      rows="{{$input_rows}}" type="{{$input_type??"text"}}"
                      class="form-control" autocomplete="on" placeholder="{{$placeholder??""}}"
                      @if($required??false)
                          required
                @endif></textarea>
        @elseif($input_type=="checkbox")
            <div class="form-check form-switch ml-3">
                <input
                    wire:model.lazy="{{$input_model}}"
                    class="form-check-input" type="checkbox"
                    id="flexSwitchCheckChecked">
            </div>
        @elseif($input_type=="select")
            <select wire:model.lazy="{{$input_model}}" class="{{$aux_class??"custom-select"}} {{$background??""}} "
                    @if($required??false)
                        required
                    @endif
                    @if($disabled??false)disabled @endif>
                <option disabled value="0"> {{$select_default??""}} </option>
                @foreach($select_options??[] as $option)
                    <option @if($select_option_title??"" != "")title="{{ $option[$select_option_title] }}"
                            @endif value="{{ $option[$select_option_value] }}">{{ $option[$select_option_view] }}</option>

                @endforeach
            </select>
        @else
            <input @if(($updated_input??null)=="lazy")
                       wire:model.lazy="{{ $input_model }}"
                   @elseif(($updated_input??null)=="defer")
                       wire:model.defer="{{ $input_model }}"
                   @else
                       wire:model="{{ $input_model }}"
                   @endif
                   @if(($input_type??"text") == "number")
                       min="{{ $number_min??''}}" max="{{ $number_max??''}}" step="{{ $number_step??''}}"
                   @endif
                   id="{{$input_id??""}}" type="{{$input_type??"text"}}"
                   class="form-control" autocomplete="{{$autocomplete??"on"}}"
                   name="{{$input_name??""}}" onchange="{{$input_on_change??""}}()" placeholder="{{$placeholder??""}}"

                   @if($disabled??false)disabled @endif
                   @if($required??false)
                       required
                @endif
            >
        @endif
    </div>
    @error($input_model)
    <div class="error-container">
        <small class="form-text text-danger">{{$message}}</small>
    </div>
    @enderror
</div>
