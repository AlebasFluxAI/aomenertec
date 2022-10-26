<div class="mb-1">
    <div class="primary-content table-responsive">
        <table class="table table-bordered">
            <thead style="position: sticky;top: 0;z-index: 2">
            <tr>
                @foreach($table_headers as $header_name=>$table_header)
                    <th>{{$header_name}}
                    </th>
                @endforeach
                @isset($table_actions)
                    <th>Acciones</th>
                @endisset
            </tr>
            </thead>
            <tbody>


            @isset($table_rows)
                @foreach($table_rows as $index=>$table_row)
                    <tr class="shadow-sm">
                        @foreach($table_headers as $header_name=>$table_header)
                            <td>
                                @if(str_contains($table_header,".") and !str_contains($table_header,"*") and $table_row->{explode(".",$table_header)[0]})

                                    {{ $table_row->{explode(".",$table_header)[0]}->{explode(".",$table_header)[1]} }}  {{--Se usa para traer datos de una relacion user.client.name--}}
                                @else
                                    {{ $table_row->{$table_header} }}
                                @endif
                            </td>
                        @endforeach
                        @isset($table_actions)
                            <td id="table-action-cell">
                                <div class="container">
                                    <div class="row">
                                        @foreach($table_actions as $action_type=>$action_value)
                                            @if($action_type=="edit")
                                                @include("partials.v1.table.table-action-button",[
                                                            "button_action"=>$action_value,
                                                            "icon_color"=>"secondary",
                                                            "model_id"=>$table_row->{$table_headers[array_keys($table_headers)[0]]},
                                                            "icon"=>"fas fa-pencil",
                                                            "tooltip_title"=>"Editar"

                                                        ])
                                            @elseif($action_type=="delete")
                                                @include("partials.v1.table.table-action-button",[
                                                         "button_action"=>$action_value,
                                                         "icon_color"=>"secondary",
                                                         "model_id"=>$table_row->{$table_headers[array_keys($table_headers)[0]]},
                                                         "icon"=>"fas fa-trash",
                                                         "tooltip_title"=>"Eliminar"
                                                     ])

                                            @elseif($action_type=="details")
                                                @include("partials.v1.table.table-action-button",[
                                                         "button_action"=>$action_value,
                                                         "icon_color"=>"secondary",
                                                         "model_id"=>$table_row->{$table_headers[array_keys($table_headers)[0]]},
                                                         "icon"=>"fas fa-search",
                                                         "tooltip_title"=>"Detalles"
                                                     ])
                                            @elseif($action_type=="customs")
                                                @foreach($action_value  as $custom)
                                                    @if(array_key_exists("conditional",$custom) and $this->{$custom["conditional"]}(isset($custom["model_id"])?$table_row->{$custom["model_id"]}:
                                                                    $table_row->{$table_headers[array_keys($table_headers)[0]]}))
                                                    @else
                                                        @if(array_key_exists("popup",$custom))
                                                            @include("partials.v1.table.table-popup-button",[
                                                                                "icon_color"=>"secondary",
                                                                                "modal_title"=>$custom["popup"]["modal_title"],
                                                                                "view_name"=>$custom["popup"]["view_name"],
                                                                                "view_data"=>$custom["popup"]["view_data"],
                                                                ])
                                                            @continue
                                                        @endif
                                                        @if(array_key_exists("redirect",$custom))
                                                            @include("partials.v1.table.table-redirect-button",[
                                                                    "button_route"=>$custom["redirect"]["route"],
                                                                    "button_binding"=>$custom["redirect"]["binding"],
                                                                    "icon_color"=>"secondary",
                                                                    "model_id"=>
                                                                    (isset($custom["model_id"])?($table_row->{$custom["model_id"]}):(
                                                                        (str_contains($table_headers[array_keys($table_headers)[0]],".") and !str_contains($table_headers[array_keys($table_headers)[0]],"*") and $table_row->{explode(".",$table_headers[array_keys($table_headers)[0]])[0]})?
                                                                          $table_row->{explode(".",$table_headers[array_keys($table_headers)[0]])[0]}->{explode(".",$table_headers[array_keys($table_headers)[0]])[1]}:
                                                                          $table_row->{$table_headers[array_keys($table_headers)[0]]})),
                                                                    "icon"=>$custom["icon"],
                                                                    "tooltip_title"=>$custom["tooltip_title"] ?? ''
                                                                ])
                                                        @else
                                                            @include("partials.v1.table.table-action-button",[
                                                                    "button_action"=>$custom["function"],
                                                                    "icon_color"=>"secondary",
                                                                     "model_id"=>
                                                                      (isset($custom["model_id"])?($table_row->{$custom["model_id"]}):(
                                                                          (str_contains($table_headers[array_keys($table_headers)[0]],".") and !str_contains($table_headers[array_keys($table_headers)[0]],"*") and $table_row->{explode(".",$table_headers[array_keys($table_headers)[0]])[0]})?
                                                                            $table_row->{explode(".",$table_headers[array_keys($table_headers)[0]])[0]}->{explode(".",$table_headers[array_keys($table_headers)[0]])[1]}:
                                                                            $table_row->{$table_headers[array_keys($table_headers)[0]]})),
                                                                    "icon"=>$custom["icon"],
                                                                    "tooltip_title"=>$custom["tooltip_title"] ?? ''
                                                                ])
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                        @endisset
                    </tr>
                @endforeach
            @endisset
            </tbody>
        </table>
    </div>
    @if($table_pageable??true)
        {{$table_rows->links("partials.v1.table.pagination-links")}}
    @endif
    <br><br>
</div>
