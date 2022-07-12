@if(($col_type?:"")== \App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN)
    <div class="text-center">
        <span class="{{$col_data?"dot-success":"dot-warning"}}"></span>
    </div>
@elseif(($col_type?:"")== \App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN_INVERSE)
    <div class="text-center">
        <span class="{{(!$col_data)?"dot-success":"dot-warning"}}"></span>
    </div>
@elseif(($col_type?:"")== \App\Http\Resources\V1\ColTypeEnum::COL_TYPE_ARRAY_CLIENT_NOTIFICATION)
    <div class="text-left">
        <a style="color: teal"
           href="{{route($col_data["target"],[$col_data["binding"]=>$col_data["binding_id"]])}}">
            {{array_key_exists($col_array_data,$col_data)?$col_data[$col_array_data]:""}} </a>
    </div>

@elseif($col_translate)
    {{__($col_translate.".".$col_data)}}
@else
    {{$col_data}}

@endif
