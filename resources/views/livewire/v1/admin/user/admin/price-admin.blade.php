<div class="login">
    @section("header") {{--extended app.blade--}}
    @endsection

    @include("partials.v1.title",[
            "first_title"=>"Precios",
            "second_title"=>"administrador"
        ])

    {{--optiones de cabecera de formulario--}}

    @include("partials.v1.table_nav",
         ["nav_options"=>[
                    ["button_align"=>"right",
                    "click_action"=>"",
                    "button_icon"=>"fas fa-list",
                    "button_content"=>"Ver listado",
                    "target_route"=>"administrar.v1.usuarios.admin.listado",
                    ],

                ]
        ])
    {{----------------------------------Formulario--------------------------}}
    <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
        @include('partials.v1.form.form_list',[
                    "col_with"=>4,
                    "list_model"=>"",
                    "disabled"=>true,
                    "list_options"=> $client_types,
                ])
        @include('partials.v1.form.form_input_icon',[
                    "col_with"=>4,
                    "input_model"=>"",
                    "icon_class"=>"",
                    "disabled"=>false,
                    "required"=>true,
                    "type"=> $text,
                    "placeholder"=> "Valor $",
                ])
    </form>

</div>
