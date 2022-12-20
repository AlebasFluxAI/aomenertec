<!-- Modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#{{$modal_target}}"
        id="add"
        class="mb-2 py-2 px-4">
    <b>
        <i class="{{$button_icon??"fa-solid fa-floppy-disk"}}"></i> {{$button_content}}
    </b>
</button>

<div class="modal fade" id="{{$modal_target}}" tabindex="-1" role="dialog" aria-labelledby="#{{$modal_target}}"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar accion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{$modal_content}}
            </div>
            <div class="modal-footer">
                <div wire:loading.remove>
                    <button type="button" id="cancel" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="add"
                            @if($submit??true)
                                type="submit"
                            @endif
                            @if($function??false)
                                wire:click="{{$function}}()"
                        @endif
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
