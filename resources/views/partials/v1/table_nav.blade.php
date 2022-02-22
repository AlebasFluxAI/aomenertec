
<div class="table-nav-var-content">
    <nav class="navbar navbar-expand-sm bg-light">

        <ul class="navbar-nav">
            @foreach($nav_options as $option)
            <li class="nav-item">
                @include("partials.v1.primary_button",[
                                      "button_align"=>$option["button_align"],
                                      "click_action"=>$option["click_action"],
                                      "button_icon"=>$option["button_icon"],
                                      "button_content"=>$option["button_content"],
                  ])
            </li>
            @endforeach
        </ul>
    </nav>
</div>



