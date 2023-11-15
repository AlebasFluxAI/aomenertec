<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Modulo",
            "second_title"=>"precios operador de red"
        ])

    {{--optiones de cabecera de formulario--}}

    {{----------------------------------Formulario--------------------------}}
    @include("partials.v1.tab.v1.tab",[
                           "wire_ignore"=>true,
                           "tab_titles"=> $model->getClientTypeForPrice(),
                           "tab_contents"=> $model->getTabContentForPrice()
        ])

</div>

