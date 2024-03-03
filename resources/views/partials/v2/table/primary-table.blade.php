<div class="{{ $class_container??'contenedor-grande' }}">
    <div class="primary-content table-responsive">
        <table class="table table-bordered custom-table">
            <thead style="position: sticky;top: 0;z-index: 2">
            <tr>
                @if(($table_checkable??false))
                    @if($table_checkable_blank??false)
                        <th>

                        </th>
                    @else
                        <th style="width:10%">
                            <div class="form-check">
                                <input wire:model="selectedAll" class="form-check-input" type="checkbox"
                                    {{count($table_rows)==0?"disabled":""}}
                                >
                            </div>
                        </th>
                    @endif
                @endif
                @foreach($table_headers as $table_header)
                    <th style="width:10%">
                        <div class="container">
                            <div class="row">

                                <div class="col-md-8 text-bold text-center tb-headers">
                                    {{$table_header["col_name"]}}
                                </div>
                                <div class="col-md-2">
                                    @if($is_filtered??true)
                                        @if($table_header["col_filter"])
                                            @include("partials.v1.table.table-filter-column",[
                                                                               "col_type"=>array_key_exists("col_type",$table_header)?$table_header["col_type"]:null,
                                                                               "col_name"=>$table_header["col_data"]
                                                                             ])
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </th>
                @endforeach
                @isset($table_actions)
                    <th class="th-acciones" style="text-align: center; font-weight: bold;">Acciones</th>
                @endisset
            </tr>
            </thead>
            <tbody>


            @isset($table_rows)
                @if(count($table_rows)==0)
                    <tr>
                        <td colspan="{{count($table_headers)+2}}">
                            <div class="text-center">
                                <i class="fa-solid fa-inbox empty-table"></i>
                                <p class="empty-table-text">{{$table_empty_text??"No existen registros"}}</p>
                            </div>
                        </td>
                    </tr>

                @endif
                @foreach($table_rows as $index=>$table_row)
                    <tr class="shadow-sm">
                        @if($table_checkable??false)

                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" wire:model="selectedRows" type="checkbox"
                                           value="{{ $table_row->{$table_headers[0]["col_data"]} }}"
                                           id="flexCheckDefault">
                                </div>
                            </td>
                        @endif
                        @foreach($table_headers as $table_header)

                            <td style="{{isset($row_color_function)?"background-color: ".$this->{$row_color_function}($table_row):''}}"
                            >
                                @if(str_contains($table_header["col_data"],".") and !str_contains($table_header["col_data"],"*") and $table_row->{explode(".",$table_header["col_data"])[0]})
                                    @if(isset($table_header["col_data_function"]))
                                        @include("partials.v2.table.primary-table-column",[
                                               "col_data"=>$table_row->{explode(".",$table_header["col_data"])[0]}->{explode(".",$table_header["col_data"])[1]}(),
                                               "col_type"=>array_key_exists("col_type",$table_header)?$table_row->{$table_header["col_type"]}:"",
                                               "col_translate"=>$table_header["col_translate"]??null,
                                               "col_redirect_url"=>null,
                                               "col_money"=>$table_header["col_money"]??null,
                                               "col_currency"=>(array_key_exists("col_currency",$table_header)?$table_row->{$table_header["col_currency"]}:(array_key_exists("col_currency_custom",$table_header)?$table_header["col_currency_custom"]:"")),
                                           ])
                                    @else

                                        @include("partials.v2.table.primary-table-column",[
                                                  "col_data"=>$table_row->{explode(".",$table_header["col_data"])[0]}->{explode(".",$table_header["col_data"])[1]},
                                                  "col_type"=>array_key_exists("col_type",$table_header)?$table_row->{$table_header["col_type"]}:"",
                                                  "col_translate"=>$table_header["col_translate"]??null,
                                                  "col_redirect_url"=>null,
                                                  "col_money"=>$table_header["col_money"]??null,
                                                   "col_currency"=>(array_key_exists("col_currency",$table_header)?$table_row->{$table_header["col_currency"]}:(array_key_exists("col_currency_custom",$table_header)?$table_header["col_currency_custom"]:"")),
                                              ])
                                    @endif
                                @else
                                    @if(isset($table_header["col_data_component_function"]))
                                        @include("partials.v2.table.primary-table-column",[
                                               "col_data"=>$this->{$table_header["col_data"]}($table_row->{$table_headers[0]["col_data"]}),
                                               "col_type"=>array_key_exists("col_type",$table_header)?$table_header["col_type"]:"",
                                               "col_translate"=>$table_header["col_translate"]??null,
                                               "col_redirect_url"=>null,
                                               "col_money"=>$table_header["col_money"]??null,
                                               "col_currency"=>(array_key_exists("col_currency",$table_header)?$table_row->{$table_header["col_currency"]}:(array_key_exists("col_currency_custom",$table_header)?$table_header["col_currency_custom"]:"")),
                                           ])
                                    @elseif(isset($table_header["col_data_function"]))

                                        @include("partials.v2.table.primary-table-column",[
                                      "col_data"=>$table_row->{$table_header["col_data"]}(),
                                      "col_array_data"=>$table_header["col_array_data"]??"",
                                      "col_type"=>array_key_exists("col_type",$table_header)?$table_header["col_type"]:"",
                                      "col_translate"=>$table_header["col_translate"]??null,
                                      "col_redirect_url"=>null,
                                      "col_money"=>$table_header["col_money"]??null,
                                      "col_currency"=>(array_key_exists("col_currency",$table_header)?$table_row->{$table_header["col_currency"]}:(array_key_exists("col_currency_custom",$table_header)?$table_header["col_currency_custom"]:"")),
                                  ])
                                    @else
                                        @include("partials.v2.table.primary-table-column",[
                                        "col_data"=>$table_row->{$table_header["col_data"]},
                                        "col_array_data"=>$table_header["col_array_data"]??"",
                                        "col_type"=>array_key_exists("col_type",$table_header)?$table_header["col_type"]:"",
                                        "col_translate"=>$table_header["col_translate"]??null,
                                        "col_redirect_url"=>array_key_exists("col_redirect_url",$table_header)?$table_row->{$table_header["col_redirect_url"]}:null,
                                        "col_money"=>$table_header["col_money"]??null,
                                        "col_currency"=>(array_key_exists("col_currency",$table_header)?$table_row->{$table_header["col_currency"]}:(array_key_exists("col_currency_custom",$table_header)?$table_header["col_currency_custom"]:"")),
                                    ])
                                    @endif
                                @endif

                            </td>
                        @endforeach
                        @isset($table_actions)

                            <td id="table-action-cell"
                                style="{{isset($row_color_function)?"background-color: ".$this->{$row_color_function}($table_row):''}}"
                            >
                                <div class="container-fluid">
                                    <div class="row">
                                        @foreach($table_actions as $action_type=>$action_value)
                                            <div wire:loading>
                                                <div class="clock-loader"></div>
                                            </div>
                                            <div class="row" wire:loading.remove>
                                                @if($action_type=="edit")
                                                    @include("partials.v1.table.table-action-button",[
                                                                "button_action"=>$action_value,
                                                                "icon_color"=>"secondary",
                                                                "model_id"=>$table_row->{$table_headers[0]["col_data"]},
                                                                "icon"=>"fas fa-pencil",
                                                                "tooltip_title"=>"Editar"

                                                            ])
                                                @elseif($action_type=="delete")
                                                    @include("partials.v1.table.table-action-button",[
                                                             "button_action"=>$action_value,
                                                             "icon_color"=>"secondary",
                                                             "model_id"=>$table_row->{$table_headers[0]["col_data"]},
                                                             "icon"=>"fas fa-trash",
                                                             "tooltip_title"=>"Eliminar"
                                                         ])

                                                @elseif($action_type=="details")
                                                    @include("partials.v1.table.table-action-button",[
                                                             "button_action"=>$action_value,
                                                             "icon_color"=>"secondary",
                                                             "model_id"=>$table_row->{$table_headers[0]["col_data"]},
                                                             "icon"=>"fas fa-search",
                                                             "tooltip_title"=>"Detalles"
                                                         ])
                                                @elseif($action_type=="customs")
                                                    @foreach($action_value  as $custom)

                                                        @if(array_key_exists("limit_roles",$custom))
                                                            @unlessrole($custom["limit_roles"])
                                                            @continue
                                                            @endunlessrole
                                                        @endif
                                                        @if(isset($custom["permission"]) and \Illuminate\Support\Facades\Auth::hasUser() and !array_intersect($custom["permission"],\App\Models\V1\User::getUserModel()->getPermissions()))
                                                            @continue
                                                        @endif

                                                        @if(array_key_exists("conditional",$custom) and $this->{$custom["conditional"]}(isset($custom["model_id"])?$table_row->{$custom["model_id"]}:
                                                                            $table_row->{$table_headers[0]["col_data"]}))
                                                            @continue
                                                        @endif
                                                        @if(array_key_exists("conditionalModel",$custom) and $this->{$custom["conditionalModel"]}($table_row))
                                                            @continue
                                                        @endif

                                                        @if(array_key_exists("redirect",$custom))
                                                            @include("partials.v1.table.table-redirect-button",[
                                                                     "button_route"=>$custom["redirect"]["route"],
                                                                     "button_binding"=>array_key_exists("binding",$custom["redirect"])?$custom["redirect"]["binding"]:"",
                                                                     "redirect_values"=>array_key_exists("extra_params",$custom["redirect"])?$custom["redirect"]["extra_params"]:[],
                                                                     "icon_color"=>"secondary",
                                                                     "model_id"=> array_key_exists("binding_value",$custom["redirect"])?$table_row->{$custom["redirect"]["binding_value"]} : ((isset($custom["model_id"]))?$table_row->{$custom["model_id"]}:
                                                                        $table_row->{$table_headers[0]["col_data"]}),
                                                                     "icon"=>$custom["icon"],
                                                                     "tooltip_title"=>$custom["tooltip_title"] ?? '',
                                                                     "button_subdomain"=>array_key_exists("button_subdomain",$custom)?$custom["button_subdomain"]:\Illuminate\Support\Facades\Route::input("subdomain")
                                                                 ])
                                                        @else
                                                            @if(array_key_exists("payment_button",$custom))
                                                                @include("partials.v1.payment.payment_button_only_form",[
                                                                    "total"=>$table_row->total,
                                                                    "reference"=>$table_row->code,
                                                                    "email"=>$table_row->client->email,
                                                                    "customer_last_name"=>$table_row->client->last_name,
                                                                    "customer_name"=>$table_row->client->name,
                                                                    "customer_phone"=>$table_row->client->phone,
                                                                    "customer_identification"=>$table_row->client->identification,
                                                                    "customer_identification_type"=>$table_row->client->identification_type,
                                                                    "public_key"=>$table_row->client->networkOperator->wompiCredentials?$table_row->client->networkOperator->wompiCredentials->wompiSecret:config("wompi.wompi_default_public")
                                                                 ])
                                                                @continue
                                                            @endif
                                                            @if(array_key_exists("modal",$custom))
                                                                @include("partials.v1.table.table-action-button",[
                                                                     "button_action"=>$custom["function"],
                                                                     "icon_color"=>"secondary",
                                                                     "model_id"=>isset($custom["model_id"])?$table_row->{$custom["model_id"]}:
                                                                        $table_row->{$table_headers[0]["col_data"]},
                                                                     "icon"=>$custom["icon"],
                                                                     "modal"=>$custom["modal"],
                                                                     "tooltip_title"=>$custom["tooltip_title"] ?? ''
                                                                 ])
                                                            @else
                                                                @include("partials.v1.table.table-action-button",[
                                                                       "button_action"=>$custom["function"],
                                                                       "icon_color"=>"secondary",
                                                                       "model_id"=>isset($custom["model_id"])?$table_row->{$custom["model_id"]}:
                                                                          $table_row->{$table_headers[0]["col_data"]},
                                                                       "icon"=>$custom["icon"],
                                                                       "tooltip_title"=>$custom["tooltip_title"] ?? ''
                                                                   ])
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
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
        @if(method_exists($table_rows,"links"))
            {{$table_rows->links("partials.v1.table.pagination-links")}}
        @endif
    @endif
    <br><br>
</div>
