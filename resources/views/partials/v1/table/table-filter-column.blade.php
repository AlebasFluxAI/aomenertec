<div class="btn-group">
    <a class="btn btn-redirect btn-sm"
            data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
        <span class="fas fa-search"></span>
    </a>
    <div class="dropdown-menu p-1 container">
        <div class="row">

            <div class="col-md-12 mb-2">
                <input wire:model.defer="filter" class="form-control form-text" type="text" placeholder="Buscar">

            </div>
            <div class="col-md-6 ">
                <button wire:click="cleanFilter" class="filter-button"> Limpiar</button>
            </div>
            <div class="col-md-6">
                <button wire:click="setFilterCol('{{$col_name}}')">Buscar</button>
            </div>

        </div>
    </div>
</div>
