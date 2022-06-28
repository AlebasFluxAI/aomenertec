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
@foreach($client_config_alert as $index => $item)
    <div wire:ignore class="modal fade" id="modal_{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel_{{ $item->id }}" aria-hidden="true">
        <div  class="modal-dialog modal-xl" role="document">
            <div  class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel_{{ $item->id }}">Seleccione las salidas relacionadas para control automatico</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div  class="modal-body">
                    @if($item->flag_id < 47)
                        @include('partials.v2.form.form_input_max_min',[
                                        "input_type"=>"input_min_max",
                                        "input_min_model"=> "client_config_alert.".$index.".min_control",
                                        "input_max_model"=>"client_config_alert.".$index.".max_control",
                                        "placeholder"=>$placeholders[$index],
                                        "col_with"=>12,
                                        "required"=>false,
                                        "updated_input" => "defer",
                                        "placeholder_clickable"=>false,
                                        "data_target"=>"",
                                        "click_action" => "",
                                ])
                    @else
                        @include('partials.v2.form.form_input_icon',[
                                        "input_type"=>"number",
                                        "offset"=>2,
                                        "input_model"=>"client_config_alert.".$index.".max_alert",
                                        "placeholder"=>$placeholders[$index],
                                        "col_with"=>8,
                                        "updated_input" => "defer",
                                        "required"=>false,
                                        "placeholder_clickable"=>true,
                                        "data_target"=>"modal_".$item['id'],
                                        "click_action" => "",
                                ])
                    @endif
                    @foreach($digital_outputs as $index => $output)
                        @include("partials.v1.form.check_button",[
                            "mt"=>0,
                            "mb"=>0,
                            "col_width"=>3,
                            "check_model"=>"checks.". $index .".output",
                            "check_label"=>$output->name,
                            "check_id"=>$index,

                            ])
                    @endforeach

                </div>
                <div class="modal-footer">
                    <button><a type="button" class="btn btn-secondary" data-dismiss="modal">Close</a></button>
                    <button><a wire:click="assignmentOutput('{{ $item->id }}')" type="button" class="btn btn-primary" data-dismiss="modal">Save changes</a></button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
