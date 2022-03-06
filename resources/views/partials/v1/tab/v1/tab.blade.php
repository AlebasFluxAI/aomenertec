<ul class="nav nav-tabs" id="custom-tab" role="tablist">

    @foreach($tab_titles as $index=>$tab_title)
        <li class="nav-item" role="presentation">
            @if($index==0)
                <button class="nav-link active" id="home-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-{{$index}}"
                        type="button"
                        role="tab" aria-controls="tab-{{$index}}"
                        aria-selected={{$index==0?"true":"false"}}>{{$tab_title["title"]}}
                </button>
        </li>
        @else

            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-{{$index}}"
                        type="button"
                        role="tab" aria-controls="tab-{{$index}}" aria-selected="false">{{$tab_title["title"]}}
                </button>
            </li>
        @endif

    @endforeach

</ul>
<div class="tab-content" id="myTabContent">

    @foreach($tab_contents as $index=>$tab_content)
        @if($index==0)
            <div class="tab-pane fade show active" id="tab-{{$index}}" role="tabpanel" aria-labelledby="home-tab">
                @include($tab_content["view_name"],$tab_content["view_values"])
            </div>
        @else
            <div class="tab-pane fade" id="tab-{{$index}}" role="tabpanel" aria-labelledby="home-tab">
                @include($tab_content["view_name"],$tab_content["view_values"])
            </div>
        @endif
    @endforeach
</div>
