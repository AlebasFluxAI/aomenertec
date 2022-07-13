<div>
    <div style="background-color: #f2f2f2;padding: 15px;border-radius: 15px">
        @include("layouts.menu.v1.header_menu_password")
        <div class="col-md-12 text-center">
            <div class="col-md-6 offset-3 text-center mt-2"
                 style="background-color: #c3c3c3;padding: 15px;border-radius: 15px">
                <h1><span class="fas fa-check" style="color:green;color:#38ab57;font-size: 200px"></span></h1>
                <h2>Se ha creado su solicitud exitosamente con el código:
                </h2>
                <h2 class="my-2"><span
                        style="color: teal;font-weight: bold;font-size: 30px">{{$model->code}}</span></h2>
                <h3><b>Debe guardar este código para dara seguimiento a la petición</b></h3>
            </div>
        </div>
    </div>
</div>
