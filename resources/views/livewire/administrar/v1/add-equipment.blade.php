<div>
    <section class="top-info bg-light">
        <div class="container">
            <div class="row">
            <div class="col-md-6 p-3 offset-3 bg-warning">
                <form wire:submit.prevent="submitForm">
                    <div class="m-4">
                        <i class="fa-solid fa-computer-speaker"></i><label><i class="fa-solid fa-id-card text-secondary"></i> Nombre del equipo</label>
                        <input wire:model="name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="textHelp" placeholder="Nombre del equipo">
                    </div>

                    <div class="m-4">
                        <i class="fa-solid fa-computer-speaker"></i><label for="serial"><i class="fa-solid fa-barcode text-secondary"></i> Serial del equipo</label>
                        <input wire:model="serial" type="text" class="form-control" id="serial" aria-describedby="textHelp" placeholder="Serial del equipo">
                    </div>
                    <div class="m-4">
                        <i class="fa-solid fa-computer-speaker"></i><label><i class="fa-solid fa-comment text-secondary"></i> Descripcion del equipo</label>
                        <input wire:model="description" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="textHelp" placeholder="Descripcion del equipo">
                    </div>
                    <div class="m-4">
                        <i class="fa-solid fa-computer-speaker"></i><label><i class="fa-solid fa-comment text-secondary"></i> Selecciona el tipo del equipo</label>
                        <select wire:model="equipment_type_id" wire:click="loadEquipmentType" class="form-control">
                            <option value="">Seleccione la opción</option>
                            @foreach($equipment_types as $equipmentType)
                                <option value="{{ $equipmentType->id }}">{{ $equipmentType->type }}</option>
                            @endforeach
                        </select>
                        <button class="btn-lg btn-secondary">Guardar</button>
                    </div>
                    @csrf
                </form>

            </div>
        </div>
        </div>
    </section>

</div>
