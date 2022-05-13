
<div
    wire:key="check-{{ $id }}"
    class="form-group mb-{{$mb??2}} mt-{{$mt??0}}  col-sm-auto col-md-auto">
    <input wire:model="{{$check_model}}" type="checkbox" class="btn-check" id="check-{{$id_button}}" autocomplete="off">
    <label class="btn btn-outline-success" for="check-{{$id_button}}">{{$label_name}}</label><br>
</div>
