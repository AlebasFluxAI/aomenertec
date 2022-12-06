@section("header")
    {{--extended app.blade--}}
@endsection
<div class="login">
    @include("partials.v1.title",[
            "first_title"=>"On/off",
            "second_title"=>"Cliente"
        ])
    @include("partials.v1.table_nav",
         ["mt"=>2,"nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"v1.admin.client.list.client",
                    ],

                ]
        ])

    <div class="contenedor-grande">

    <div class="d-flex flex-column pt-3">

        @foreach($coils as $index=>$coil)
            <div wire:key="coil-{{ $index }}" class="d-flex justify-content-center">
                <div wire:loading wire:target="confirmAction('{{ $index }}')" class="justify-content-end  mx-2 form-group mb-0 mt-0 ">

                    <span class="">Conectando...</span>
                    <div class="spinner-grow" role="status">
                    </div>
                </div>
                <div wire:ignore class=" justify-content-end form-group  mx-2 mb-0 mt-0 ">
                    <label class="input_check" id="{{ $index }}">
                         <input  wire:model="coils.{{ $index }}.status" disabled id="coils_{{ $coil->id }}" type="checkbox"
                                checked data-toggle="toggle" data-width="90"
                                data-on="<i class='fas fa-lightbulb'></i>  ON"
                                data-off="<i class='far fa-lightbulb'></i>  OFF" data-onstyle="success"
                                data-offstyle="danger"/>
                    </label>
                </div>
                <div class=" form-group mx-2 mb-0 mt-0 ">
                    <input wire:model.lazy="coils.{{ $index }}.name" id="input_{{ $coil->id }}"
                           placeholder="Salida {{ $coil->number }}">
                </div>
                <div class="modal fade" id="confirmModal_{{ $index }}" tabindex="-1" role="dialog"
                     aria-labelledby="confirmModalLabel_{{ $index }}">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="confirmModalLabel_{{ $index }}"> Cuadro de
                                    confirmación </h4>
                                <a onclick="$('#confirmModal_'+{{ $index }}).modal('hide');" type="button"
                                   class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></a>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        @if($coil->status)
                                            <label class="control-label"> ¿Desear desactivar {{ $coil->name }}
                                                ? </label>
                                        @else
                                            <label class="control-label"> ¿Desear activar {{ $coil->name }}
                                                ? </label>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <a onclick="$('#confirmModal_'+{{ $index }}).modal('hide');" type="button"
                                   class="btn btn-default" data-despeds="modal"> Cancelar </a>
                                <a onclick="$('#confirmModal_'+{{ $index }}).modal('hide');confirmCheck({{ $coil->id }});"
                                   wire:click="confirmAction('{{ $index }}')" type="button" class="btn btn-primary">
                                    Confirmar </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    </div>
    </div>
    <script>

        var flag = true;
        var checks = document.querySelectorAll(".input_check");
        for (let check of checks) {
            $('#' + check.id).click(function (e) {
                console.log(flag)
                e.stopPropagation();
                if (flag) {
                    $('#confirmModal_' + check.id).modal('show');
                }
            });
        }

        function confirmCheck(id) {
            flag = false
            console.log(flag)
        }
        document.addEventListener('livewire:load', function () {

            @this.on('changeCheck',(e) =>{
                if (e.flag == true) {
                    console.log(e)
                    $('#coils_${e.index}').bootstrapToggle('enable')
                    $('#coils_${e.index}').bootstrapToggle('toggle')
                    $('#coils_${e.index}').bootstrapToggle('disable')

                }else {
                    console.log(false)
                }
                flag = true
                console.log(flag)

            })
        })

    </script>
</div>

