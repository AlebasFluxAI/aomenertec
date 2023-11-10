<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml"
>

<head>
    <title>
    </title>
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        body {
            font-family: 'Montserrat', sans-serif;
            padding-bottom: 0;
            margin-bottom: 0;
        }
    </style>
</head>

<body style="background-color:#f2f2f2; width: 19cm;height: 29.7cm;padding: 10px">
<div>
    <table style="width: 100%">
        <tr>
            <td>
                <img alt="logo" height="auto"
                     src="https://enertedevops.s3.us-east-2.amazonaws.com/images/enertec-logotipo-new.png"
                     style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:20%;font-size:13px;"
                     width="150px">
                <br>
            </td>
        </tr>
        <tr>
            <td> ENERTEC COLOMBIA</td>

        </tr>
        <tr>
            <td> Nit: 901091737 - 7</td>
        </tr>
    </table>

    <div style="right: 10px;padding-left: 10px">
        <hr style="border-color: orange;border-width: 1px">
    </div>
    <div>
        <h2>Factura de consumo </h2>
    </div>

    <div>
        <table cellpadding="20px" cellspacing="20px" style="width:100%;">
            <tr>
                <td style="padding:0 30px 0 30px;width: 50%">
                    <p>
                    <h3>
                        {{ $invoice->client->name }}</h3>
                    {{ $invoice->client->billingInformation->first()->address }}
                    - {{ $invoice->client->addresses->first()->country }}
                    </p>
                </td>
                <td style="padding:0 30px 0 30px;width: 50%">
                    <p><b>Numero de factura:</b> {{$invoice->code}}</p>
                    <p><b>Total de
                            factura:</b>
                        {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::TOTAL_INVOICE)->first()->id)->first()->total)}}

                    </p>
                    <p>
                        <b>Fecha de corte: </b>
                        {{ $invoice->created_at->subDay()->format("d-m-Y")}}
                    </p>
                    <p>
                        <b>Fecha de generación: </b>
                        {{ $invoice->created_at->format("d-m-Y") }}

                    </p>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <div style="background-color: orangered;color: white;padding: 10px;text-align: center">
            <b>Información de consumo</b>
        </div>
        <table style="width: 100%">
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    <b>Consumo total del mes Kw/h</b>
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::numberFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::TOTAL_CONSUMPTION)->first()->id)->first()->quantity)}}

                </td>
            </tr>
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    <b> Costo de Kw/h</b>
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::TOTAL_CONSUMPTION)->first()->id)->first()->unit_total)}}
                </td>
            </tr>
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    <b> Costo bruto total del mes</b>
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::TOTAL_CONSUMPTION)->first()->id)->first()->total)}}

                </td>
            </tr>
        </table>
    </div>
    <br>
    <div>
        <div style="background-color: orangered;color: white;padding: 10px;text-align: center">
            <b>Costo del Kw/h Desglosado</b>
        </div>
        <table style="width: 100%">
            @if(!$client->consumptionFeeFlag())
                <tr>
                    <td style="padding: 10px;background-color: white;width:75%">
                        Costo de distribución
                    </td>
                    <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                        {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::DISTRIBUTION_ITEM)->first()->id)->first()->total)}}

                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;background-color: white;width:75%">
                        Costo de transmición
                    </td>
                    <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                        {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::TRANSMISSION_ITEM)->first()->id)->first()->total)}}

                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;background-color: white;width:75%">
                        Costo de generación
                    </td>
                    <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                        {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::GENERATION_ITEM)->first()->id)->first()->total)}}

                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;background-color: white;width:75%">
                        Costo de comercialización
                    </td>
                    <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                        {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::COMMERCIALIZATION_ITEM)->first()->id)->first()->total)}}

                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;background-color: white;width:75%">
                        Costo de perdidas
                    </td>
                    <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                        {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::LOST_ITEM)->first()->id)->first()->total)}}

                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px;background-color: white;width:75%">
                        Costo de restricciones
                    </td>
                    <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                        {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::RESTRICTION_ITEM)->first()->id)->first()->total)}}

                    </td>
                </tr>
            @endif
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    <b> Costo de Kw/h</b>
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::TOTAL_CONSUMPTION)->first()->id)->first()->unit_total)}}
                </td>
            </tr>

        </table>
    </div>
    <br>
    <div>
        <div style="background-color: orangered;color: white;padding: 10px;text-align: center">
            <b>Otros costos</b>
        </div>
        <table style="width: 100%">
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    Impuesto alumbrado publico
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::PUBLIC_TAX_TYPE_TOTAL)->first()->id)->first()->total)}}

                </td>
            </tr>
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    Contribucion
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::CONTRIBUTION_ITEM)->first()->id)->first()->total)}}

                </td>
            </tr>

        </table>
    </div>
    <br>
    <div>
        <div style="background-color: orangered;color: white;padding: 10px;text-align: center">
            <b>Descuentos</b>
        </div>
        <table style="width: 100%">
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    Descuentos
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    - {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::DISCOUNT_ITEM)->first()->id)->first()->total)}}

                </td>
            </tr>

        </table>
    </div>
    <br>
    <div>
        <table style="width: 100%;">
            <tr>
                <td style="background-color: teal;color: white;padding: 1%;">
                    Valor total a pagar
                </td>

            </tr>
            <tr>
                <td style="background-color: gray;padding: 1%;text-align: right"
                ">
                {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::TOTAL_INVOICE)->first()->id)->first()->total)}}
                </td>

            </tr>
        </table>
    </div>
    <br>
    <div>
        <table style="width: 40%;">
            <tr>
                <td style="background-color: orangered;color: white;text-align: center">
                    <h3>Total a pagar</h3>
                    <hr style="margin-left: 5px;margin-right: 5px">
                    <h3>
                        {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->items->where("billable_item_id",\App\Models\V1\BillableItem::whereSlug(\App\Models\V1\BillableItem::TOTAL_INVOICE)->first()->id)->first()->total)}}

                    </h3>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div style="background-color: teal; color: white;text-align: center">
        <br>
    </div>
    <div style="text-align: center">
        <a href="https://www.linkedin.com/feed/">
            <h1 style="text-align: center;color: teal">Pagar factura</h1>
            <span style="font-size: 10px;text-decoration: none;color: black">Click aqui para pagar tu factura</span>
        </a>

    </div>
</div>
</body>

</html>
