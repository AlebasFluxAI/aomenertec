<div class="text-{{$button_align??"center"}} custom-nav-button">
    <div class="dropdown dropleft">

        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span
                class="{{$icon??""}} {{$button_icon??""}}"></span> {{$button_content}}
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            @foreach($dropdown_options as $option)
                @if(!array_key_exists("actionable",$option))
                    @continue
                @endif

                @if(isset($option["actionable"]["permission"]) and \Illuminate\Support\Facades\Auth::hasUser() and
                        !array_intersect($option["actionable"]["permission"],\App\Models\V1\User::getUserModel()->getPermissions()))
                    @continue
                @endif
                @if(array_key_exists("redirect",$option["actionable"]))
                    <a class="btn btn-redirect btn-sm dropdown-item"
                       href="{{route($option["actionable"]["redirect"]["route"],
                                [$option["actionable"]["redirect"]["binding"]=>$option["actionable"]["redirect"]["value"]])}}">
                        {{$option["title"]}}</a>

                @elseif(array_key_exists("function",$option["actionable"]))
                    @include("partials.v1.table.table-action-button",[
                            "modal"=>$option["actionable"]["modal"]?? null,
                            "button_content"=>$option["actionable"]["title"]?? null,
                            "button_action"=>$option["actionable"]["function"],
                            "model_id"=>$option["actionable"]["value"]?? 1,

                    ])
                @endif
            @endforeach
        </div>
    </div>
</div>



