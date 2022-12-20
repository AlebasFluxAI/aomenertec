<div class="text-{{$button_align}}">
    @if(isset($modal_content)==false)
        <button id="add"
                @if($submit??true)
                    type="submit"
                @endif
                @if($function??false)
                    wire:click="{{$function}}()"
                @endif
                class="mb-2 py-2 px-4">
            <b>
                <i class="{{$button_icon??"fa-solid fa-floppy-disk"}}"></i> {{$button_content}}
            </b>
        </button>
    @else
        @include("partials.v1.modal-confirm",[
                "modal_target"=>"confirm",
                "button_icon"=>$button_icon??"fa-solid fa-floppy-disk",
                "modal_content"=>$modal_content,
                "function"=>$function,
        ])

    @endif


</div>

