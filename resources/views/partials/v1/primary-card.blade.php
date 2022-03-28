<div class="row">
    <div class="col-12">
        <div class="card" style="width: 30rem;">
            <div class="card-header">
                {{$card_title}} <b>{{$card_subtitle}}</b>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($card_body as $field)
                    <li class="list-group-item"><b>{{$field['name']}}</b>: {{$field['value']}}</li>
                @endforeach

            </ul>
        </div>
    </div>
</div>
