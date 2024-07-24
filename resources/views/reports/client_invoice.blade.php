<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Factura de consumo de energia</title>
    <style>
        @page {
            margin: 0; /* Elimina los márgenes de la página */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
        }

        .header-container {
            position: absolute;
            display: table;
            width: 100%;
            border: 2px solid #B6B7B7;
            background-color: #F3F3F3;
            padding: 10px;
        }

        .footer-container {
            position: fixed;
            bottom: 0;
            left: 0;
            display: table;
            width: 100%;
            height: 50px;
            border: 2px solid #B6B7B7;
            background-color: #F3F3F3;
        }

        .large-container {
            margin-left: 40px;
            display: table;
            width: 90%;
            border: 2px solid #B6B7B7;
            border-radius: 10px;
            background-color: #fff;
            padding: 0;
        }

        .middle-container {
            display: table;
            width: 100%;
            border: 2px solid #B6B7B7;
            border-radius: 10px;
            background-color: #fff;
            padding: 0;
        }

        .column-container {
            display: table;
            width: 33%;
            margin: 10px;
            padding: 10px;
        }

        .flex-row {
            display: table-row;
        }

        .flex-item {
            display: table-cell;
            padding: 10px;
        }

        .logo {
            max-height: 50px;
            width: 200px;
            /* Ajusta el tamaño del logo según tus necesidades */
            margin-right: 20px;
            /* Reducido el margen para dar más espacio */
        }

        .qr_code {
            height: 80px;
            width: 80px;
            /* Ajusta el tamaño del logo según tus necesidades */
            margin-right: 20px;
            /* Reducido el margen para dar más espacio */
        }

        .bar_code {
            height: 50px;
            width: 550px;
        }

        .chart-consumption {
            width: 300px;
            height: 150px;
            margin: 0;
            padding: 0;
        }

        .table-container {
            width: 100%;
            border-collapse: collapse;
            padding-left: 40px;
            padding-right: 40px;
        }

        .table-container td {
            padding: 0;
            vertical-align: top;
        }

    </style>
