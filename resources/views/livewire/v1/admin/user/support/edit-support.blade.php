<div class="login">
    @section("header")
        {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Editar",
            "second_title"=>"usuario soporte"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.soporte.listado",
                    ],

                ]
        ])
    {{----------------------------------Formulario--------------------------}}

    <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
        @include("partials.v1.addUserTemplate.user-add-form")
    </form>


</div>
