<div class="mb-3">
    <div class="primary-content table-responsive">

        <table class="table table-bordered">
            <thead style="position: sticky;top: 0;z-index: 2">
            <tr>
                @foreach($table_headers as $header_name=>$table_header)
                    <th>{{$header_name}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>


            @isset($table_rows)
                @foreach($table_rows as $index=>$table_row)
                    <div wire:key="equipment-field-{{ $index }}"
                         class="form-group mb-2 align-content-start col-md-3 col-sm-3">
                        <tr class="shadow-sm">
                            @foreach($table_headers as $header_name=>$table_header)
                                <td>
                                    @if(str_contains($table_header,".") and !str_contains($table_header,"*"))
                                        {{ $table_row->{explode(".",$table_header)[0]}->{explode(".",$table_header)[1]} }}  {{--Se usa para traer datos de una relacion user.client.name--}}
                                    @elseif($header_name == "Serial")
                                        <div class="input-group">
                                            <input wire:model="equipment.{{ $index }}.serial"
                                                   wire:keydown.enter="assignEquipmentFirst({{$table_row['type_id']}})"
                                                   type="number" class="form-control" id="equipment.{{ $index }}.serial"
                                                   autocomplete="off" placeholder="{{ $table_row['type'] }}" required>
                                            <div class="input-group-append">
                                                 <span class="input-group-text">
                                                     @if($table_row['picked'])
                                                         <span class="badge badge-success">
                                                             <i>
                                                                 <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                      height="16" fill="currentColor"
                                                                      class="bi bi-check2" viewBox="0 0 16 16">
                                                                     <path
                                                                         d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                                                 </svg>
                                                             </i>
                                                         </span>
                                                     @else
                                                         <span class="badge badge-danger">
                                                             <i>
                                                                 <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                      height="16" fill="currentColor" class="bi bi-x-lg"
                                                                      viewBox="0 0 16 16">
                                                                     <path fill-rule="evenodd"
                                                                           d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                                                                     <path fill-rule="evenodd"
                                                                           d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                                                                 </svg>
                                                             </i>
                                                         </span>
                                                     @endif
                                                 </span>
                                            </div>
                                        </div>
                                        @error("equipment.".$index.".serial")
                                        <div class="error-container">
                                            <small class="form-text text-danger">{{$message}}</small>
                                        </div>
                                    @else
                                        @if($serials->contains('equipment_type_id', $table_row['type_id']))
                                            @if(!($table_row['picked']))
                                                @if(strlen($table_row['serial'])>= 2)

                                                    <ul class="dropdown-menu list-search">
                                                        <h6 class="dropdown-header"><b>Seleccione opción</b></h6>
                                                        @foreach($serials as $item)
                                                            <li class="dropdown-item ">
                                                                <a wire:click="assignEquipment('{{ $item->id }}')"
                                                                   type="button">
                                                                    {{ $item->serial }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                @endif
                                            @endif
                                        @else
                                            <div class="">
                                                <small class="form-text text-muted">{{$table_row['post']}}</small>
                                            </div>
                                        @endif
                                        @enderror

                                        @else
                                            {{ $table_row['type'] }}
                                        @endif
                                </td>
                            @endforeach
                        </tr>
                @endforeach
            @endisset
            </tbody>
        </table>
    </div>
    {{--@if($table_pageable??true)
        {{$table_rows->links("partials.v1.table.pagination-links")}}
    @endif
    <br><br>--}}

</div>
