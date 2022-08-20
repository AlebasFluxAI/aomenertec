<div class="float-end mt-{{ $mt??0 }} mb-{{ $mb??0 }}">
    <br>
    <nav class=" navbar ">
        <ul class="navbar-nav">
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
                                           "icon"=>array_key_exists("button_icon",$option)?$option["button_icon"]:"",
                                           "button_content"=>$option["button_content"],
                      ])
                </li>
            @endforeach
        </ul>
    </nav>
</div>

