<div class="col-3">
    <button class="btn btn-primary btn-sm"
            data-toggle="tooltip" data-placement="{{$tooltip_position??"top"}}" title="{{$tooltip_title??""}}"
            wire:click="{{$button_action}}({{$model_id}})">
        <i class="text-{{$icon_color}} {{$icon}}"></i></button>
</div>
