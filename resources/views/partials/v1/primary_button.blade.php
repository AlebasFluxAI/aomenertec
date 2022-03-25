
<div class="text-{{$button_align}}">
    <button type='button'  wire:click="{{$click_action}}" class="{{$class_button??"b-success"}}">
            <i class="{{$button_icon??"fa-solid fa-floppy-disk"}}"></i> {{$button_content}}
    </button>
</div>
