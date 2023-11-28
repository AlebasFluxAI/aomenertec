<div class="btn-group">
    <a class="btn btn-redirect btn-sm"
       data-toggle="dropdown"
       aria-haspopup="true" aria-expanded="false">
        <span style="font-size: 10px" class="fas fa-search"></span>
    </a>
    <div class="dropdown-menu p-1 container">
        <div class="row">
            <div class="col-md-12 mb-2">
                @if(isset($col_type) and $col_type==\App\Http\Resources\V1\ColTypeEnum::COL_TYPE_BOOLEAN)
                    <div class="p-2">
                        <div class="form-check form-switch ">
                            <div
                                class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input wire:model.defer="filter" wire:change="setFilterCol('{{$col_name}}')"
                                       type="checkbox"
                                       name="toggle" id="toggle"
                                       class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
                                <label for="toggle"
                                       class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>

                            <label>Filtrar</label>

                        </div>
                    </div>

                @else
                    <input wire:model.defer="filter" class="form-control form-text" type="text"
                           placeholder="Buscar">
            </div>
            <div class="col-md-6 ">
                <button wire:click="cleanFilter" class="filter-button"> Limpiar</button>
            </div>
            <div class="col-md-6">
                <button wire:click="setFilterCol('{{$col_name}}')">Buscar</button>
            </div>
            @endif


        </div>
    </div>
</div>

