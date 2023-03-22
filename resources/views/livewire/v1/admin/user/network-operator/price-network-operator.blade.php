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
                           "tab_contents"=>[
                                               [
                                                   "view_name"=>"livewire.v1.admin.user.network-operator.price-calculator.calculator",
                                                   "view_values"=>  [
                                                                "client_type"=>\App\Models\V1\ClientType::ZIN_CONVENTIONAL
                                                           ],


                                               ],




                                ],
        ])


</div>
