<div class="card card-services">
    <div class="g-0 d-flex align-items-center">
        <div style="width: 40%;margin: 5px">
            <img src="{{$image_url}}" class="img-fluid rounded-start"
                 alt="Gestión de proyectos energéticos y de telecomunicaciones">
        </div>
        <div style="width: 60%;text-justify: auto">
            <div class="card-body p-0">
                <h4 class="card-title" style="color:#1c9599; font-size: 15px;">
                    @if(isset($card_route))
                        <a href="{{route($card_route,["subdomain"=>\Illuminate\Support\Facades\Route::input("subdomain")?:"aom"])}}">{{$text}}</a>
                    @else
                        {{$text}}
                    @endif
                </h4>
            </div>
        </div>
    </div>
</div>
