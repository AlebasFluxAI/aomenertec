@section("header") {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"Configurar",
            "second_title"=>"equipo de cliente"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Listado de clientes",
                    "target_route"=>"v1.admin.client.list.client",
                    ],

                ]
        ])

    @include("partials.v1.tab.v1.tab",[

                            "tab_titles"=>[
                                                [
                                                    "title"=>"Configuraciones",

                                                ],

                                           ],

                            "tab_contents"=>[
                                                [
                                                    "view_name"=>"partials.v2.form.primary_form",
                                                    "view_values"=>  [
                                                                        "form_toast"=>true,
                                                                        "session_message"=>"message",
                                                                        "form_submit_action"=>"submitForm",
                                                                        "form_title"=>"Ajuste las configuraciones para el equipo del cliente",
                                                                        "form_inputs"=> $inputs

                                                            ]
                                                ],

                                          ]
         ])
@foreach($alerts as $item)
    <div class="modal fade" id="modal_{{ $item['id'] }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel_{{ $item['id'] }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel_{{ $item['id'] }}">Seleccione las salidas relacionadas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('partials.v1.form.check_button', [
                                "col_width"=>6,
                                "check_model"=>"",
                                "check_id"=>"",
                                "check_label"=>"salida 1",

                            ])
                    @include('partials.v1.form.check_button', [
                                "col_width"=>6,
                                "check_model"=>"",
                                "check_id"=>"",
                                "check_label"=>"salida 2",

                            ])
                </div>
                <div class="modal-footer">
                    <button><a type="button" class="btn btn-secondary" data-dismiss="modal">Close</a></button>
                    <button><a type="button" class="btn btn-primary" data-dismiss="modal">Save changes</a></button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
