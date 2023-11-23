<div class="mb-1">
    <div class="detail-table table-responsive">

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
                            @isset($info["money"])
                                <td> ${{number_format($info["value"],0)}} {{$info["currency"]??"COP"}}
                                </td>

                            @else
                                <td>{{$info["value"]}}</td>
                            @endisset
                        @elseif($info["type"]==\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_ARRAY)
                            <td>
                                @if(isset($info["redirect_route"]))
                                    @foreach($info["value"] as $model)

                                        <li class="link">
                                            <a href="{{route($info["redirect_route"],[$info["redirect_binding"]=>$model->equipment->{$info["redirect_value"]}])}}">
                                                Id: {{$model->equipment->id}} - Serial: {{$model->equipment->serial}}
                                                - Nombre: {{$model->equipment->name}}
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    @foreach($info["value"] as $model)

                                        <li>
                                            Id: {{$model->equipment->id}} - Serial: {{$model->equipment->serial}}
                                            - Nombre: {{$model->equipment->name}}
                                        </li>
                                    @endforeach
                                @endif

                            </td>
                        @elseif($info["type"]=="image")

                            <td>
                                @include("partials.v1.image",[
                                               "image_url"=>$info["value"]
                                          ])
                            </td>

                        @elseif($info["type"]=="image_multiple")
                            <td>
                                @foreach($info["value"] as $image)
                                    @if(\App\Models\V1\Image::validateImageFile($image))
                                        @include("partials.v1.image",[
                                                    "image_url"=>$image->url,
                                                    "description"=>$image->description
                                               ])
                                    @else
                                        @include("partials.v1.document",[
                                                   "document_url"=>$image->url,
                                                   "description"=>$image->description
                                              ])
                                    @endif
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
                            @isset($info["money"])
                                <td> ${{number_format($info["value"],0)}} {{$info["currency"]??"COP"}}
                                </td>

                            @else
                                <td>{{$info["value"]}}</td>
                            @endisset
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
