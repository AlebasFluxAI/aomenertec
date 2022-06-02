<div class="contenedor-grande">
    <div  class="d-flex  flex-column pt-3">
        <div class="d-flex justify-content-center">
            <div class="justify-content-end form-group mb-0 mt-0 col-5">
                <input wire:ignore id="test" wire:model="test" type="checkbox" checked data-toggle="toggle" data-width="90" data-on="<i class='fas fa-lightbulb'></i>  ON" data-off="<i class='far fa-lightbulb'></i>  OFF" data-onstyle="success" data-offstyle="danger" >
            </div>
            <div class="justify-content-start form-group mb-0 mt-0 col-7">
                <label>Salida test</label>
            </div>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class = "modal-title" id = "deleteModalLabel"> Cuadro de confirmación </h4>
                        <a onclick="$('#deleteModal').modal('hiden');" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></a>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label class = "control-label"> ¿Desear continuar con la acción? </label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <a onclick="$('#deleteModal').modal('hiden');" type = "button" class = "btn btn-default" data-despeds = "modal"> Cancelar </a>
                        <a onclick="$('#deleteModal').modal('hiden');" type = "button" class = "btn btn-primary"> Confirmar </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

            $('#test').change(function() {
                $('#deleteModal').modal('show'); // abrir
            })

    </script>
</div>

