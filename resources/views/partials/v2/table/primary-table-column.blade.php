@if(($col_type?:"")== \App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN)
    <div class="text-center">
        <span class="{{$col_data?"dot-success":"dot-warning"}}"></span>
    </div>
@elseif(($col_type?:"")== \App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN_INVERSE)
    <div class="text-center">
        <span class="{{(!$col_data)?"dot-success":"dot-warning"}}"></span>
    </div>
@else
    {{$col_data}}

@endif
