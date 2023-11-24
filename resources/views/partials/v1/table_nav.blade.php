<div class=" mt-{{ $mt??0 }} mb-{{ $mb??0 }} flex justify-center">
    <br>
    <nav class="navbar navbar-expand-lg" style="margin-left: 80%">
        <ul class="navbar-nav">
            @foreach($nav_options as $option)

                @if(isset($option["permission"]) and !array_intersect($option["permission"],\App\Models\V1\User::getUserModel()->getPermissions()))
                    @continue
                @endif
                <li>
                    @if(array_key_exists("button_type",$option) and $option["button_type"]=="dropdown")

                        @include("partials.v1.dropdowns_navigator",[
                                        "button_content"=>$option["button_content"],
                                        "dropdown_options"=>$option["button_options"],
                                        "button_icon"=>$option["button_icon"],
                                    ])
                    @elseif(array_key_exists("button_type",$option) and $option["button_type"]=="dropdown_filter")
                        @include("partials.v1.dropdowns_navigator",[
                                        "button_content"=>$option["button_content"],
                                        "dropdown_options"=>$option["button_options"],
                                        "button_icon"=>$option["button_icon"],
                                    ])
                    @else
                        @include("partials.v1.primary_navigator",[
                                              "button_align"=>$option["button_align"],
                                               "target_route"=>$option["target_route"],
                                               "target_binding"=>$option["target_binding"]??null,
                                               "target_binding_value"=>$option["target_binding_value"]??null,
                                               "icon"=>$option["button_icon"]??($option["icon"]??null),
                                               "button_content"=>$option["button_content"],
                          ])

                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
</div>

