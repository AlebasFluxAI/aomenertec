<div class="contenedor-grande">
    <div  class="d-flex  flex-column pt-3">

        @foreach($coils as $index=>$coil)
            <div  wire:key="coil-{{ $coil->id }}" class="d-flex justify-content-center">
                <div class="justify-content-end form-group mb-0 mt-0 col-5">
                    <span wire:ignore class="input_check" id="{{ $coil->id }}">
                         <input  wire:model="coils.{{ $index }}.status" id="coils_{{ $coil->id }}"  type="checkbox" checked data-toggle="toggle" data-width="90" data-on="<i class='fas fa-lightbulb'></i>  ON" data-off="<i class='far fa-lightbulb'></i>  OFF" data-onstyle="success" data-offstyle="danger" />
                    </span>
                </div>
                <div class="justify-content-start form-group mb-0 mt-0 col-7">
                    <input wire:model.lazy="coils.{{ $index }}.name" id="input_{{ $coil->id }}" placeholder="Salida {{ $coil->number }}">
                </div>
                <div class="modal fade" id="confirmModal_{{ $coil->number }}" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel_{{ $coil->number }}">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class = "modal-title" id = "confirmModalLabel_{{ $coil->number }}"> Cuadro de confirmación </h4>
                                <a onclick="$('#confirmModal_'+{{ $coil->number }}).modal('hide');" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></a>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        @if($coil->status)
                                            <label class = "control-label"> ¿Desear desactivar {{ $coil->name }}? </label>
                                        @else
                                            <label class = "control-label"> ¿Desear activar {{ $coil->name }}? </label>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <a onclick="$('#confirmModal_'+{{ $coil->number }}).modal('hide');" type = "button" class = "btn btn-default" data-despeds = "modal"> Cancelar </a>
                                <a onclick="confirm({{ $coil->id }})"wire:click="confirmAction('{{ $index }}')" type = "button" class = "btn btn-primary"> Confirmar </a>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        @endforeach
    </div>
   <script>

           var checks = document.querySelectorAll(".input_check");
           for (let check of checks){
               $('#'+check.id).click(function(e){
                   e.stopPropagation();
                   $('#confirmModal_'+check.id).modal('show');
               });
           }
           function confirm(id){
               $('#confirmModal_'+id).modal('hide');
               $('#coils_'+id).bootstrapToggle('toggle')
           }

    </script>
</div>

