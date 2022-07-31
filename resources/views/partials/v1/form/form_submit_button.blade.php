<br>
<div class="text-{{$button_align}}">

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

</div>

