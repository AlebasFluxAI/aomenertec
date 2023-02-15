<div class="col-2">
    @isset($modal)

        <!-- Modal -->
        <a class="btn btn-redirect btn-sm" data-toggle="modal" data-target="#{{$modal_target??"modal"}}"
        >
            @if(!isset($button_content))
                <i
                    class="text-{{$icon_color??""}} {{$icon??""}}"></i>
            @endif
            {{$button_content??""}}</a>

        <div class="modal fade" id="{{$modal_target??"modal"}}" tabindex="-1" role="dialog"
             aria-labelledby="#{{$modal_target??"modal"}}"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$modal["header"]}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{$modal["body"]}}
                    </div>
                    <div class="modal-footer">
                        <div wire:loading.remove>
                            <button type="button" id="cancel" data-dismiss="modal">Cancelar</button>
                            <button type="button" id="add" data-dismiss="modal"
                                    wire:click="{{$button_action}}('{{$model_id}}')"
                            >Confirmar
                            </button>
                        </div>
                        <div wire:loading>
                            <div class="clock-loader"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        @if(is_array($button_action))
            <a class="btn btn-redirect btn-sm"
               data-toggle="tooltip" data-placement="{{$tooltip_position??"top"}}" title="{{$tooltip_title??""}}"
               wire:click="{{$button_action["button_action"]}}('{{$button_action["value"]}}')">
                {{$button_action["button_content"]}}</a>
        @else
            <a class="btn btn-redirect btn-sm"
               data-toggle="tooltip" data-placement="{{$tooltip_position??"top"}}" title="{{$tooltip_title??""}}"
               wire:click="{{$button_action}}('{{$model_id}}')">
                <i class="text-{{$icon_color}} {{$icon}}"></i></a>
        @endif

    @endisset


</div>
