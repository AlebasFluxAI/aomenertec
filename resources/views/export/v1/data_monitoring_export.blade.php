<table>
    @foreach($data as $index=>$item)
        @if($index == 0)
            <thead>
            <tr>
                @foreach($item as $option)
                    <td>{{ $option }}</td>
                @endforeach
            </tr>
            </thead>
            @break
        @endif
    @endforeach
    <tbody>

    @foreach($data as $index=>$item)
        <tr>
            @if($index != 0)
                @foreach($item as $option)
                    <td>{{ $option }}</td>
                @endforeach
            @endif
        </tr>
    @endforeach

    </tbody>
</table>
