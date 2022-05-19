
<div
    wire:key="check-{{ $id }}"
    class="form-group mb-{{$mb??2}} mt-{{$mt??0}}  col-sm-6 col-md-3">
    <input wire:model="{{$check_model}}" type="checkbox" class="btn-check" id="check-{{$id_button}}" autocomplete="off">
    <label class="btn btn-block btn-outline-success" for="check-{{$id_button}}">{{$label_name}}</label><br>
</div>
