<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        @foreach($tab_titles as $index=>$tab_title)

            @if($index==0)

                <button
                    @if($tab_title["action"]??"" != "") wire:click="${{$tab_title["action"]}}"@endif
                        class="nav-link active primary-nav-link" id="nav-{{$index}}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#tab-{{$index}}"
                        type="button"
                        role="tab" aria-controls="tab-{{$index}}"
                        aria-selected={{$index==0?"true":"false"}}>{{$tab_title["title"]}}
                </button>

            @else


                <button
                        @if($tab_title["action"]??"" != "") wire:click="${{$tab_title["action"]}}"@endif
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
        @if($index==0)

            <div class="tab-pane fade show active" id="tab-{{$index}}" role="tabpanel"
                 aria-labelledby="nav-{{$index}}-tab">
                @include($tab_content["view_name"],$tab_content["view_values"])
            </div>
        @else
            <div class="tab-pane fade" id="tab-{{$index}}" role="tabpanel" aria-labelledby="nav-{{$index}}-tab">
                @include($tab_content["view_name"],$tab_content["view_values"])
            </div>
        @endif
    @endforeach
</div>
