<div class="text-{{$button_align??"center"}} custom-nav-button">

    <a href="{{$target_binding?route($target_route,[$target_binding=>$target_binding_value]):route($target_route)}}"
       style="display: inline;">
        <button class="btn btn-outline-primary"><span
                class="{{$icon??""}} {{$button_icon??""}}"></span> {{$button_content}}
        </button>
    </a>
</div>
