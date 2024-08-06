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

        .column-container_firma {
            display: table;
            width: 50%;
            margin: 20px;
            padding: 20px;
            float: right;
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

        .file {
            width: 150px;
            /* Ajusta el tamaño del logo según tus necesidades */
            /* Reducido el margen para dar más espacio */
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
        <td class="flex-item" rowspan="3"><img src={{$admin->icon->url}} alt="Logo" class="logo"></td>
        <td class="flex-item" rowspan="3"><br>{{$network_operator->name. ' '. $network_operator->last_name}}<br>www.enerteclatam.com
        </td>
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;"><strong>Reporte
                PQR {{$pqr->code}}</strong></td>
        <td class="flex-item"
            style="background: #fff; border-top-right-radius: 15px; text-align: right;">{{$pqr->code}}</td>
    </tr>

    <tr class="flex-row">
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;"><strong>Tipo de PQR</strong>
        </td>
        <td class="flex-item"
            style="background: #fff; border-top-right-radius: 15px; text-align: right;">{{$pqr->type}}</td>
    </tr>

    <tr class="flex-row">
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;"><strong>Fecha de
                registro<strong></strong></td>
        <td class="flex-item"
            style="background: #fff; border-top-right-radius: 15px; text-align: right; ">{{$pqr->created_at}}</td>
    </tr>

</table>
@if($client)
    <table class="large-container" style="margin-top: 150px;">
        <tr class="flex-row">
            <td class="flex-item"
                style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;"
                colspan="6"><strong>INFORMACIÓN CLIENTE</strong></td>
        </tr>
        <tr class="flex-row">
            <td class="flex-item" style="padding: 6px;" colspan="4"></td>
        </tr>
        <tr class="flex-row">
            <td class="flex-item" style="padding:2px; text-align: right;"><strong>Razón social:</strong></td>
            <td class="flex-item" style="padding:2px;" colspan="2">{{$client->name. ' '. $client->last_name}}</td>
            <td class="flex-item" style="padding:2px; text-align: right;"><strong>Ciudad y Depto</strong></td>
            <td class="flex-item" style="padding:2px;"
                colspan="2">{{$client->address->city.', '.$client->address->state }}</td>
        </tr>
        <tr class="flex-row">
            <td class="flex-item" style="padding:2px; text-align: right;"><strong>{{$client->identification_type}}
                    :</strong>
            </td>
            <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->identification}}</td>
            <td class="flex-item" style="padding:2px; text-align: right;"><strong>Dirección:</strong></td>
            <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->address->address}}</td>
        </tr>
        <tr class="flex-row">
            <td class="flex-item" style="padding:2px; text-align: right;"><strong>Teléfono:</strong></td>
            <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->phone}}</td>
            <td class="flex-item" style="padding:2px; text-align: right;"><strong>Código cliente</strong></td>
            <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">{{$client->code}}</td>
        </tr>
    </table>
@endif

<table class="large-container" style="margin-top: 20px; margin-right: 5px;">
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;"
            colspan="12"><strong>INFORMACIÓN DEL PROCEDIMIENTO PQR</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: left; width: 10%;"><strong>Elaboró:</strong></td>
        <td class="flex-item" style="padding:2px; width: 10%;text-align: left;"
            colspan="3">{{$created_by->name . ' ' . $created_by->last_name}}</td>
        <td class="flex-item" style="padding:2px; text-align: right; width: 10%;"><strong>Tramitó:</strong></td>
        <td class="flex-item" style="padding:2px; width: 10%;text-align: left;"
            colspan="3">{{$resolved_by ? $resolved_by->name . ' ' . $resolved_by->last_name : '-'}}</td>
        <td class="flex-item" style="padding:2px; text-align: right; width: 10%;"><strong>Importancia:</strong></td>
        <td class="flex-item" style="padding:2px; width: 10%;text-align: left;" colspan="3">{{$pqr->severity}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left;"
            colspan="6"><strong>ASUNTO:</strong> <br>{{$pqr->subject}}</td>
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left;"
            colspan="6"><strong>DETALLE:</strong><br>{{$pqr->detail}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>
    <tr class="flex-row">

        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left;"
            colspan="12"><strong>DESCRIPCIÓN:</strong> <br>{{$pqr->description}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>
    <tr class="flex-row">

        <td class="flex-item" style="padding:2px; text-align: center; width: 10%;" colspan="12"><strong>Fecha de
                finalización:</strong>{{$pqr->status_closed_at}}</td>

    </tr>

</table>


<table class="large-container" style="margin-top: 20px; margin-right: 5px;">
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;"
            colspan="12"><strong>ARCHIVOS ADJUNTOS</strong></td>
    </tr>
    <tr class="flex-row">
        @if($pqr->attach)
            <td class="flex-item middle-container" style="padding:6px; text-align: center; "><img
                    src={{$pqr->attach->url}} alt="Archivo-adjunto" class="file"></td>
            @for($i = 1; $i < 4; $i++)
                @if(count($files) >= $i)
                    <td class="flex-item middle-container" style="padding:6px; text-align: center; "><img
                            src={{$files[$i]}} alt="Archivo-adjunto" class="file"></td>
                @else
                    <td class="flex-item middle-container" style="padding:6px; text-align: center; "><img
                            src="https://t4.ftcdn.net/jpg/04/73/25/49/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg"
                            alt="Archivo-adjunto" class="file"></td>
                @endif
            @endfor

        @else
            @for($i = 1; $i < 5; $i++)
                @if(count($files) >= $i)
                    <td class="flex-item middle-container" style="padding:6px; text-align: center; "><img
                            src={{$files[$i]}} alt="Archivo-adjunto" class="file"></td>
                @else
                    <td class="flex-item middle-container" style="padding:6px; text-align: center; "><img
                            src="https://t4.ftcdn.net/jpg/04/73/25/49/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg"
                            alt="Archivo-adjunto" class="file"></td>
                @endif
            @endfor
        @endif
    </tr>
</table>


<table class="large-container" style="margin-top: 20px; margin-right: 20px;">
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;"
            colspan="12"><strong>DIAGNÓSTICO Y SOLUCIÓN</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:6px; text-align: left;" colspan="12">
            <strong>{{ $close_message->message }}</strong></td>

    </tr>

</table>
<table class="column-container_firma " style="margin-top: 20px; margin-right: 20px;">

    <tr class="flex-row">
        <td class="flex-item" style="padding:6px; text-align: right;" colspan="12"><strong>Firma: </strong><img
                src="{{ $resolved_by->sign ? $resolved_by->sign->url :"" }}" alt="Archivo-adjunto"
                class="logo"><br> {{$resolved_by->name . ' ' . $resolved_by->last_name}} - Tecnico
        </td>
    </tr>

</table>


</body>
</html>
