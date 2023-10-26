<div class="col-2">
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
        <button type="submit"
                data-toggle="tooltip" data-placement="top" title="Pagar factura"
        ><span class="fas fa-dollar-sign text-success"></span></button>
    </form>
</div>
