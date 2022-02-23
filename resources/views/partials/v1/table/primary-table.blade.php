<div class="mb-3">
<div class="primary-content table-wrapper-scroll-y my-custom-scrollbar">

<table class="table table-striped table-bordered ">
    <thead style="position: sticky;top: 0;z-index: 2" class="thead-light">
    <tr>
        @foreach($table_headers as $header_name=>$table_header)
        <th>{{$header_name}}</th>
        @endforeach
        @isset($table_actions)
                <th>Acciones</th>
        @endisset
    </tr>
    </thead>
    <tbody>


    @isset($table_rows)
    @foreach($table_rows as $index=>$table_row)
    <tr>
        @foreach($table_headers as $header_name=>$table_header)
            <td>
                @if(str_contains($table_header,".") and !str_contains($table_header,"*"))
                    {{ $table_row->{explode(".",$table_header)[0]}->{explode(".",$table_header)[1]} }}  {{--Se usa para traer datos de una relacion user.client.name--}}
                @else
                    {{$table_row->{$table_header} }}
                @endif
            </td>
        @endforeach
            @isset($table_actions)
                <td>
                    <div class="row text-center">
                    @foreach($table_actions as $action_type=>$action_value)
                        @if($action_type=="edit")
                            @include("partials.v1.table.table-action-button",[
                                        "button_action"=>$action_value,
                                        "icon_color"=>"primary",
                                        "model_id"=>$table_row->{$table_headers[array_keys($table_headers)[0]]},
                                        "icon"=>"fas fa-pencil"
                                    ])
                            @elseif($action_type=="delete")
                            @include("partials.v1.table.table-action-button",[
                                     "button_action"=>$action_value,
                                     "icon_color"=>"danger",
                                     "model_id"=>$table_row->{$table_headers[array_keys($table_headers)[0]]},
                                     "icon"=>"fas fa-trash"
                                 ])
                            @endif
                    @endforeach
                        </div>
                </td>
            @endisset
    </tr>
    @endforeach
    @endisset
    </tbody>
</table>
</div>
{{$table_rows->links("partials.v1.table.pagination-links")}}
<br><br>
</div>
