<div class="login" style=" border-color: #f2f2f2;border-width: 2px;padding: 15px;border-radius: 15px">

    @section("header")
        {{--extended app.blade--}}
        @auth
        @else
            @include("layouts.menu.v1.header_menu_password")
        @endauth
    @endsection



    @include("partials.v1.title",[
          "second_title"=>"",
          "first_title"=>"Historico PQR ".$model->code,
      ])

    @auth
        @include("partials.v1.table_nav",
                [
                 "mt"=>2,
                 "nav_options"=>[
                        [
                        "permission"=>[\App\Http\Resources\V1\Permissions::PQR_SHOW],
                        "button_align"=>"right",
                        "click_action"=>"",
                        "button_content"=>"Ver listado",
                        "button_icon"=>"fa-solid fa-list",
                        "target_route"=>"administrar.v1.peticiones.listado",
                        ],

                    ]
                ])
    @endauth
    @include("partials.v1.primary-card",[
               'card_title'=>"Peticion de cliente: ",
               'card_subtitle'=>$model->code,
               'card_body'=>[
                               [
                                      "name"=>"Asunto",
                                      "value"=>$model->subject
                               ] ,

                            ]
           ])


    <div
        style="overflow-y: scroll;height:500px;border-color: #f2f2f2;border-width: 3px;padding: 5px;border-radius: 15px">
        @if(!count($model->messages))
            <div class="text-center mt-5">
                <i style="font-size: 5rem" class="fa-solid fa-inbox empty-table"></i>
                <p class="empty-table-text">{{$table_empty_text??"No existen Mensajes para esta petición"}}</p>
            </div>
        @else
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
        @endif
    </div>
    <div wire:loading.class="loader">
    </div>
    <div wire:loading.remove class="text-right">
        @if($model->status==\App\Models\V1\Pqr::STATUS_RESOLVED)
            <form wire:submit.prevent="closePqrForm" id="formulario" class="needs-validation" role="form">
                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_content"=>" Cerrar petición"
                          ])

            </form>
        @endif
    </div>
    <div class="m-5">

        <a href="{{ url()->previous() }}" class="btn btn-default">Volver</a>
    </div>


</div>

