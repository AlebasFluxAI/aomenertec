<nav wire:ignore>

    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        @foreach($tab_titles as $index=>$tab_title)
            @php
                $permission_failed[$index] = false
            @endphp
            @foreach($tab_title["permissions"]??[] as $modelPermission=>$permission)
                @if(\App\Models\V1\User::getUserModel()::class==$modelPermission)

                    @if(!($tab_title["conditionable"]??false) and !(\App\Models\V1\User::getUserModel()->tabPermissionExist($permission)))

                        @php
                            $permission_failed[$index] = true
                        @endphp
                    @else
                        @php
                            $permission_failed[$index] = false
                        @endphp
                    @endif
                    @if($tab_title["conditionable"]??false)

                        @if(!(\App\Models\V1\User::getUserModel()->tabPermissionConditionableExist($permission,$model)))
                            @php
                                $permission_failed[$index] = true
                            @endphp
                        @else
                            @php
                                $permission_failed[$index] = false
                            @endphp
                        @endif
                    @else
                        @if(!(\App\Models\V1\User::getUserModel()->tabPermissionExist($permission)))
                            @php
                                $permission_failed[$index] = true
                            @endphp
                        @else
                            @php
                                $permission_failed[$index] = false
                            @endphp
                        @endif
                    @endif
                @endif
            @endforeach
            @if($permission_failed[$index])
                @continue
            @endif
            @if($index == array_search(false, $permission_failed))

                <button wire:ignore
                        @if($tab_title["action"]??"" != "") wire:click="${{$tab_title["action"]}}" @endif
                        class="nav-link active primary-nav-link" id="nav-{{$index}}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-{{$index}}"
                        type="button"
                        role="tab" aria-controls="tab-{{$index}}"
                        aria-selected={{$index == array_search(false, $permission_failed)?"true":"false"}}>{{$tab_title["title"]}}
                </button>

            @else

                <button wire:ignore
                        @if($tab_title["action"]??"" != "") wire:click="${{$tab_title["action"]}}" @endif
                        class="nav-link primary-nav-link" id="nav-{{$index}}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-{{$index}}"
                        type="button"
                        role="tab" aria-controls="tab-{{$index}}"
                        aria-selected="false">{{$tab_title["title"]}}
                </button>

            @endif

        @endforeach

    </div>
</nav>
<div class="tab-content" id="myTabContent">

    @foreach($tab_contents as $index=>$tab_content)
        @if($permission_failed[$index])
            @continue
        @endif
        @if($index == array_search(false, $permission_failed))

            <div wire:ignore.self class="tab-pane contenedor-grande fade show active" id="tab-{{$index}}"
                 role="tabpanel"
                 aria-labelledby="nav-{{$index}}-tab">
                @if(array_key_exists("component_class",$tab_content))
                    @livewire($tab_content["component_class"],$tab_content["component_values"])
                @else
                    @include($tab_content["view_name"],$tab_content["view_values"])
                    @if($logout_button??false)
                        @include("partials.v1.table_nav",
                                ["mt"=>2,"nav_options"=>[
                                 ["button_align"=>"right",
                                 "click_action"=>"",
                                 "button_content"=>"Cerrar sesión",
                                 "button_icon"=>"fa-solid fa-right-from-bracket",
                                 "target_route"=>"logout",
                                 ],
                             ]
                        ])
                    @endif
                @endif
            </div>
        @else
            <div wire:ignore.self class="contenedor-grande tab-pane fade" id="tab-{{$index}}"
                 role="tabpanel"
                 aria-labelledby="nav-{{$index}}-tab">

                @if(array_key_exists("component_class",$tab_content))
                    @livewire($tab_content["component_class"],$tab_content["component_values"])
                @else
                    @include($tab_content["view_name"],$tab_content["view_values"])
                @endif
            </div>
        @endif
    @endforeach
</div>
