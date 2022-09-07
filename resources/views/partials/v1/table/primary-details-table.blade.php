<div class="mb-1">
    <div class="detail-table">

        <table class="table table-bordered">
            <thead style="position: sticky;top: 0;z-index: 2">
            @foreach($table_info  as $info)
                <tr>
                    <th>{{$info["key"]}}</th>
                    @isset($info["type"])
                        @if($info["type"]=="text")

                            <td>{{$info["value"]}}</td>
                        @elseif($info["type"]=="image")

                            <td>
                                <img src='{{$info["value"]}}' class="rounded img-fluid" alt="Logo" width="150px"
                                     height="150px">
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
