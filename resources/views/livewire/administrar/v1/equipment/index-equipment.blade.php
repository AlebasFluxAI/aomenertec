<div>
@section("header") {{--extended app.blade--}}
    @include("layouts.v1.app_admin_header")
    @include("partials.v1.title",[
            "first_title"=>"Listado",
            "second_title"=>"de equipos"
        ])

@endsection


    @include("partials.v1.table_nav",
           ["nav_options"=>[
                      ["button_align"=>"right",
                      "click_action"=>"",
                      "button_content"=>"Crear nuevo",
                      "icon"=>"fa-solid fa-plus",
                      "target_route"=>"administrar.equipos.agregar",
                      ],

                  ]
          ])


</div>
