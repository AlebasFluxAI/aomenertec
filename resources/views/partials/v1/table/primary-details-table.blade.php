<div class="mb-1">
    <div class="detail-table">

        <table class="table table-bordered">
            <thead style="position: sticky;top: 0;z-index: 2">
            @foreach($table_info  as $info)
                @isset($info["show_column"])

                    @if($info["show_column"]==false)
                        @continue
                    @endif
                @endisset
                <tr>
                    <th class="font-[2000] uppercase" style="color:#009299;">{{$info["key"]}}</th>
                    @isset($info["type"])
                        @if($info["type"]=="text")

                            <td>{{$info["value"]}}</td>
                        @elseif($info["type"]=="image")

                            <td>
                                @include("partials.v1.image",[
                                               "image_url"=>$info["value"]
                                          ]);
                            </td>

                        @elseif($info["type"]=="image_multiple")
                            <td>
                                @foreach($info["value"] as $image)

                                    @include("partials.v1.image",[
                                                "image_url"=>$image->url
                                           ]);
                                @endforeach
                            </td>
                        @endif
                    @else
                        @if(isset($info["translate"]))
                            <td>{{__($info["translate"].".".$info["value"])}}</td>
                        @elseif(isset($info["redirect_route"]) and $info["redirect_value"])
                            <td class="link">

                                <a href="{{route($info["redirect_route"],[$info["redirect_binding"]=>$info["redirect_value"]])}}">
                                    {{$info["value"]}}</a>
                            </td>
                        @else
                            <td>{{$info["value"]}}</td>
                        @endif
                    @endisset
                </tr>
            @endforeach
        </table>

        @if(isset($edit_function))
            <div class="content-block edit-table ">
                <button wire:click="{{$edit_function}}">Editar</button>
            </div>
        @endif
    </div>
</div>
