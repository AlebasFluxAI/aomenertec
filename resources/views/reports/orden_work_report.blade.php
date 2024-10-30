<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Orden de trabajo {{ $work_order->id }}</title>
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
            max-height: 100px;
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
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;"><strong>Órden de
                Trabajo </strong></td>
        <td class="flex-item"
            style="background: #fff; border-top-right-radius: 15px; text-align: right;">{{ $work_order->id }}</td>
    </tr>


    <tr class="flex-row">
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;"><strong>PQR
                Asociado</strong></td>
        <td class="flex-item"
            style="background: #fff; border-top-right-radius: 15px; text-align: right; ">{{ $work_order->pqr ? $work_order->pqr->code : "N/A"}}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;"><strong>Fecha de
                registro</strong></td>
        <td class="flex-item"
            style="background: #fff; border-top-right-radius: 15px; text-align: right; ">{{ $work_order->created_at }}</td>
    </tr>

</table>

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
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>{{$client->identification_type}}:</strong>
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


<table class="large-container" style="margin-top: 20px; margin-right: 5px;">
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;"
            colspan="12"><strong>INFORMACIÓN DE LA ÓRDEN DE SERVICIO</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: left; width: 10%;"><strong>CREADA POR:</strong></td>
        <td class="flex-item" style="padding:2px; width: 10%;text-align: left;"
            colspan="3">{{ $work_order->createdBy()->name . ' ' . $work_order->createdBy()->last_name}}</td>
        <td class="flex-item" style="padding:2px; text-align: center; width: 10%;"><strong>TRAMITÓ:</strong></td>
        <td class="flex-item" style="padding:2px; width: 10%;text-align: left;"
            colspan="3">{{ $work_order->closedBy()->name . ' ' . $work_order->closedBy()->last_name}}</td>
        <td class="flex-item" style="padding:2px; text-align: right; width: 10%;"><strong>ESTADO:</strong></td>
        <td class="flex-item" style="padding:2px; width: 10%;text-align: left;"
            colspan="3">{{ $work_order->status }}</td>

    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left;"
            colspan="12"><strong>DESCRIPCIÓN:</strong><br>{{ $work_order->description }}</td>
        {{--        <td class="flex-item middle-container" colspan="3" style="padding:6px; text-align: center; " ><img src={{$work_order->images[0]->url}} alt="Archivo-adjunto" class="file"></td>--}}
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>
    <tr class="flex-row">

        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left;"
            colspan="6"><strong>HERRAMIENTAS:</strong><br>{{ $work_order->tools }} </td>
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left;"
            colspan="6"><strong>MATERIALES:</strong><br>{{ $work_order->materials }}</td>

    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left; "
            colspan="6"><strong>FECHA DE INICIALIZACIÓN:</strong>{{ $work_order->open_at }}</td>
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left; "
            colspan="6"><strong>FECHA DE FINALIZACIÓN:</strong>{{ $work_order->closed_at }}</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left; "
            colspan="12"><strong>TIPO:</strong>{{ $work_order->type }}</td>
    </tr>

    <tr/>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>

    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;"
            colspan="12"><strong>TIEMPO ESTIMADO DE DURACIÓN</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>

    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: left; width: 10%;" colspan="4">
            <strong>DÍAS: </strong>{{ $work_order->days }}</td>
        <td class="flex-item" style="padding:2px; text-align: center; width: 10%;" colspan="4">
            <strong>HORAS: </strong>{{ $work_order->hours }}</td>
        <td class="flex-item" style="padding:2px; text-align: right; width: 10%;" colspan="4">
            <strong>MINUTOS: </strong>{{ $work_order->minutes }}</td>
    </tr>
    {{--    <tr class="flex-row">--}}
    {{--        <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;" colspan="12"><strong>TIEMPO REAL DE DURACIÓN</strong></td>--}}
    {{--    </tr>--}}
    {{--    <tr class="flex-row">--}}
    {{--        <td class="flex-item" style="padding: 6px;" colspan="12"></td>--}}
    {{--    </tr>--}}

    {{--    <tr class="flex-row">--}}
    {{--        <td class="flex-item" style="padding:2px; text-align: left; width: 10%;"colspan="4"><strong>DÍAS: </strong>-</td>--}}
    {{--        <td class="flex-item" style="padding:2px; text-align: center; width: 10%;" colspan="4"><strong>HORAS: </strong>{{ $work_order->execution_time_hours }}</td>--}}
    {{--        <td class="flex-item" style="padding:2px; text-align: right; width: 10%;"colspan="4"><strong>MINUTOS: </strong>{{ $work_order->execution_time_minutes }}</td>--}}
    {{--    </tr>--}}
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
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
            <strong>{{ $work_order->solution_description }}</strong></td>

    </tr>
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;"
            colspan="12"><strong>RECOMENDACIONES FINALES</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:6px; text-align: left;" colspan="12">
            <strong>{{ $work_order->final_recommendations }}</strong></td>

    </tr>

</table>
<table class="large-container" style="margin-top: 20px; margin-right: 5px;">
    <tr class="flex-row">
        <td class="flex-item"
            style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;"
            colspan="12"><strong>EVIDENCIAS</strong></td>
    </tr>
    <tr class="flex-row">
        @for($i = 0; $i < 4; $i++)
            @if(count($work_order->evidences) > $i)
                <td class="flex-item middle-container" style="padding:6px; text-align: center; "><img
                        src={{$work_order->evidences[$i]->url}} alt="Archivo-adjunto" class="file">
                    <p>{{ $work_order->evidences[$i]->description ?? '-' }}</p>

                </td>
            @else
                <td class="flex-item middle-container" style="padding:6px; text-align: center; "><img
                        src="https://t4.ftcdn.net/jpg/04/73/25/49/360_F_473254957_bxG9yf4ly7OBO5I0O5KABlN930GwaMQz.jpg"
                        alt="Archivo-adjunto" class="file"></td>
            @endif
        @endfor
    </tr>
</table>
<table class="column-container_firma " style="margin-top: 20px; margin-right: 20px;">

    <tr class="flex-row">
        <td class="flex-item" style="padding:6px; text-align: right;" colspan="12"><strong>Firma: </strong><img
                src="{{ $client->clientTechnician()->first()?$client->clientTechnician()->first()->sign ? $client->clientTechnician()->first()->sign->url:"":"" }}"
                alt="Archivo-adjunto"
                class="logo"><br> {{ $work_order->closedBy()->name . ' ' . $work_order->closedBy()->last_name}} -
            Tecnico
        </td>
    </tr>

</table>


</body>
</html>
