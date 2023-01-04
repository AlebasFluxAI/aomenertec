<!doctype html>
<html>

<body style="font-family:Georgia,Helvetica,Arial,sans-serif">
<div style="width: 20cm;">
    <table style="background:#ffffff;background-color:#ffffff;width:100%;">
        <tbody>

        <tr>
            <td>
                <div style="text-align:center;font-weight:bold">
                    <p> FACTURA DE VENTA</p>
                    <p> Referencia: {{$invoice_code}}</p>
                </div>

            </td>
        </tr>
    </table>
    <table style=" background:#ffffff;background-color:#ffffff;width:100%; margin-top:20px">
        <tbody>
        <tr>
            <td style="width: 400px;">
                <div style="text-align: left; padding: 10px;">
                    <img src="{{$logo_url}}" style="width:250px;"/>
                </div>
            </td>

        </tr>
    </table>
    <table style="background:#ffffff;background-color:#ffffff;width:100%;">
        <tbody>
        <tr style="text-align: center">
            <td style="width: 400px;">
                <div class="__text_under_logo">
                    <p class="__text"><b>Empresa:</b> {{$client_name}}</p>
                    <p class="__text"><b>NIT:</b> {{$client_document}}</p>
                    <p class="__text"><b>Direccion:</b> {{$client_address}}</p>
                    <p class="__text"><b>Ciudad:</b> {{$client_city}}
                    </p>
                </div>
            </td>
            <td style="width: 400px;">
                <div class="__text_under_logo">
                    <p class="__text"><b>Moneda :</b> {{$currency}}</p>
                    <p class="__text"><b>Nota : </b> {{$notes}}</p>
                    <p class="__text"><b>Fecha de pago : </b> {{$payment_date}}</p>
                    <p class="__text"><b>Fecha de vencimiento : </b> {{$expiration_date}}</p>
                </div>
            </td>
        </tr>
    </table>

    <table class="tabla">
        <thead>
        <tr>
            <th class="_items_td">
                <b>Articulo</b>
            </th>
            <th class="_items_td">
                <b>Descripción</b>
            </th>
            <th class="_items_td">
                <b>Cantidad</b>
            </th>
            <th class="_items_td">
                <b>Valor unitario</b>
            </th>
            <th class="_items_td">
                <b>Subtotal</b>
            </th>
            <th class="_items_td">
                <b>IVA</b>
            </th>
            <th class="_items_td">
                <b>Total</b>
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td class="_items_body">{{$item->billableItem->name}}</td>
                <td class="_items_body">{{$item->billableItem->description}} - <b>{{$item->notes}}</b>
                </td>
                <td class="_items_body">{{$item->quantity}} </td>
                <td class="_items_body">${{$item->unit_total}} </td>
                <td class="_items_body">${{$item->subtotal}} </td>
                <td class="_items_body">${{$item->tax_total}} </td>
                <td class="_items_body">${{$item->total}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <hr style="border: 1px solid rgb(234, 234, 225);">
    <div style="margin-left: 10cm;">
        <table>
            <td>
                <div class="_summary_left">
                    <p><b>Subtotal</b></p>

                    <p><b>IVA</b></p>

                    <p><b>Total</b></p>

                </div>
            </td>
            <td>
                <div class="_summary_right">
                    <p>${{$subtotal}} </p>

                    <p>${{$total_tax}} </p>

                    <p>${{$total}} </p>

                </div>
            </td>
        </table>
    </div>
    <div style="text-align:center;font-weight:bold;background-color: {{$color}};padding: 15px;margin-top:10px;">
        <p style="color: whitesmoke"> {{$invoice_payment_status}}</p>
    </div>
    <div style="margin-top:50px;">

    </div>
</div>
</body>
<style>
    * {

        font-family: Arial, Helvetica, sans-serif;
    }

    .tabla {
        width: 20cm;
        border-color: transparent;
        font-family: Georgia, Helvetica, Arial, sans-serif;
        border: 1px solid rgb(234, 234, 225);
        border-collapse: collapse;
        padding: 5px;

    }

    span {
        font-weight: bold;
    }

    .__text {
        text-align: left;
        color: #151e23;
        font-size: 10px;
    }

    .__text_column {
        font-family: Arial, sans-serif;
        line-height: 10px;
        text-align: left;
        color: #55575d;
        height: 130px;
        border-color: black;
        border-width: 1px;
        border-style: solid;
        padding: 10px;
    }

    ._items_td {
        background-color: rgb(234, 234, 225);
        text-align: center;
        font-family: Georgia, Helvetica, Arial, sans-serif;
        font-size: 10px;
        border: 1px solid black;
        border-collapse: collapse;
        padding: 5px;


    }

    ._items_body {
        background-color: white;
        text-align: center;
        font-family: Georgia, Helvetica, Arial, sans-serif;
        font-size: 10px;
        border: 1px solid rgb(234, 234, 225);
        border-collapse: collapse;
        padding: 10px;


    }

    .__text_under_logo {
        font-family: Arial, sans-serif;
        font-size: 30px;
        line-height: 10px;
        color: #55575d;
    }

    ._summary_left {
        background-color: #dbdbdb;
        width: 6cm;
        text-align: right;
        margin-top: 15px;
        padding-top: 1px;
        padding-bottom: 1px;
        padding-right: 10px;
        color: #3b3b3b;
        line-height: 15px;
        font-weight: bold;
        font-size: 10px;
    }

    ._summary_right {
        background-color: #ffffff;
        width: 5cm;
        text-align: left;
        margin-top: 15px;
        padding-top: 1px;
        padding-bottom: 1px;
        padding-right: 10px;
        line-height: 15px;
        font-weight: bold;
        font-size: 10px;
    }

    #watermark {
        position: fixed;

        /**
            Set a position in the page for your image
            This should center it vertically
        **/
        bottom: 10cm;
        left: 5.5cm;

        /** Change image dimensions**/
        width: 8cm;
        height: 8cm;

        /** Your watermark should be behind every content**/
        z-index: -1000;
    }
</style>

</html>
