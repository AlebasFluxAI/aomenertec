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
                        [
                        "button_align"=>"right",
                        "button_type"=>"dropdown",
                        "button_icon"=>"fas fa-gear",
                        "button_content"=>"Acciones",
                        "button_options"=>$model->navigatorDropdownOptions()
                        ]

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

    @include("partials.v1.divider_title",["title"=>"Mensajes intercambiados"])

    <div
        style="overflow-y: scroll;height:500px;border-color: #f2f2f2;border-width: 3px;padding: 5px;border-radius: 15px">
        @if(!count($model->messages))
            <div class="text-center mt-5">
                <i style="font-size: 5rem" class="fa-solid fa-inbox empty-table"></i>
                <p class="empty-table-text">{{$table_empty_text??"No existen Mensajes para esta petición"}}</p>
            </div>
        @else
            @include("livewire.v1.admin.pqr.inbox-partial",[
                    "model"=>$model
             ])
        @endif
    </div>
    @if($model->closeMessage)
        @include("partials.v1.divider_title",["title"=>"Solución de la petición"])
        <div
        >
            <ul id="message-closer-box">
                <p style="color: teal"><b>Evidencias de cierre de la peticion:</b></p>
                <br>
                <p class="text-right">{{\Carbon\Carbon::parse($model->closeMessage->created_at)->format('d/m/Y H:i:s')}} </p>
                <p class="text-right">Cerrado
                    por {{$model->closeMessage->sender()?$model->closeMessage->sender()->name:''}} </p>
                <hr class="mx-5 my-2">
                <ul style="margin-left: 10px">
                    {{$model->closeMessage->message}}
                </ul>
                <hr class="mx-5 my-2">
                @if($model->closeMessage->attach and $model->closeMessage->attach->name!="no_found.png")
                    @if(\App\Models\V1\Image::validateImageFile($model->closeMessage->attach))
                        @include("partials.v1.image",[
                                    "image_url"=>$model->closeMessage->attach->url,
                               ])
                    @else
                        @include("partials.v1.document",[
                                   "document_url"=>$model->closeMessage->attach->url,
                              ])
                    @endif
                @endif
            </ul>

        </div>
    @endif
</div>

