<nav class="navbar navbar-expand-sm table-nav" style="float: right">
    <ul class="navbar-nav">
        @foreach($nav_options as $option)
            <li>
                @include("partials.v1.primary_navigator",[
                                      "button_align"=>$option["button_align"],
                                       "target_route"=>$option["target_route"],
                                       "icon"=>array_key_exists("icon",$option)?$option["icon"]:"",
                                       "button_content"=>$option["button_content"],
                  ])
            </li>
        @endforeach
    </ul>
</nav>



