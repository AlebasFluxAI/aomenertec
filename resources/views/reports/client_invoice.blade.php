<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ejemplo de PDF con Dompdf</title>
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
        .chart-consumption {
            width: 300px;
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
        .title {

            /* Centra solo el título */
            margin-bottom: 0;
            font-size: 6px;
        }
        .container_titulo {
            text-align: center;
            /* Agrega esto para centrar el contenido horizontalmente */
            margin: 10px;
            border: 1px solid #60ABB6;
            border-radius: 10px;
            background-color: #60ABB6;
            padding: 1px 10px;


        }
    </style>
</head>
<body>
<table class="header-container">
    <tr class="flex-row">
        <td class="flex-item" rowspan="3"> <img src={{$admin->icon->url}} alt="Logo" class="logo"></td>
        <td class="flex-item" rowspan="3">{{$network_operator->name. ' '. $network_operator->last_name}}<br>{{$network_operator->identification_type.': '.$network_operator->identification}}<br>www.enerteclatam.com</td>
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;" ><strong>No. de cuenta:</strong></td>
        <td class="flex-item" style="background: #fff; border-top-right-radius: 15px; text-align: right;">1000</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;"><strong>Total a pagar:</strong></td>
        <td class="flex-item" style="background: #fff; border-top-right-radius: 15px; text-align: right;">{{'$'.$value->total}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;"><strong>Pago oportuno:</strong></td>
        <td class="flex-item" style="background: #fff; border-top-right-radius: 15px; text-align: right; ">2/05/2023</td>
    </tr>
</table>

<table class="large-container" style="margin-top: 150px;">
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="6"><strong>INFORMACIÓN CLIENTE</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="6"></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Razón social:</strong></td>
        <td class="flex-item" style="padding:2px;" colspan="2">{{$client->name. ' '. $client->last_name}}</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Ciudad y Depto</strong></td>
        <td class="flex-item" style="padding:2px;" colspan="2">{{$client->address->city.', '.$client->address->state }}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>{{$client->identification_type}}:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->identification}}</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>No. Medidor</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">1234567</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Dirección:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->address->address}}</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Codigo cliente</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->code}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Clase de servicio:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{($client->stratum->name == 'Comercial' || $client->stratum->name == 'Industrial')?$client->stratum->name:'Domicialiario' }}</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Tipo de cliente</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->clientType->type}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>CUFE:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="5">hdsgailgDUGSDUIgdugdAOBDKÑBFOPIFD54G8564F56SGH456HG454H4DH</td>
    </tr>


