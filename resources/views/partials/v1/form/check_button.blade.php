
<div wire:ignore  class="dropdown form-group mb-{{$mb??2}} mt-{{$mt??0}} col-md-{{$col_width??6}} col-sm-12">

    <select wire:model="{{$model_select}}" class="selectpicker" name="{{$name_select}}" multiple>
        @foreach($options_list as $index => $option)
            <option value="{{$option['id_button']}}">{{ $option['label_name'] }}</option>
        @endforeach
    </select>

</div>

<script>
    $(function() {


        var a = $('input[name="select_report"]').selectpicker();

    });
</script>
