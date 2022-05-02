<div>
    <section class="login">
        @section("header") {{--extended app.blade--}}
        @endsection
        @if (session()->has('success'))
            <div class="alert alert-succes">
                {{ session('success') }}
            </div>
        @endif
        @include("partials.v1.title",[
                "first_title"=>"Añadir",
                "second_title"=>"Clientes"
            ])


        <div class="contenedor-grande">
            <div class="row content pt-3">
                @include("partials.v1.table_nav",
             ["nav_options"=>[
                ["button_align"=>"right",
                "click_action"=>"",
                "button_icon"=>"fas fa-list",
                "button_content"=>"Ver listado",
                "target_route"=>"v1.admin.client.list.client",
                ],

            ]
    ])
                {{-- <form  action="#" method="post"  enctype="multipart/form-data" >
                     @csrf
                     <div>&nbsp;&nbsp;<strong>Importar desde archivo excel</strong> </div>
                     <div class="row mb-4">
                         @include("partials.v1.form.form_input_icon",[
                                  "input_type"=>"file",
                                  "input_class"=>"custom-file-input",
                                  "icon_class"=>"fas fa-file-excel",
                                  "input_model" => "",
                                  "col_with"=>6,
                                  "required"=>true
                         ])

                         <div class="form-group mb-2 col-md-4 col-sm-12">
                             <div class="input-group">
                                 <div  class="input-group-prepend">
                                     <span for="buscar" class="input-group-text" >
                                         <i>
                                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-video2" viewBox="0 0 16 16">
                                                 <path d="M10 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                                                 <path d="M2 1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2ZM1 3a1 1 0 0 1 1-1h2v2H1V3Zm4 10V2h9a1 1 0 0 1 1 1v9c0 .285-.12.543-.31.725C14.15 11.494 12.822 10 10 10c-3.037 0-4.345 1.73-4.798 3H5Zm-4-2h3v2H2a1 1 0 0 1-1-1v-1Zm3-1H1V8h3v2Zm0-3H1V5h3v2Z"/>
                                             </svg>
                                         </i>
                                     </span>
                                 </div>
                                 <input wire:model="aux_network_operator"
                                        wire:keydown.enter="assignNetworkOperatorAuxFirst()" type="text" class="form-control" id="buscar" autocomplete="off" placeholder="Identificacion" required >
                                 <div class="input-group-append">
                                     <span class="input-group-text" >
                                         @if($picked_aux_network_operator)
                                             <span class="badge badge-success">
                                                 <i>
                                                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                                         <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                                     </svg>
                                                 </i>
                                             </span>
                                         @else
                                             <span class="badge badge-danger">
                                                 <i>
                                                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                         <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                                                         <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                                                     </svg>
                                                 </i>
                                             </span>
                                         @endif
                                     </span>
                                 </div>
                             </div>
                             @error("aux_network_operator")
                             <div  class="error-container">
                                 <small class="form-text text-danger">{{$message}}</small>
                             </div>
                             @else
                                 @if(count($aux_network_operators)>0)
                                     @if(!$picked_aux_network_operator)
                                         <div class="px-4 pt-2 pb-0  dropdown rounded shadow" >
                                             @foreach($aux_network_operators as $user)

                                                 <a  wire:click="assignNetworkOperatorAux('{{ $user }}')" class="nav-item">
                                                     {{ $user->name }}
                                                 </a>
                                                 <hr>
                                             @endforeach
                                         </div>
                                     @endif
                                 @else
                                     <div class="">
                                         <small class="form-text text-muted">{{$message_aux_network_operator}}</small>
                                     </div>
                                 @endif
                                 @enderror
                         </div>
                         <div class="text-center col-md-2 col-sm-12">
                             <button type="submit" id="importar" class="px-5 py-2" >Importar </button>
                         </div>
                     </div>
                 </form>--}}

                <form wire:submit.prevent="save" id="formulario" class="needs-validation" role="form">
                    {{--<div> &nbsp;&nbsp; <strong> Agregar manualmente</strong></div>--}}
                    <div class="row ">
                        @include("partials.v1.form.form_list",[
                              "col_with"=>3,
                              "input_type"=>"text",
                              "list_model" => "person_type",
                              "list_default" => "Tipo de persona ...",
                              "list_options" => $person_types,
                              "list_option_value"=>"value",
                              "list_option_view"=>"key",
                              "list_option_title"=>"",
                     ])


                        @include("partials.v1.form.form_list",[
                                "col_with"=>3,
                                "input_type"=>"text",
                                "list_model" => "identification_type",
                                "list_default" => "Tipo de identificacion",
                                "list_options" => $identification_types,
                                "list_option_value"=>"value",
                                "list_option_view"=>"key",
                                "list_option_title"=>"",
                       ])
                        @include("partials.v1.form.form_input_icon",[
                                "input_model"=>"identification",
                                "icon_class"=>"fas fa-barcode",
                                "placeholder"=>"identificación",
                                "col_with"=>4,
                                "input_type"=>"text",
                                "required"=>true
                       ])

                        @include("partials.v1.form.form_input_icon",[
                                "input_model"=>"name",
                                "icon_class"=>"fas fa-user",
                                "placeholder"=>"Nombre",
                                "col_with"=>5,
                                "input_type"=>"text",
                                "required"=>true
                       ])

                        @include("partials.v1.form.form_input_icon",[
                                "input_model"=>"phone",
                                "icon_class"=>"fas fa-barcode",
                                "placeholder"=>"Telefono",
                                "col_with"=>3,
                                "input_type"=>"text",
                       ])
                        @include("partials.v1.form.form_list",[
                                 "col_with"=>4,
                                 "input_type"=>"text",
                                 "list_model" => "location_type_id",
                                 "list_default" => "Tipo ubicación...",
                                 "list_options" => $location_types,
                                 "list_option_value"=>"id",
                                 "list_option_view"=>"type",
                                 "list_option_title"=>"",
                        ])
                        @include("partials.v1.form.form_list",[
                                 "col_with"=>4,
                                 "input_type"=>"text",
                                 "list_model" => "department_id",
                                 "list_default" => "Departamento...",
                                 "list_options" => $departments,
                                 "list_option_value"=>"id",
                                 "list_option_view"=>"name",
                                 "list_option_title"=>"",
                        ])
                        @include("partials.v1.form.form_list",[
                                 "col_with"=>4,
                                 "input_type"=>"text",
                                 "list_model" => "municipality_id",
                                 "list_default" => "Municipio...",
                                 "list_options" => $municipalities,
                                 "list_option_value"=>"id",
                                 "list_option_view"=>"name",
                                 "list_option_title"=>"",
                        ])

                        @include("partials.v1.form.form_list",[
                                 "col_with"=>4,
                                 "input_type"=>"text",
                                 "list_model" => "location_id",
                                 "list_default" => "Ubicacion...",
                                 "list_options" => $locations,
                                 "list_option_value"=>"id",
                                 "list_option_view"=>"name",
                                 "list_option_title"=>"",
                        ])

                        @include("partials.v1.form.form_input_icon",[
                                "input_model"=>"direction",
                                "icon_class"=>"",
                                "placeholder"=>"Direccion",
                                "col_with"=>4,
                                "input_type"=>"text",
                       ])
                        @include("partials.v1.form.form_input_icon",[
                                "input_model"=>"email",
                                "icon_class"=>"",
                                "placeholder"=>"E-mail",
                                "col_with"=>4,
                                "input_type"=>"email",
                       ])
                        @include("partials.v1.form.form_input_icon",[
                                 "input_model"=>"latitude",
                                 "icon_class"=>"",
                                 "placeholder"=>"Latitud",
                                 "col_with"=>2,
                                 "input_type"=>"text",
                        ])
                        @include("partials.v1.form.form_input_icon",[
                                 "input_model"=>"longitude",
                                 "icon_class"=>"",
                                 "placeholder"=>"Longitud",
                                 "col_with"=>2,
                                 "input_type"=>"text",
                        ])

                        @include("partials.v1.form.form_list",[
                                 "col_with"=>2,
                                 "list_model" => "stratum_id",
                                 "list_default" => "Estrato...",
                                 "list_options" => $strata,
                                 "list_option_value"=>"id",
                                 "list_option_view"=>"acronym",
                                 "list_option_title"=>"",
                        ])

                        @include("partials.v1.form.form_list",[
                                 "col_with"=>2,
                                 "list_model" => "client_type_id",
                                 "list_default" => "Conexión...",
                                 "list_options" => $client_types,
                                 "list_option_value"=>"id",
                                 "list_option_view"=>"type",
                                 "list_option_title"=>"description",
                        ])

                        @if($client_type != "")
                            @if(strpos($client_type->type, "CONVENCIONAL") !== false)
                                @include("partials.v1.form.form_list",[
                                         "col_with"=>4,
                                         "list_model" => "voltage_level_id",
                                         "list_default" => "Nivel tensión...",
                                         "list_options" => $voltage_levels,
                                         "list_option_value"=>"id",
                                         "list_option_view"=>"level",
                                         "list_option_title"=>"description",
                                ])

                                <div class="my-2 col-md-2 col-sm-12 form-check justify-content-center">
                                    <input title="Marque si el usuario paga impuesto alumbrado publico"
                                           wire:model="public_lighting_tax" class="form-check-input" type="checkbox"
                                           value="" id="flexCheckAP" checked>
                                    <label class="form-check-label" for="flexCheckAP">
                                        ¿ Impuesto AP?
                                    </label>
                                </div>
                                @if($stratum_id > 4)
                                    <div class="my-2 col-md-2 col-sm-12 form-check justify-content-center">
                                        <input wire:model="contribution" class="form-check-input" type="checkbox"
                                               value="" id="flexCheckChecked" checked>
                                        <label class="form-check-label" for="flexCheckChecked">
                                            ¿Contribución?
                                        </label>
                                    </div>
                                @endif
                                @if($stratum_id < 4)
                                    @include("partials.v1.form.form_list",[
                                             "col_with"=>2,
                                             "list_model" => "subsistence_consumption_id",
                                             "list_default" => "Subsidio...",
                                             "list_options" => $subsistence_consumptions,
                                             "list_option_value"=>"id",
                                             "list_option_view"=>"value",
                                             "list_option_title"=>"description",
                                    ])
                                @endif
                            @endif
                        @endif

                        <div class="form-group mb-2 col-md-4 col-sm-12">
                            <select wire:model="network_topology" class="custom-select" name="topologia" id="topologia"
                                    required>
                                <option value="">Topologia red...</option>
                                <option value="monophasic">MONOFASICO</option>
                                <option value=biphasic">BIFASICO</option>
                                <option value="triphasic">TRIFASICO</option>
                            </select>
                        </div>
                        @include("partials.v1.form.form_dropdown_input_searchable",[
                                  "col_with" => 4,
                                  "icon_class" => "fas fa-user",
                                  "dropdown_model" => "network_operator",
                                  "placeholder" => "Operador de red",
                                  "required" => true,
                                  "picked_variable" => $picked_network_operator,
                                  "message_variable" => $message_network_operator,
                                  "dropdown_results" => $network_operators,
                                  "selected_value_function" => "assignNetworkOperator",
                                  "dropdown_result_id" => "id",
                                  "dropdown_result_value" => "name",
                                  "count_bool" => (count($network_operators)>0),

                        ])
                        {{--<div class="form-group mb-2 col-md-4 col-sm-12">
                            <div class="input-group">
                                <div  class="input-group-prepend">
                                <span for="sponsor" class="input-group-text" >
                                    <i>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-video2" viewBox="0 0 16 16">
                                            <path d="M10 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                                            <path d="M2 1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2ZM1 3a1 1 0 0 1 1-1h2v2H1V3Zm4 10V2h9a1 1 0 0 1 1 1v9c0 .285-.12.543-.31.725C14.15 11.494 12.822 10 10 10c-3.037 0-4.345 1.73-4.798 3H5Zm-4-2h3v2H2a1 1 0 0 1-1-1v-1Zm3-1H1V8h3v2Zm0-3H1V5h3v2Z"/>
                                        </svg>
                                    </i>
                                </span>
                                </div>
                                <input wire:model="network_operator"
                                       wire:keydown.enter="assignNetworkOperatorFirst()" type="text" class="form-control" id="network_operator" autocomplete="off" placeholder="network_operator" required >
                                <div class="input-group-append">
                                <span class="input-group-text" >
                                    @if($picked_network_operator)
                                        <span class="badge badge-success">
                                            <i>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                                </svg>
                                            </i>
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                                                    <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                                                </svg>
                                            </i>
                                        </span>
                                    @endif
                                </span>
                                </div>
                            </div>
                            @error("network_operator")
                            <div  class="error-container">
                                <small class="form-text text-danger">{{$message}}</small>
                            </div>
                            @else
                                @if(count($network_operators)>0)
                                    @if(!$picked_network_operator)
                                        <div class="px-4 pt-2 pb-0 rounded shadow" >
                                            @foreach($network_operators as $user)
                                                <a  wire:click="assignNetworkOperator('{{ $user }}')"style="cursor: pointer;">
                                                    {{ $user->name }}
                                                </a>
                                                <hr>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <div class="">
                                        <small class="form-text text-muted">{{$message_network_operator}}</small>
                                    </div>
                                @endif
                                @enderror
                        </div>--}}
                        @if($client_type_id != "")

                            <div class="col-12 text-left"> &nbsp;&nbsp; <strong> Seriales de componentes</strong></div>
                            @foreach($equipment as $index => $item)
                                <div wire:key="equipment-field-{{ $index }}"
                                     class="form-group mb-2 align-content-start col-md-3 col-sm-12">
                                    @include("partials.v1.form.form_list",[
                                 "col_with"=>4,
                                 "mb"=>0,
                                 "disabled" => $item['disable'],
                                 "aux_class"=>"no-border",
                                 "list_model" => "equipment.".$index.".type_id",
                                 "list_default" => "Seleccione equipo...",
                                 "list_options" => $equipment_types,
                                 "list_option_value"=>"id",
                                 "list_option_view"=>"type",
                                 "list_option_title"=>""
                        ])
                                    @include("partials.v1.form.form_dropdown_input_searchable",[
                                  "form_group" => false,
                                  "dropdown_model" => "equipment.".$index.".serial",
                                  "placeholder" => $item['type'],
                                  "required" => true,
                                  "picked_variable" => $item['picked'],
                                  "message_variable" => $item['post'],
                                  "variable_2" => $index??0,
                                  "dropdown_results" => $serials,
                                  "count_bool" => $serials->contains('equipment_type_id', $item['type_id']),
                                  "selected_value_function" => "assignEquipment",
                                  "dropdown_result_id" => "id",
                                  "dropdown_result_value" => "serial",
                        ])
                                </div>
                            @endforeach

                            <div class="d-flex align-items-center col-md-2 col-sm-12">
                                @include("partials.v1.primary_button",[
                                                             "button_align"=>"right" ,
                                                             "click_action" => "addInputEquipment()",
                                                             "button_content"=>"",
                                                             "button_icon" => "fas fa-plus",
                                                             "class_button"=>"b-success"

                                                 ])
                                @include("partials.v1.primary_button",[
                                                             "button_align"=>"right" ,
                                                             "click_action" => "deleteInputEquipment()",
                                                             "button_content"=>"",
                                                             "button_icon" => "fas fa-minus",
                                                             "class_button"=>"b-danger"
                                                 ])
                            </div>
                        @endif
                        @if (session()->has('no_delete'))
                            <div class="alert alert-danger">
                                {{ session('no_delete') }}
                            </div>
                        @endif


                        <div class="text-center">
                            <button id="add" type="submit" class="mb-2 py-2 px-4"
                                    @if(!$picked_network_operator) disabled="true" @endif>
                                <b>
                                    Añadir
                                </b>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </section>
    <script>
        document.addEventListener('livewire:load', function () {
            $('input[type="file"]').change(function (e) {
                var fileName = e.target.files[0].name;
                $('.custom-file-label').html(fileName);
                $("#importar").prop('disabled', false);
            });
            $("input").keydown(function (e) {
                var keyCode = e.which;
                if (keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });
    </script>
</div>


