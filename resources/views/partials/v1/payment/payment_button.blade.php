<div class="text-center"
     style="background-color: green;padding: 10px;margin-left: 20%;margin-right: 20%;border-radius: 15px">
    <button id="add"
            class="mb-2 py-2 px-4" data-toggle="" data-target="#exampleModal">
        <b>
            <i class="fas fa-check"></i> Pagar factura
        </b>
    </button>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p>Confirmar pago</p>
            </div>
            <div class="modal-body">

                <p> ¿Estas seguro de realizar el pago ?</p>
                <p style="color: teal"> <span
                        style="color: teal;font-size: 20px"> ${{\App\Http\Resources\V1\Formatter::currencyFormat($total)}}</span>
                </p>
                {{number_format($total, 2, '.', '')}}
                <div class="text-right">
                    <form action="https://checkout.wompi.co/p/"
                          method="GET">
                        <!-- OBLIGATORIOS -->
                        <input type="hidden" name="public-key"
                               value="{{$public_key}}"/>
                        <input type="hidden" name="currency" value="COP"/>
                        <input type="hidden" name="amount-in-cents" value="{{number_format($total, 2, '', '')}}"/>
                        <input type="hidden" name="reference" value="{{$reference}}"/>
                        <input type="hidden" name="customer-data.email" value="{{$email}}"/>
                        <input type="hidden" name="customer-data.full-name"
                               value="{{$customer_name." ".$customer_last_name}}"/>
                        <input type="hidden" name="customer-data.phone-number"
                               value="{{$customer_phone}}"/>
                        <input type="hidden" name="customer-data.phone-number-prefix"
                               value="+57"/>
                        <input type="hidden" name="customer-data.legal-id"
                               value="{{$customer_identification}}"/>
                        <input type="hidden" name="customer-data.legal-type"
                               value="{{$customer_identification_type}}"/>
                        <button wire:click="confirmRecharge" type="submit">Pagar factura</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
