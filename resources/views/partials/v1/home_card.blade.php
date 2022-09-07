<div class="col-lg-3 col-sm-12 d-flex serviciosHomeCaja mb-md-4 p-md-2">

    <div class="col-6">
        <a href="{{($redirect??null)?route($redirect,["subdomain"=>\Illuminate\Support\Facades\Route::input("subdomain")??\App\Http\Resources\V1\Subdomain::SUBDOMAIN_DEFAULT]):"#"}}"
           class="linkServiciosHome">
            <img alt="{{$image_alt??""}}"
                 src="{{$image_url??null}}">
        </a>
    </div>
    <div class="col-sm-12 col-md-10">
        <h3 class="header tituloServiciosHome">
            <a href="{{($redirect??null)?route($redirect,["subdomain"=>\Illuminate\Support\Facades\Route::input("subdomain")??\App\Http\Resources\V1\Subdomain::SUBDOMAIN_DEFAULT]):"#"}}"
               class="linkServiciosHome">
                {{$tittle}}
            </a>
        </h3>
    </div>

</div>
