{{--
"icon_class"=>Clase de font awesome para icono del input "fas fa-user"
"col_with"=> "Tamaño de la columna bootstrap",
"list_model"=>"variable que tendra el valor del select wire:model del componente",
"list_options"=> "variable que contiene el array de resultados para la consulta del select (array)",
"list_default"=> "variable que contiene primera opcion de list"
"list_option_value"=> "atributo del objeto que se tomara como identificador en el listado de resultados del select",
"list_option_view"=> "atributo del objeto que se presentara en el list"
"list_option_title"=> "atributo title del objeto que se presentara en el list"

--}}

<div
    class="form-group mb-{{$mb??2}} mt-{{$mt??0}}  @if($aux_class??"" != 'no-border-card') col-sm-8 col-md-{{$col_with??12}}@endif ">
    <label>{{$input_label??""}}</label>
    <select wire:model.lazy="{{$list_model}}" class="{{$aux_class??"custom-select"}} {{$background??""}} "
            {{($required??false)?"required":""}} @if($disabled??false)disabled @endif>
        <option disabled value="0"> {{$list_default??""}} </option>
        @if($not_selection??false)
            <option value=""> {{$not_selection??"Sin seleccion"}} </option>
        @endif
        @foreach($list_options as $option)
            <option @if($list_option_title != "")title="{{ $option[$list_option_title] }}"
                    @endif value="{{ $option[$list_option_value] }}">{{ $option[$list_option_view] }}</option>

        @endforeach
    </select>
</div>
