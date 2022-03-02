{{--

<<<<<<< HEAD
<div class="table-nav-var-content">
=======
<div class="primary-content">

>>>>>>> d2b72f5 (add table component)
    <nav class="navbar navbar-expand-sm bg-light">

        <ul class="navbar-nav">
            @foreach($nav_options as $option)


                @include("partials.v1.primary_navigator",[
                                      "button_align"=>$option["button_align"],
<<<<<<< HEAD
                                      "click_action"=>$option["click_action"],
                                      "button_icon"=>$option["button_icon"],
                                      "button_content"=>$option["button_content"],
=======
                                       "target_route"=>$option["target_route"],
                                       "icon"=>array_key_exists("icon",$option)?$option["icon"]:"",
                                       "button_content"=>$option["button_content"],
>>>>>>> d2b72f5 (add table component)
                  ])
            </li>
            @endforeach
        </ul>
    </nav>

</div>
--}}
