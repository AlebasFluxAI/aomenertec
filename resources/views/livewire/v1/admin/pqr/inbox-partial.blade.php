@foreach($model->messages as$message)
    <ul id="{{$message->sender_type==\App\Models\V1\PqrMessage::SENDER_TYPE_USER?"message-box-left":"message-box-right"}}">
        <p class="text-right">{{\Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i:s')}} </p>
        <p class="text-right">Enviado por {{$message->sender()?$message->sender()->name:''}} </p>
        <hr class="mx-5 my-2">
        <ul style="margin-left: 10px">
            {{$message->message}}
        </ul>
        <hr class="mx-5 my-2">
        @if($message->attach and $message->attach->name!="no_found.png")
            @include("partials.v1.image",[
                "image_url"=>$message->attach->url
              ])
        @endif
    </ul>
@endforeach