</table>
<table class="table-container">
    <tr>
        <td>
            <table class="middle-container" style="margin-top: 10px; margin-right: 5px; ">
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 10px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="2"><strong>INFORMACIÓN DE PAGO</strong></td>
                </tr>
                <tr class="flex-row" style="background: #B6B7B7;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Total a pagar</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >{{'$'.$value->total}}</td>
                </tr>
                <tr class="flex-row" style="background: #F3F3F3;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Fecha de pago oportuno</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >12/05/2023</td>
                </tr>
                <tr class="flex-row" style="background: #B6B7B7;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Fecha de suspensión</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >20/052023</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:5px;" colspan="2"></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Numero de cuenta</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >1564</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Periodo facturado</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >20/052023 - 20/08/2023</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Dias facturados</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >30</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Fecha de suspensión</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >20/052023</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Factura de venta No.</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >052023</td>
                </tr>
            </table>
            <table class="middle-container" style="margin-top: 13px; margin-right: 5px;">
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="3"><strong>HISTORIAL DE CONSUMOS</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="border-radius: 10px; padding:2px; text-align: center;background: #ffdf7e; font-size: 8px"><strong>Consumo facturado <br> mes actual</strong></td>
                    <td class="flex-item" style="border-radius: 10px;padding:2px; text-align: center; background: #F3F3F3; font-size: 8px" ><strong>Consumo facturado <br>mes anterior</strong></td>
                    <td class="flex-item" style="border-radius: 10px; padding:2px; text-align: center; background: #B6B7B7; font-size: 8px" ><strong>Promedio de consumo <br>ultimos 6 meses</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: center;">{{$monthly_data->interval_real_consumption}}</td>
                    <td class="flex-item" style="padding:2px; text-align: center;" >2650</td>
                    <td class="flex-item" style="padding:2px; text-align: center" >2786</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="text-align: center" colspan="3" rowspan="3"><img src="https://i.ibb.co/VVNm8p9/linexchart-3.png" alt="Logo" class="chart-consumption"></td>
                </tr>

            </table>
            <table class="middle-container" style="margin-top: 13px; margin-right: 5px; font-size: 10px ">
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="3"><strong>DESGLOSE REACTIVA</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: center;"><strong>Detalle</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center; " ><strong>Unidad</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center" ><strong>Cantidad</strong></td>
                </tr>

                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Activa</td>
                    <td class="flex-item" style="padding:2px; text-align: center;" >kWh</td>
                    <td class="flex-item" style="padding:2px; text-align: center" >{{$monthly_data->interval_real_consumption}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Reactiva inductiva total</td>
                    <td class="flex-item" style="padding:2px; text-align: center; " >kVArLh</td>
                    <td class="flex-item" style="padding:2px; text-align: center" >{{$monthly_data->interval_reactive_inductive_consumption}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Reactiva capacitiva</td>
                    <td class="flex-item" style="padding:2px; text-align: center;">kVArCh</td>
                    <td class="flex-item" style="padding:2px; text-align: center" >{{$monthly_data->interval_reactive_capacitive_consumption}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Reactiva inductiva facturada </td>
                    <td class="flex-item" style="padding:2px; text-align: center; " >kVArLh</td>
                    <td class="flex-item" style="padding:2px; text-align: center" >{{$monthly_data->penalizable_reactive_inductive_consumption}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Factor M</td>
                    <td class="flex-item" style="padding:2px; text-align: center; " >-</td>
                    <td class="flex-item" style="padding:2px; text-align: center" >1</td>
                </tr>
            </table>
        </td>
        <td>
            <table class="middle-container" style="margin-top: 10px; margin-left: 5px; ">
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="3"><strong>DETALLE DE LA FACTURA</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: center;"><strong>Item</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" ><strong>Cantidad</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center" ><strong>Valor</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Activa</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >{{$monthly_data->interval_real_consumption}}</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >{{'$'.$value->value_active}}</td>
                </tr>
                @if($client->stratum->id > 4)
                    @if($client->contribution && $other_fees->contribution > 0)
                        <tr class="flex-row">
                            <td class="flex-item" style="padding:2px; text-align: left;">Contribución</td>
                            <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >{{$other_fees->contribution.'%'}}</td>
                            <td class="flex-item" style="padding:2px; text-align: right" >{{'$'.$value->value_contribution}}</td>
                        </tr>
                    @endif
                @elseif($client->stratum->id < 4)
                    @if($other_fees->discount > 0)
                        <tr class="flex-row">
                            <td class="flex-item" style="padding:2px; text-align: left;">Descuento</td>
                            <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >{{$other_fees->discount.'%'}}</td>
                            <td class="flex-item" style="padding:2px; text-align: right" >{{'$'.$value->value_discount}}</td>
                        </tr>
                    @endif
                @endif
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Reactiva capacitiva</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >{{$monthly_data->interval_reactive_capacitive_consumption}}</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >{{'$'.$value->value_varch}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Reactiva inductiva</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >{{$monthly_data->penalizable_reactive_inductive_consumption}}</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >{{'$'.$value->value_varlh}}</td>
                </tr>
                @if($client->public_lighting_tax && $other_fees->tax > 0)
                    <tr class="flex-row">
                        <td class="flex-item" style="padding:2px; text-align: left;">Impuesto AP</td>
                        <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >{{($other_fees->tax_type == 'money_fee')?'$'.$other_fees->tax:$other_fees->tax.'%'}}</td>
                        <td class="flex-item" style="padding:2px; text-align: right" >{{'$'.$value->tax}}</td>
                    </tr>
                @endif
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 2px; " colspan="3"></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;"><strong>Subtotal energia</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >-</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >{{'$'.$value->subtotal_energy}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 15px; " colspan="3"></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; text-align: center;" colspan="3"><strong>OTROS COBROS</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: center;"></td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" ><strong>Cantidad</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center" ><strong>Valor</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Saldo de cartera</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >-</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >$0</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 2px; " colspan="3"></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;"><strong>Subtotal otros cobros</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >-</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >{{'$'.$value->subtotal_others}}</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 15px; " colspan="3"></td>
                </tr>
                <tr class="flex-row" style="background: #009599;">
                    <td class="flex-item" style="padding: 6px; border-bottom-left-radius: 10px; margin: 0;  text-align: left;" colspan="2"><strong>Total a pagar</strong></td>
                    <td class="flex-item" style="padding: 6px;  border-bottom-right-radius: 10px; margin: 0;  text-align: right;"><strong>{{'$'.$value->total}}</strong></td>
                </tr>
            </table>
            <table class="middle-container" style="margin-top: 10px; margin-left: 5px; ">
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="2"><strong>CALCULO DE TARIFA DE ENERGÍA</strong></td>
                </tr>
                <tr class="flex-row" style="background: #B6B7B7;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Generación(G)</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >{{'$'.$fees->generation}}</td>
                </tr>
                <tr class="flex-row" style="background: #F3F3F3;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Transmisión (T)</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >{{'$'.$fees->transmission}}</td>
                </tr>
                <tr class="flex-row" style="background: #B6B7B7;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Distribucción (D)</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >{{'$'.$fees->distribution}}</td>
                </tr>
                <tr class="flex-row" style="background: #F3F3F3;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Comercialización (Cv)</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >{{'$'.$fees->commercialization}}</td>
                </tr>
                <tr class="flex-row" style="background: #B6B7B7;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Pérdidas (P)</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >{{'$'.$fees->lost}}</td>
                </tr>
                <tr class="flex-row" style="background: #F3F3F3;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Restricciones (R)</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >{{'$'.$fees->restriction}}</td>
                </tr>
                <tr class="flex-row" style="background: #009599;">
                    <td class="flex-item" style="padding: 6px; border-bottom-left-radius: 10px; margin: 0;  text-align: left;" ><strong>Total Costo Unitario (CU)</strong></td>
                    <td class="flex-item" style="padding: 6px;  border-bottom-right-radius: 10px; margin: 0;  text-align: right;"><strong>{{'$'.$fees->unit_cost}}</strong></td>
                </tr>
                <tr class="flex-row" style="background: #009599;">
                    <td class="flex-item" style="padding: 6px; border-bottom-left-radius: 10px; margin: 0;  text-align: left;" ><strong>Tarifa opcional</strong></td>
                    <td class="flex-item" style="padding: 6px;  border-bottom-right-radius: 10px; margin: 0;  text-align: right;"><strong>{{'$'.$fees->optional_fee}}</strong></td>
                </tr>
            </table>

        </td>
    </tr>

</table>
<table class="large-container" style="margin-top: 10px;">
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="6"><strong>DATOS TÉCNICOS</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Tipo de servicio:</strong></td>
        <td class="flex-item" style="padding:2px;" colspan="2">{{($client->stratum->name == 'Comercial' || $client->stratum->name == 'Industrial')?$client->stratum->name:'Domicialiario' }}</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Topologia de la red</strong></td>
        <td class="flex-item" style="padding:2px;" colspan="2">{{$client->network_topology}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Estrato:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->stratum->name}}</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>No. Medidor</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">1234567</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Nivel de tensión:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->voltageLevel->level}}</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Propiedad activos</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{($client->voltageLevel->level == 'NIVEL 1(b)') ? 'Compartida Cliente-Operador de red':(($client->voltageLevel->level == 'NIVEL 1(c)')?'100% Cliente': '100% Operador de red')}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Circuito:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">12</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Transformador</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">15615</td>
    </tr>


</table>
</body>
</html>