</head>
<body>
<table class="header-container">
    <tr class="flex-row">
        <td class="flex-item" rowspan="3" style="padding-top: 4px; padding-bottom: 4px"><img
                src={{$admin->icon->url}} alt="Logo" class="logo"></td>
        <td class="flex-item" rowspan="3"
            style="padding-top: 4px; padding-bottom: 4px">{{$network_operator->name. ' '. $network_operator->last_name}}
            <br>{{$network_operator->identification_type.': '.$network_operator->identification}}<br>www.enerteclatam.com
        </td>
        <td class="flex-item"
            style="background: #ffdf7e; border-bottom-left-radius: 15px; padding-top: 4px; padding-bottom: 4px"><strong>No.
                de factura:</strong></td>
        <td class="flex-item"
            style="background: #fff; border-top-right-radius: 15px; text-align: right; padding-top: 4px; padding-bottom: 4px">{{ $other_data['numero_factura'] }}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item"
            style="padding-top: 4px; padding-bottom: 4px; background: #ffdf7e; border-bottom-left-radius: 15px;">
            <strong>Total a pagar:</strong></td>
        <td class="flex-item"
            style="padding-top: 4px; padding-bottom: 4px; background: #fff; border-top-right-radius: 15px; text-align: right;">{{'$'.number_format($value->total, 2, ',', '.')}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item"
            style="padding-top: 4px; padding-bottom: 4px; background: #ffdf7e; border-bottom-left-radius: 15px;">
            <strong>Pago oportuno:</strong></td>
        <td class="flex-item"
            style="padding-top: 4px; padding-bottom: 4px; background: #fff; border-top-right-radius: 15px; text-align: right; ">{{ $other_data['pago_oportuno'] }}</td>
    </tr>
</table>

<table class="large-container" style="margin-top: 110px;">
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;"
            colspan="6"><strong>INFORMACIÓN CLIENTE</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:1px; text-align: right;"><strong>Razón social:</strong></td>
        <td class="flex-item" style="padding:1px;" colspan="2">{{$client->name. ' '. $client->last_name}}</td>
        <td class="flex-item" style="padding:1px; text-align: right;"><strong>Ciudad y Depto</strong></td>
        <td class="flex-item" style="padding:1px;"
            colspan="2">{{$client->address->city.', '.$client->address->state }}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:1px; text-align: right;"><strong>{{$client->identification_type}}:</strong>
        </td>
        <td class="flex-item" style="padding:1px; text-align: left;" colspan="2">{{$client->identification}}</td>
        <td class="flex-item" style="padding:1px; text-align: right;"><strong>No. Medidor</strong></td>
        <td class="flex-item" style="padding:1px; text-align: left;" colspan="2">{{ $other_data['serial_meter'] }}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:1px; text-align: right;"><strong>Dirección:</strong></td>
        <td class="flex-item" style="padding:1px; text-align: left;" colspan="2">{{$client->address->address}}</td>
        <td class="flex-item" style="padding:1px; text-align: right;"><strong>Codigo cliente</strong></td>
        <td class="flex-item" style="padding:1px; text-align: left;" colspan="2">{{$client->code}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:1px; text-align: right;"><strong>Clase de servicio:</strong></td>
        <td class="flex-item" style="padding:1px; text-align: left;"
            colspan="2">{{($client->stratum->name == 'Comercial' || $client->stratum->name == 'Industrial')?$client->stratum->name:'Domicialiario' }}</td>
        <td class="flex-item" style="padding:1px; text-align: right;"><strong>Tipo de cliente</strong></td>
        <td class="flex-item" style="padding:1px; text-align: left;" colspan="2">{{$client->clientType->type}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:1px; text-align: right;"><strong>CUFE:</strong></td>
        <td class="flex-item" style="padding:1px; text-align: left;" colspan="5">
            hdsgailgDUGSDUIgdugdAOBDKÑBFOPIFD54G8564F56SGH456HG454H4DH
        </td>
    </tr>


</table>
<table class="table-container">
    <tr>
        <td>
            <table class="middle-container" style="margin-top: 5px; margin-right: 5px; ">
                <tr class="flex-row">
                    <td class="flex-item"
                        style="padding: 10px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;"
                        colspan="2"><strong>INFORMACIÓN DE PAGO</strong></td>
                </tr>
                <tr class="flex-row" style="background: #B6B7B7;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Total a pagar</strong></td>
                    <td class="flex-item"
                        style="padding:5px; text-align: right">{{'$'.number_format($value->total, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row" style="background: #F3F3F3;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Fecha de pago oportuno</strong>
                    </td>
                    <td class="flex-item" style="padding:5px; text-align: right">{{ $other_data['pago_oportuno'] }}</td>
                </tr>
                <tr class="flex-row" style="background: #B6B7B7;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Fecha de suspensión</strong>
                    </td>
                    <td class="flex-item" style="padding:5px; text-align: right">{{ $other_data['suspension'] }}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Numero de factura</strong></td>
                    <td class="flex-item"
                        style="padding:5px; text-align: right">{{ $other_data['numero_factura'] }}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Periodo facturado</strong></td>
                    <td class="flex-item"
                        style="padding:5px; text-align: right">{{ $other_data['periodo_facturado'] }}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Dias facturados</strong></td>
                    <td class="flex-item"
                        style="padding:5px; text-align: right">{{ $other_data['dias_facturados'] }}</td>
                </tr>
            </table>
            <table class="middle-container" style="margin-top: 5px; margin-right: 5px;">
                <tr class="flex-row">
                    <td class="flex-item"
                        style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;"
                        colspan="3"><strong>HISTORIAL DE CONSUMOS</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item"
                        style="border-radius: 10px; padding:2px; text-align: center;background: #ffdf7e; font-size: 8px">
                        <strong>Consumo facturado <br> mes actual</strong></td>
                    <td class="flex-item"
                        style="border-radius: 10px;padding:2px; text-align: center; background: #F3F3F3; font-size: 8px">
                        <strong>Consumo facturado <br>mes anterior</strong></td>
                    <td class="flex-item"
                        style="border-radius: 10px; padding:2px; text-align: center; background: #B6B7B7; font-size: 8px">
                        <strong>Promedio de consumo <br>ultimos 6 meses</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item"
                        style="padding:2px; text-align: center;">{{number_format($monthly_data->interval_real_consumption, 2, ',', '.')}}</td>
                    <td class="flex-item"
                        style="padding:2px; text-align: center;">{{number_format($other_data['last_month'], 2, ',', '.')}}</td>
                    <td class="flex-item"
                        style="padding:2px; text-align: center">{{number_format($other_data['promedio'], 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="text-align: center" colspan="3" rowspan="3"><img
                            class="chart-consumption" src={{$image_chart_url}} alt="Logo"></td>
                </tr>

            </table>
            <table class="middle-container" style="margin-top: 13px; margin-right: 5px; font-size: 10px ">
                <tr class="flex-row">
                    <td class="flex-item"
                        style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;"
                        colspan="3"><strong>DESGLOSE REACTIVA</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:1px; text-align: center;"><strong>Detalle</strong></td>
                    <td class="flex-item" style="padding:1px; text-align: center; "><strong>Unidad</strong></td>
                    <td class="flex-item" style="padding:1px; text-align: center"><strong>Cantidad</strong></td>
                </tr>

                <tr class="flex-row">
                    <td class="flex-item" style="padding:1px; text-align: left;">Activa</td>
                    <td class="flex-item" style="padding:1px; text-align: center;">kWh</td>
                    <td class="flex-item"
                        style="padding:1px; text-align: center">{{number_format($monthly_data->interval_real_consumption, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:1px; text-align: left;">Reactiva inductiva total</td>
                    <td class="flex-item" style="padding:1px; text-align: center; ">kVArLh</td>
                    <td class="flex-item"
                        style="padding:1px; text-align: center">{{number_format($monthly_data->interval_reactive_inductive_consumption, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:1px; text-align: left;">Reactiva capacitiva</td>
                    <td class="flex-item" style="padding:1px; text-align: center;">kVArCh</td>
                    <td class="flex-item"
                        style="padding:1px; text-align: center">{{number_format($monthly_data->interval_reactive_capacitive_consumption, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:1px; text-align: left;">Reactiva inductiva facturada</td>
                    <td class="flex-item" style="padding:1px; text-align: center; ">kVArLh</td>
                    <td class="flex-item"
                        style="padding:1px; text-align: center">{{number_format($monthly_data->penalizable_reactive_inductive_consumption, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:1px; text-align: left;">Factor M</td>
                    <td class="flex-item" style="padding:1px; text-align: center; ">-</td>
                    <td class="flex-item" style="padding:1px; text-align: center">1</td>
                </tr>
            </table>
        </td>
        <td>
            <table class="middle-container" style="margin-top: 5px; margin-left: 5px; ">
                <tr class="flex-row">
                    <td class="flex-item"
                        style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;"
                        colspan="3"><strong>DETALLE DE LA FACTURA</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: center;"><strong>Item</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7">
                        <strong>Cantidad</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center"><strong>Valor</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Activa</td>
                    <td class="flex-item"
                        style="padding:2px; text-align: center; background: #B6B7B7">{{number_format($monthly_data->interval_real_consumption, 2, ',', '.')}}</td>
                    <td class="flex-item"
                        style="padding:2px; text-align: right">{{'$'.number_format($value->value_active, 2, ',', '.')}}</td>
                </tr>
                @if($client->stratum->id > 4)
                    @if($client->contribution && $other_fees->contribution > 0)
                        <tr class="flex-row">
                            <td class="flex-item" style="padding:2px; text-align: left;">Contribución</td>
                            <td class="flex-item"
                                style="padding:2px; text-align: center; background: #B6B7B7">{{number_format($other_fees->contribution, 2, ',', '.').'%'}}</td>
                            <td class="flex-item"
                                style="padding:2px; text-align: right">{{'$'.number_format($value->value_contribution, 2, ',', '.')}}</td>
                        </tr>
                    @endif
                @elseif($client->stratum->id < 4)
                    @if(isset($other_fees->discount))
                        @if($other_fees->discount > 0)
                            <tr class="flex-row">
                                <td class="flex-item" style="padding:2px; text-align: left;">Descuento</td>
                                <td class="flex-item"
                                    style="padding:2px; text-align: center; background: #B6B7B7">{{number_format($other_fees->discount, 2, ',', '.').'%'}}</td>
                                <td class="flex-item"
                                    style="padding:2px; text-align: right">{{'$'.number_format($value->value_discount, 2, ',', '.')}}</td>
                            </tr>
                        @endif
                    @endif
                @endif
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Reactiva capacitiva</td>
                    <td class="flex-item"
                        style="padding:2px; text-align: center; background: #B6B7B7">{{number_format($monthly_data->interval_reactive_capacitive_consumption, 2, ',', '.')}}</td>
                    <td class="flex-item"
                        style="padding:2px; text-align: right">{{'$'.number_format($value->value_varch, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Reactiva inductiva</td>
                    <td class="flex-item"
                        style="padding:2px; text-align: center; background: #B6B7B7">{{number_format($monthly_data->penalizable_reactive_inductive_consumption, 2, ',', '.')}}</td>
                    <td class="flex-item"
                        style="padding:2px; text-align: right">{{'$'.number_format($value->value_varlh, 2, ',', '.')}}</td>
                </tr>
                @if(isset($other_fees->tax))

                    @if($client->public_lighting_tax && $other_fees->tax > 0)
                        <tr class="flex-row">
                            <td class="flex-item" style="padding:2px; text-align: left;">Impuesto AP</td>
                            <td class="flex-item"
                                style="padding:2px; text-align: center; background: #B6B7B7">{{($other_fees->tax_type == 'money_fee')?'$'.number_format($other_fees->tax, 2, ',', '.'):number_format($other_fees->tax, 2, ',', '.').'%'}}</td>
                            <td class="flex-item"
                                style="padding:2px; text-align: right">{{'$'.number_format($value->value_tax, 2, ',', '.')}}</td>
                        </tr>
                    @endif
                @endif
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 2px; " colspan="3"></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;"><strong>Subtotal energia</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7">-</td>
                    <td class="flex-item"
                        style="padding:2px; text-align: right">{{'$'.number_format($value->subtotal_energy, 2, ',', '.')}}</td>
                </tr>

                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: center;"></td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7">
                        <strong>Cantidad</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center"><strong>Valor</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Saldo de cartera</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7">-</td>
                    <td class="flex-item" style="padding:2px; text-align: right">$0</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Mora</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7">2%</td>
                    <td class="flex-item" style="padding:2px; text-align: right">$0</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 2px; " colspan="3"></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;"><strong>Subtotal otros cobros</strong>
                    </td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7">-</td>
                    <td class="flex-item"
                        style="padding:2px; text-align: right">{{'$'.number_format($value->subtotal_others, 2, ',', '.')}}</td>
                </tr>

                <tr class="flex-row" style="background: #009599;">
                    <td class="flex-item"
                        style="padding: 6px; border-bottom-left-radius: 10px; margin: 0;  text-align: left;"
                        colspan="2"><strong>Total a pagar</strong></td>
                    <td class="flex-item"
                        style="padding: 6px;  border-bottom-right-radius: 10px; margin: 0;  text-align: right;">
                        <strong>{{'$'.number_format($value->total, 2, ',', '.')}}</strong></td>
                </tr>
            </table>
            <table class="middle-container" style="margin-top: 5px; margin-left: 5px; ">
                <tr class="flex-row">
                    <td class="flex-item"
                        style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;"
                        colspan="2"><strong>CALCULO DE TARIFA DE ENERGÍA</strong></td>
                </tr>
                <tr class="flex-row" style="background: #B6B7B7;">
                    <td class="flex-item" style="padding:3px; text-align: left;"><strong>Generación(G)</strong></td>
                    <td class="flex-item"
                        style="padding:3px; text-align: right">{{'$'.number_format($fees->generation, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row" style="background: #F3F3F3;">
                    <td class="flex-item" style="padding:3px; text-align: left;"><strong>Transmisión (T)</strong></td>
                    <td class="flex-item"
                        style="padding:3px; text-align: right">{{'$'.number_format($fees->transmission, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row" style="background: #B6B7B7;">
                    <td class="flex-item" style="padding:3px; text-align: left;"><strong>Distribucción (D)</strong></td>
                    <td class="flex-item"
                        style="padding:3px; text-align: right">{{'$'.number_format($fees->distribution, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row" style="background: #F3F3F3;">
                    <td class="flex-item" style="padding:3px; text-align: left;"><strong>Comercialización (Cv)</strong>
                    </td>
                    <td class="flex-item"
                        style="padding:3px; text-align: right">{{'$'.number_format($fees->commercialization, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row" style="background: #B6B7B7;">
                    <td class="flex-item" style="padding:3px; text-align: left;"><strong>Pérdidas (P)</strong></td>
                    <td class="flex-item"
                        style="padding:3px; text-align: right">{{'$'.number_format($fees->lost, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row" style="background: #F3F3F3;">
                    <td class="flex-item" style="padding:3px; text-align: left;"><strong>Restricciones (R)</strong></td>
                    <td class="flex-item"
                        style="padding:3px; text-align: right">{{'$'.number_format($fees->restriction, 2, ',', '.')}}</td>
                </tr>
                <tr class="flex-row" style="background: #009599;">
                    <td class="flex-item"
                        style="padding: 6px; border-bottom-left-radius: 10px; margin: 0;  text-align: left;"><strong>Total
                            Costo Unitario (CU)</strong></td>
                    <td class="flex-item"
                        style="padding: 6px;  border-bottom-right-radius: 10px; margin: 0;  text-align: right;">
                        <strong>{{'$'.number_format($fees->unit_cost, 2, ',', '.')}}</strong></td>
                </tr>
                <tr class="flex-row" style="background: #009599;">
                    <td class="flex-item"
                        style="padding: 6px; border-bottom-left-radius: 10px; margin: 0;  text-align: left;"><strong>Tarifa
                            opcional</strong></td>
                    <td class="flex-item"
                        style="padding: 6px;  border-bottom-right-radius: 10px; margin: 0;  text-align: right;">
                        <strong>{{'$'.number_format($fees->optional_fee, 2, ',', '.')}}</strong></td>
                </tr>
            </table>

        </td>
    </tr>

</table>
<table class="large-container" style="margin-top: 10px;">
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;"
            colspan="6"><strong>DATOS TÉCNICOS</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Tipo de servicio:</strong></td>
        <td class="flex-item" style="padding:2px;"
            colspan="2">{{($client->stratum->name == 'Comercial' || $client->stratum->name == 'Industrial')?$client->stratum->name:'Domicialiario' }}</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Topologia de la red</strong></td>
        <td class="flex-item" style="padding:2px;" colspan="2">{{$client->network_topology}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Estrato:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->stratum->name}}</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>No. Medidor</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{ $other_data['serial_meter'] }}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Nivel de tensión:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->voltageLevel->level}}</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Propiedad activos</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;"
            colspan="2">{{($client->voltageLevel->level == 'NIVEL 1(b)') ? 'Compartida Cliente-Operador de red':(($client->voltageLevel->level == 'NIVEL 1(c)')?'100% Cliente': '100% Operador de red')}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Circuito:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">12</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Transformador</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">15615</td>
    </tr>


</table>

<table class="table-container">
    <tr>
        <table class="middle-container" style="margin-top: 5px; margin-left: 5px; ">
            <tr class="flex-row">
                <td class="flex-item"
                    style="padding: 10px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;"
                    colspan="2"><strong>Para pagos en banco</strong></td>
            </tr>
            <tr class="flex-row">
                <td class="flex-item" style="padding: 6px; margin: 0; text-align: center;" colspan="2"><img
                        class="bar_code" src="data:image/png;base64,{{ $bar_code }}" alt="Código QR"></td>
            </tr>
        </table>
        <td>
            <table class="middle-container" style="margin-top: 5px; margin-left: 5px; ">
                <tr class="flex-row">
                    <td class="flex-item"
                        style="padding: 10px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;"
                        colspan="2"><strong>Para pagos en linea</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 6px; margin: 0; text-align: center;" colspan="2"><img
                            class="qr_code" src="data:image/png;base64,{{ $qr_code }}" alt="Código QR"></td>
                </tr>
            </table>

        </td>
    </tr>

</table>
{{--<table class="footer-container" style="margin-top: 5px">--}}
{{--    <td class="flex-item" style="margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="2"><img class="qr_code" src="data:image/png;base64,{{ $qr_code }}" alt="Código QR"></td>--}}

{{--</table>--}}
</body>
</html>
