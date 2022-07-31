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
    <label>{{$input_label??""}}</label>
    <div class="input-group">
        <div class="input-group-prepend">
                                    <span class="input-group-text">
                                     <i class="{{$icon_class??"fas fa-user"}}"></i>
                                    </span>
        </div>

        <select wire:model="{{$dropdown_model}}"
                @if(isset($dropdown_refresh))
                    wire:click="{{$dropdown_refresh}}()"
                @endif
                class="form-select"
                aria-label="Default select example"
                placeholder="{{$placeholder??""}}" @if($disabled??false)
                    disabled
            @endif>
            @if($dropdown_editing??true)
                <option value=""></option>
            @endif

            @foreach($dropdown_values as $dropdown_value)
                <option value="{{$dropdown_value["value"]}}">{{$dropdown_value["key"]}}</option>
            @endforeach
        </select>
    </div>
    @error($dropdown_model)
    <div class="error-container">
        <small class="form-text text-danger">{{$message}}</small>
    </div>
    @enderror
</div>
