<div class="form-check col-md-{{$col_width??4}} ml-{{$ml??4}}">

    <input wire:model="{{$input_model}}" class="form-check-input" type="checkbox"
           value="" id="flexCheckDefault{{$input_model}}">
    <label class="form-check-label" for="flexCheckDefault" style="font-size: 16px">
        {{$input_label}}
    </label>
</div>
