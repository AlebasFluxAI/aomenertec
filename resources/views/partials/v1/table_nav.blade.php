<div class=" mt-{{ $mt??0 }} mb-{{ $mb??0 }} flex justify-center">
    <br>
    <nav class="col-12 navbar" style="text-align: center;">
        <ul class="navbar-nav d-flex w-100 flex-row justify-content-between" style=" display: inline-block;	list-style: none;  list-style-type: none;">
            <button class="btn btn-outline-primary mb-5" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-backspace-fill" viewBox="0 0 16 16">
                    <path d="M15.683 3a2 2 0 0 0-2-2h-7.08a2 2 0 0 0-1.519.698L.241 7.35a1 1 0 0 0 0 1.302l4.843 5.65A2 2 0 0 0 6.603 15h7.08a2 2 0 0 0 2-2V3zM5.829 5.854a.5.5 0 1 1 .707-.708l2.147 2.147 2.146-2.147a.5.5 0 1 1 .707.708L9.39 8l2.146 2.146a.5.5 0 0 1-.707.708L8.683 8.707l-2.147 2.147a.5.5 0 0 1-.707-.708L7.976 8 5.829 5.854z"/>
                </svg>
            </button>
            @foreach($nav_options as $option)
                @if(isset($option["permission"]) and !array_intersect($option["permission"],\App\Models\V1\User::getUserModel()->getPermissions()))
                    @continue
                @endif
                <li>
                    @include("partials.v1.primary_navigator",[
                                          "button_align"=>$option["button_align"],
                                           "target_route"=>$option["target_route"],
                                           "target_binding"=>$option["target_binding"]??null,
                                           "target_binding_value"=>$option["target_binding_value"]??null,
                                           "icon"=>$option["button_icon"]??($option["icon"]??null),
                                           "button_content"=>$option["button_content"],
                      ])
                </li>
            @endforeach
        </ul>
    </nav>
</div>

