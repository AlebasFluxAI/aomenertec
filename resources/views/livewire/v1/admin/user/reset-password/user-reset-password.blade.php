<div>
    @include("layouts.menu.v1.header_menu_password")
    <div class="col-md-12 bg-secondary p-5">
        <div class="col-md-6 offset-3">
            <form wire:submit.prevent="resetPassword">
                <div class="text-left">
                    <label for="email">{{ __('login.email') }}</label>
                    <li>
                        Ingrese el correo de registro para reestablecer la contraseña.
                    </li>
                    <br>
                    <input id="email" class="form-control" type="email" wire:model="email" :value="old('email')"
                           required
                           autofocus/>
                </div>
                @error('reset_error') <span class="error">{{ $message }}</span> @enderror

                <div class="font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="btn btn-info">
                        Enviar link para restaurar contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

