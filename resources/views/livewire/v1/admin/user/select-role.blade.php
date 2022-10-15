<div class="container p-5">

    <h1 class="mb-6">Seleccione el tipo de rol con el que quiere navegar</h1>
    <div class="row">
        @foreach($roles as $role)
            <div wire:click="selectRole('{{$role["rol"]}}')" class="col-md-5 text-center select-rol-container">
                <div class="col-md-12 text-center">
                    <div class="col-md-6 offset-3" style="border-color: white;border-width: 3px;padding: 5px">
                        <p><b>{{$role["name"]}}</b></p>
                        <br>
                        <i style="font-size: 120px" class="{{$role["icon"]}}"></i>
                    </div>
                </div>
            </div>

        @endforeach
    </div>

</div>
