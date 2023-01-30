<div>
    @include("partials.v1.title",[
            "first_title"=>"Agregar equipos a",
            "second_title"=>"Clientes"
        ])

    @include("partials.v1.table_nav",
    ["mt"=>2, "nav_options"=>[
            ["button_align"=>"right",
            "click_action"=>"",
            "button_icon"=>"fas fa-list",
            "button_content"=>"Ver listado",
            "target_route"=>"v1.admin.client.list.client",
            ],
            [
                    "button_align"=>"right",
                    "button_type"=>"dropdown",
                    "button_icon"=>"fas fa-gear",
                    "button_content"=>"Acciones",
                    "button_options"=>$client->navigatorDropdownOptions()
                    ]

        ]
    ])
    <div class="contenedor-grande ">
        <form wire:submit.prevent="save" id="formulario" class="needs-validation" role="form">
            @include("partials.v1.equipment_to_client_association")

            <div class="text-right">
                <button id="add" type="submit" class="mb-2 py-2 px-4">
                    <b>
                        Guardar
                    </b>
                </button>
            </div>
        </form>
    </div>

</div>
