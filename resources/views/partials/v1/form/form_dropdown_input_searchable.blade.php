{{--
"icon_class"=>Clase de font awesome para icono del input "fas fa-user"
"placeholder"=> "Placeholder para el input",
"col_with"=> "Tamaño de la columna bootstrap",
"dropdown_model"=>"variable que tendra el valor del input wire:model del componente",
"dropdown_enter_function"=>"funcion a ejecutar cuando se de enter al input",
"picked_variable"=> "variable a usar para validar si una opcion es seleccionada (boolean)",
"dropdown_results"=> "variable que contiene el array de resultados para la consulta del input (array)",
"selected_value_function"=> "funcion a ejecutar cuando un valor del dropdown es seleccionado",
"dropdown_result_id"=> "atributo del objeto que se tomara como identificador en el listado de resultados del dropdown",
"dropdown_result_value"=> "atributo del objeto que se presentara en el dropdown",
--}}


<div class="form-group mb-2 col-md-{{$col_with??12}} col-sm-{{$col_with??12}}">
    <div class="input-group">
        <div  class="input-group-prepend">
                                    <span class="input-group-text" >
                                     <i class="{{$icon_class??"fas fa-user"}}"></i>
                                    </span>
        </div>

        <input wire:model="{{$dropdown_model}}"
               wire:keydown.enter="{{$dropdown_enter_function}}" type="text" class="form-control" autocomplete="off" placeholder="{{$placeholder??""}}" required="{{$required??false}}" >
        <div class="input-group-append">
                                        <span class="input-group-text" >
                                            @if($picked_variable)
                                                <span class="badge badge-success">
                                                    <i class="fa-solid fa-check"></i>
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                        <i class="fa-solid fa-xmark"></i>
                                                </span>
                                            @endif
                                        </span>
        </div>
    </div>



    @if(count($dropdown_results)>0)
        @if(!$picked_variable)
            <ul class="dropdown-menu list-search">
                <h6 class="dropdown-header"><b>Seleccione opción</b></h6>
                @foreach($dropdown_results as $dropdown_result)
                    <li  class="dropdown-item">
                        <a wire:click="{{$selected_value_function}}('{{ $dropdown_result }}')" type="button" >
                            {{ $dropdown_result->{$dropdown_result_id} }} - {{ $dropdown_result->{$dropdown_result_value} }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif

</div>
