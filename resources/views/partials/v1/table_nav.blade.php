<div class="col-md-3 offset-10">
    <nav class=" navbar navbar-expand-sm table-nav">
        <ul class="navbar-nav">
            @foreach($nav_options as $option)
                <li>
                    @include("partials.v1.primary_navigator",[
                                          "button_align"=>$option["button_align"],
                                           "target_route"=>$option["target_route"],
                                           "icon"=>array_key_exists("button_icon",$option)?$option["button_icon"]:"",
                                           "button_content"=>$option["button_content"],
                      ])
                </li>
            @endforeach
        </ul>
    </nav>
</div>
