<div class="col-md-12">
    <label>{{$label}}</label>
    @foreach($options_list as $value)
        <div class="col-md-6">
            <input wire:change="{{$functionChanged}}('{{$value["value"]}}')" class="form-check-input"
                   type="checkbox"
                   style="font-size:18px"
                   value="" id="flexCheckDefault{{$value["value"]}}">
            <label class="form-check-label" for="flexCheckDefault" style="font-size: 16px">
                {{$value["key"]}}
            </label>
        </div>
    @endforeach
</div>
