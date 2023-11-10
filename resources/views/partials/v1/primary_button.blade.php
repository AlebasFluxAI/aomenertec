<div class="text-{{$button_align}} col-md-{{$col_with??4}} mb-2 grid-margin stretch-card">
    <a type='button' wire:click="{{$click_action}}" class="btn btn-outline-{{$class_button}}">
        <i class="{{$button_icon??"fa-solid fa-floppy-disk"}}"></i> {{$button_content}}
    </a>
</div>
