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
          "first_title"=>"CERRAR PQR ".$model->code,
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


    <div
        style="overflow-y: scroll;height:500px;border-color: #f2f2f2;border-width: 3px;padding: 5px;border-radius: 15px">
        @if(!count($model->messages))
            <div class="text-center mt-5">
                <i style="font-size: 5rem" class="fa-solid fa-inbox empty-table"></i>
                <p class="empty-table-text">{{$table_empty_text??"No existen Mensajes para esta petición"}}</p>
            </div>
        @else
            @foreach($model->messages as $message)
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

    @include("partials.v1.divider_title",[
                                           "title"=>"Agregar respuesta de cierre a la petición"
                                   ]
                                  )
    <form wire:submit.prevent="submitCloserMessage" id="formulario" class="needs-validation" role="form">
        @include("partials.v1.form.form_input_icon",[
                          "input_label"=>"Respuesta de cierre",
                          "input_model"=>"description",
                          "input_rows"=>8,
                          "tooltip_title"=>"Agrege una respuesta final para darle cierre a la peticion, puedes agregar una imagen o texto",
                          "placeholder"=>"Agregar mensaje",
                          "col_with"=>12,
                          "input_type"=>"text",
                          "updated_input"=>"defer",
                          "required"=>true
                                       ])


        @include("partials.v1.form.form_input_file",[
                            "input_type"=>"file",
                                                "input_model"=>"attach",
                                                "icon_class"=>"fas fa-file",
                                                "placeholder"=>"Puedes cargar una imagen",
                                                "file_accepted"=>".png,.jpg,.gif,.webp,.bmp,.pdf,.docx",
                                                "col_with"=>12,
                                                "required"=>false,
                                           ])

        @include("partials.v1.form.form_submit_button",[
                                        "button_align"=>"right" ,
                                        "button_content"=>"Solucionar petición"
                            ])

    </form>

</div>

