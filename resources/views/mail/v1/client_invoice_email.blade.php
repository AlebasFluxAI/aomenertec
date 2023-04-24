<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">

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
                            factura:</b> {{ \App\Http\Resources\V1\Formatter::currencyFormat($invoice->total?:0,$invoice->client->currency())}}
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
                    Costo de distribución
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::numberFormat($consumption->distribution)}}

                </td>
            </tr>
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    Costo de transmición
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::numberFormat($consumption->transmission)}}

                </td>
            </tr>
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    Costo de generación
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::numberFormat($consumption->generation)}}

                </td>
            </tr>
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    Costo de comercilización
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::numberFormat($consumption->commercialization)}}

                </td>
            </tr>
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    Consumo total del mes Kw/h
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::numberFormat($invoice->items->first()->quantity)}}

                </td>
            </tr>
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    <b> Costo de Kw/h</b>
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{\App\Http\Resources\V1\Formatter::currencyFormat( $invoice->items->first()->unit_total)}}
                </td>
            </tr>
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    <b> Consumo total del mes Kw/h</b>
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ \App\Http\Resources\V1\Formatter::numberFormat($invoice->items->first()->quantity)}}

                </td>
            </tr>
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    Fecha de corte
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ $invoice->created_at->subDay()->format("d-m-Y")}}
                </td>
            </tr>
            <tr>
                <td style="padding: 10px;background-color: white;width:75%">
                    Fecha de generación
                </td>
                <td style="padding: 10px;background-color: gray;color:white;width:25%;text-align: right">
                    {{ $invoice->created_at->format("d-m-Y") }}
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
                <td style="background-color: teal;color: white;padding: 1%;">
                    Intereses de mora
                </td>
                <td style="background-color: teal;color: white;padding: 1%;">
                    Otros cargos
                </td>
            </tr>
            <tr>
                <td style="background-color: gray;padding: 1%;text-align: right"
                ">
                {{ \App\Http\Resources\V1\Formatter::numberFormat($invoice->total?:0) ." ". strtoupper($invoice->client->currency())}}
                </td>
                <td style="background-color: gray;padding: 1%;text-align: right"
                ">
                0.0
                </td>
                <td style="background-color: gray;padding: 1%;text-align: right"
                ">
                0.0
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
                    <h3>{{ \App\Http\Resources\V1\Formatter::numberFormat($invoice->total?:0) ." ". strtoupper($invoice->client->currency())}}</h3>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div style="background-color: teal; color: white;text-align: center">
        <br>
    </div>
    <div style="text-align: center">
        <h1 style="text-align: center;color: teal">Pagar factura</h1>
        <a href="https://www.linkedin.com/feed/"><img
                src="https://wompi.com/wcm/connect/wompi.com-8443/854dbb75-bd23-4d22-9ec9-0bdc2b9e1f35/logo-wompi.svg?MOD=AJPERES&CACHEID=ROOTWORKSPACE.Z18_K9HC1202P8T7A068HV5Q0M35A3-854dbb75-bd23-4d22-9ec9-0bdc2b9e1f35-o3nnYCk">
            <br>
            <span style="font-size: 10px;text-decoration: none;color: black">Click aqui para pagar tu factura</span>
        </a>

    </div>
</div>
</body>

</html>
