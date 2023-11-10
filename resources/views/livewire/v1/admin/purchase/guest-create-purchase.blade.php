<div>
    <div style="background-color: #f2f2f2;padding: 15px;border-radius: 15px">
        @include("layouts.menu.v1.header_menu_password")
        <br>

        <div class="section-title my-5">
            <h3 class="text-center p3" style="font-size: 30px"><b><span class="fas fa-cash-register"></span> Recarga
                    para clientes</b>
            </h3>

        </div>

        <div class="contenedor-grande">
            <form wire:submit.prevent="submitForm" id="formulario" class="needs-validation" role="form">
                @include("partials.v1.divider_title",[
                                           "title"=>"Ingresa tu código de cliente o identificación para conocer tu tarifa"
                                   ]
               )
                <br>
                @include("partials.v1.form.form_input_icon",[
                         "input_label"=>"Código de cliente",
                         "input_model"=>"client_code",
                         "icon_class"=>"fas fa-user",
                         "placeholder"=>"Ingresa tu codigo de cliente",
                         "col_with"=>6,
                         "input_type"=>"text",
                         "required"=>false
                ])
                @include("partials.v1.form.form_input_icon",[
                         "input_label"=>"Identificación del cliente",
                         "input_model"=>"client_identification",
                         "icon_class"=>"fas fa-user",
                         "placeholder"=>"Ingresa tu identificacion",
                         "col_with"=>6,
                         "input_type"=>"text",
                         "required"=>false
                ])
                @error('blank_client') <span class="error">{{ $message }}</span> @enderror

                @include("partials.v1.form.form_submit_button",[
                                      "button_align"=>"right" ,
                                      "button_icon"=>"fas fa-cash-register",
                                      "button_content"=>"Buscar tarifa"
                          ])
            </form>
            <hr>
            @if($price)
                <br>
                <div class="col-4 offset-4"
                     style="background-color: white;padding: 15px;border-radius: 15px;justify-content: center">
                    <p><span class="fas fa-money-bill"></span> <b>Tu tarifa es:</b></p>
                    <br>

                    @include("partials.v1.table.primary-details-table",
                              [
                                "table_info"=>[
                                 [
                                     "key"=>"Precio x kWh",
                                     "value"=>$price->price." COP",
                                     "number_format"=>"number"

                                 ],

                       ],
                     ])

                </div>
                <br>
                <hr>
                <div class="row"
                     style="margin:10px;background-color: white;padding: 15px;border-radius: 15px;justify-content: center">

                    <div class="col-4">
                        @include("partials.v1.form.form_dropdown",[
                                                  "input_label"=>"Tipo de recarga que desea realizar:",
                                                        "input_type"=>"dropdown",
                                                        "icon_class"=>"fas fa-table-list",
                                                        "placeholder"=>"Seleccione el tipo de recarga",
                                                        "col_with"=>12,
                                                        "dropdown_model"=>"purchase_type",
                                                        "dropdown_values"=>$purchase_types,
                                                        "dropdown_editing"=>false,
                                          ])
                    </div>

                    @if($purchase_type ==\App\Http\Livewire\V1\Admin\Purchase\PurchaseGuestCreateComponent::PURCHASE_TYPE_KWH)
                        <div class="col-4">
                            @include("partials.v1.form.form_input_icon",[
                                         "number_step" => 0.1,
                                         "updated_input"=>"defer",
                                         "input_label"=>"Ingrese la cantidad de Kwh",
                                         "input_model"=>"kwh_quantity",
                                         "icon_class"=>"fas fa-bolt",
                                         "placeholder"=>"Ingrese la cantidad a recargar",
                                         "col_with"=>12,
                                         "input_type"=>"number",
                                         "number_min"=>0,
                                         "required"=>false
                                ])
                        </div>

                        <div class="col-4 text-center"
                             style="background-color: #f2f2f2;margin: 10px;padding: 15px;border-radius: 15px">
                            <div class="row">
                                <div class="col-6">
                                    <i style="color: teal;font-size: 100px;"
                                       class="fa-solid fa-file-invoice-dollar"></i>
                                </div>
                                <div class="col-6">
                                    <p><b>Monto a recargar:</b><br> <span
                                            style="color: teal;font-size: 20px"> {{$kwh_quantity*$price->price}}</span>
                                    </p>
                                    <hr>
                                    <p><b>Cantidad de
                                            KWh: </b><br><span
                                            style="color: teal;font-size: 20px">{{$kwh_quantity}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($purchase_type ==\App\Http\Livewire\V1\Admin\Purchase\PurchaseGuestCreateComponent::PURCHASE_TYPE_CASH)
                        <div class="col-4">
                            @include("partials.v1.form.form_input_icon",[
                                             "number_step" => 50,
                                         "updated_input"=>"defer",
                                         "input_label"=>"Ingrese el monto a recargar",
                                         "input_model"=>"total",
                                         "icon_class"=>"fas fa-dollar",
                                         "placeholder"=>"Ingrese el monto a recargar",
                                         "col_with"=>12,
                                         "input_type"=>"number",
                                         "number_min"=>0,
                                         "required"=>false
                                ])
                        </div>
                        <div class="col-4 text-center"
                             style="background-color: #f2f2f2;margin: 10px;padding: 15px;border-radius: 15px">
                            <div class="row">
                                <div class="col-6">
                                    <i style="color: teal;font-size: 100px;"
                                       class="fa-solid fa-file-invoice-dollar"></i>
                                </div>
                                <div class="col-6">
                                    <p><b>Monto a recargar:</b><br> <span
                                            style="color: teal;font-size: 20px"> {{$total}}</span>
                                    </p>
                                    <hr>
                                    <p><b>Cantidad de
                                            KWh: </b><br><span
                                            style="color: teal;font-size: 20px">{{($total)/($price->price??0)}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($recharge_code)
                        <div class="col-4 text-center"
                             style="background-color: #f2f2f2;margin: 10px;padding: 15px;border-radius: 15px">
                            <div class="row">
                                <div class="col-12">
                                    <p><b>CODIGO:</b><br> <span
                                            style="color: teal;font-size: 20px"> {{ $recharge_code }}</span>
                                    </p>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="text-center">
                    <button wire:click="confirmRecharge" class="mb-2 py-2 px-4">
                        <!--                             data-toggle="modal" data-target="#exampleModal">-->
                        <b>
                            <i class="fas fa-check"></i> Confirmar recarga
                        </b>
                    </button>
                </div>


                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p>Confirmar compra</p>
                            </div>
                            <div class="modal-body">

                                <p> ¿Estas seguro de realizar la recarga ?</p>
                                <p style="color: teal"> <span
                                        style="color: teal;font-size: 20px"> {{$total}}</span>
                                </p>

                                <div class="text-right">

                                    <!-- OBLIGATORIOS -->
                                    <input type="hidden" name="public-key"
                                           value="pub_test_knPE3DSMREXJQgxqle2QgpGDEs7x3wJT"/>
                                    <input type="hidden" name="currency" value="COP"/>
                                    <input type="hidden" name="amount-in-cents" value="{{$total."00"}}"/>
                                    <input type="hidden" name="reference" value="{{$reference}}"/>
                                    <input type="hidden" name="customer-data.email" value="{{$client->email}}"/>
                                    <input type="hidden" name="customer-data.full-name"
                                           value="{{$client->name." ".$client->last_name}}"/>
                                    <input type="hidden" name="customer-data.phone-number"
                                           value="{{$client->phone}}"/>
                                    <input type="hidden" name="customer-data.phone-number-prefix"
                                           value="+57"/>
                                    <input type="hidden" name="customer-data.legal-id"
                                           value="{{$client->identification}}"/>
                                    <input type="hidden" name="customer-data.legal-type"
                                           value="{{$client->identification_type}}"/>
                                    <button wire:click="confirmRecharge" type="button">Pagar recarga</button>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            @endif
        </div>
    </div>
</div>
