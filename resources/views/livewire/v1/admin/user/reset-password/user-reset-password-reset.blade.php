<div>

    @include("layouts.menu.v1.header_menu_password")
    @if($has_error)
        <div class="col-md-12 bg-secondary p-5">
            <div class="col-md-6 offset-3">
                <div class="text-center ">
                    <i style="color:darkred;font-size: 120px" class="fa-solid fa-xmark"></i>
                    <p> Tu codigo para reestablecer contraseña no es valido</p>

                </div>
            </div>
        </div>
    @else

        <div class="contenedor-grande col-md-12 p-5" style="background-color: #f2f2f2">
            <div class="col-md-6 offset-3 bg-secondary p-5" style="border-radius: 10px">
                <form wire:submit.prevent="submitForm">
                    <p for="email">Reestablecer contraseña</p>
                    <br>
                    <div class="text-left">
                        <label>
                            Ingrese su nueva contraseña
                        </label>

                        <input id="email" class="form-control" type="password" wire:model="password"
                               required
                               autofocus/>
                    </div>
                    <br>
                    <div class="text-left">
                        <label>
                            Confirma tu contraseña
                        </label>

                        <input id="email" class="form-control" type="password" wire:model="password_reply"
                               required
                               autofocus/>
                    </div>
                    @error('password_error') <span class="error">{{ $message }}</span> @enderror

                    <div class="font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="btn btn-info">
                            Reestablecer contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
