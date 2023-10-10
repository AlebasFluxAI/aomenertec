@if(($col_type?:"")== \App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN)
    <div class="text-center">
        <span class="{{$col_data?"dot-success":"dot-warning"}}"></span>
    </div>
@elseif(($col_type?:"")== \App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN_INVERSE)
    <div class="text-center">
        <span class="{{(!$col_data)?"dot-success":"dot-warning"}}"></span>
    </div>
@elseif(($col_type?:"")== \App\Http\Resources\V1\ColTypeEnum::COL_TYPE_ARRAY)
    <div class="text-center">
        @foreach(json_decode($col_data) as $key=>$array_value)
            <li>
                <b>{{$key}}</b>
                <p>{{$array_value}}</p>
            </li>
        @endforeach
    </div>
@elseif(($col_type?:"")== \App\Http\Resources\V1\ColTypeEnum::COL_TYPE_ARRAY_CLIENT_NOTIFICATION)
    <div class="text-left">
        <a style="color: teal"
           href="{{route($col_data["target"],[$col_data["binding"]=>$col_data["binding_id"]])}}">
            {{array_key_exists($col_array_data,$col_data)?$col_data[$col_array_data]:""}} </a>
    </div>

@elseif($col_redirect_url)
    <a style="color: #0a53be" href="{{$col_redirect_url}}">{{$col_data}}</a>
@elseif($col_translate)
    {{__($col_translate.".".$col_data)}}
@elseif($col_money)

    ${{\App\Http\Resources\V1\Formatter::currencyFormat($col_data,$col_currency)}}
@else
    {{$col_data}}

@endif
