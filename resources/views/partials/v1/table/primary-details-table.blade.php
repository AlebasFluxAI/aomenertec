<div class="mb-3">
    <div class="detail-table">

        <table class="table table-bordered">
            <thead style="position: sticky;top: 0;z-index: 2">
            @foreach($table_info  as $info)
                <tr>
                    <th>{{$info["key"]}}</th>

                    <td>{{$info["value"]}}</td>
                </tr>
            @endforeach
        </table>

        @if(isset($edit_function))
            <div class="content-block edit-table ">
                <button wire:click="{{$edit_function}}">Editar</button>
            </div>
        @endif
    </div>
</div>
