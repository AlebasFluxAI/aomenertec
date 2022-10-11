<div class=" mt-{{ $mt??0 }} mb-{{ $mb??0 }} flex justify-center">
    <br>
    <nav class="col-md-8 col-sm-12 navbar justify-content-end" style="text-align: center;">
        <ul class="navbar-nav" style=" display: inline-block;	list-style: none;  list-style-type: none;">
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

