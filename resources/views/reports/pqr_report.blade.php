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
        <td class="flex-item" rowspan="3"> <img src="https://www.enerteclatam.com/media/nuhcco0k/logotipo-enerteclatam.png" alt="Logo" class="logo"></td>
        <td class="flex-item" rowspan="3"><br>Enertec Latinoamerica SAS<br>www.enerteclatam.com</td>
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;" ><strong>Reporte PQR</strong></td>
        <td class="flex-item" style="background: #fff; border-top-right-radius: 15px; text-align: right;">375227</td>
    </tr>

    <tr class="flex-row">
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;" ><strong>Tipo de PQR</strong></td>
        <td class="flex-item" style="background: #fff; border-top-right-radius: 15px; text-align: right;">Tècnico</td>
    </tr>

    <tr class="flex-row">
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;"><strong>Fecha de registro<strong></strong></td>
        <td class="flex-item" style="background: #fff; border-top-right-radius: 15px; text-align: right; ">20/09/2023</td>
    </tr>

</table>

<table class="large-container" style="margin-top: 150px;">
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="6"><strong>INFORMACIÓN CLIENTE</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="4"></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Razón social:</strong></td>
        <td class="flex-item" style="padding:2px;" colspan="2">LorenaM</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Cuidad y Depto</strong></td>
        <td class="flex-item" style="padding:2px;" colspan="2">Villavicencio - Meta</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Nit:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">1121919373</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Dirección:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">Calle 25 sur</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Teléfono:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">3209302716</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Código cliente</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">125874</td>
    </tr>
</table>



<table class="large-container" style="margin-top: 20px; margin-right: 5px;">
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;" colspan="12"><strong>INFORMACIÓN DEL PROCEDIMIENTO PQR</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: left; width: 10%;"><strong>Elaboró:</strong></td>
        <td class="flex-item" style="padding:2px; width: 10%;text-align: left;" colspan="3">Lorena Pineda</td>
        <td class="flex-item" style="padding:2px; text-align: right; width: 10%;"><strong>Tramitó:</strong></td>
        <td class="flex-item" style="padding:2px; width: 10%;text-align: left;" colspan="3">Sneider Fuentes</td>
        <td class="flex-item" style="padding:2px; text-align: right; width: 10%;"><strong>Importancia:</strong></td>
        <td class="flex-item" style="padding:2px; width: 10%;text-align: left;" colspan="3">Alta</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left;" colspan="6"><strong>DETALLE:</strong><br>no registra datos en la plataforma</td>
        <td class="flex-item" style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left;" colspan="6"><strong>ASUNTO:</strong> <br>No me deja ver la medicion en la plataforma </td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>
    <tr class="flex-row">

        <td class="flex-item" style="padding: 6px; margin: 0; background: #DFE9F5; border-top-left-radius: 10px; text-align: left;" colspan="12"><strong>DESCRIPCIÓN:</strong> <br>No me deja ver la medicion en la plataforma </td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px;" colspan="12"></td>
    </tr>
    <tr class="flex-row">

        <td class="flex-item" style="padding:2px; text-align: center; width: 10%;"colspan="12"><strong>Fecha de finalización:</strong>29/09/2023</td>

    </tr>

</table>



<table class="large-container" style="margin-top: 20px; margin-right: 5px;">
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;" colspan="12"><strong>ARCHIVOS ADJUNTOS</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item middle-container" style="padding:6px; text-align: left; width: 25%; " ><strong>ARCHIVO1 ARCHIVO1 ARCHIVO1 ARCHIVO1 ARCHIVO1 ARCHIVO1ARCHIVO1 ARCHIVO1 ARCHIVO1ARCHIVO1 ARCHIVO1 ARCHIVO1ARCHIVO1 ARCHIVO1 ARCHIVO1ARCHIVO1 ARCHIVO1 ARCHIVO1ARCHIVO1 ARCHIVO1 ARCHIVO1ARCHIVO1 ARCHIVO1 ARCHIVO1</strong></td>
        <td class="flex-item middle-container" style="padding:6px; text-align: left; width: 25%; "><strong>ARCHIVO2 ARCHIVO2 ARCHIVO2</strong></td>
        <td class="flex-item middle-container" style="padding:6px; text-align: left; width: 25%; "><strong>ARCHIVO3 ARCHIVO3 ARCHIVO3</strong></td>
        <td class="flex-item middle-container" style="padding:6px; text-align: left; width: 25%; "><strong>ARCHIVO4 ARCHIVO4 ARCHIVO4</strong></td>
    </tr>
</table>


<table class="large-container" style="margin-top: 20px; margin-right: 20px;">
    <tr class="flex-row">
        <td class="flex-item" style="padding: 6px; margin: 0; background: #009599; border-top-left-radius: 10px; border-top-right-radius: 10px; text-align: center;" colspan="12"><strong>DIAGNÓSTICO Y SOLUCIÓNS</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:6px; text-align: left;" colspan="12"><strong>Se realizó cambio de tarjeta electronica y limpieza de superficies Se realizó cambio de tarjeta electronica y limpieza de superficies Se realizó cambio de tarjeta electronica y limpieza de superficies</strong></td>

    </tr>

</table>
<table class="column-container_firma " style="margin-top: 20px; margin-right: 20px;">

    <tr class="flex-row">
        <td class="flex-item" style="padding:6px; text-align: right;" colspan="12"><strong>Firma: </strong>SneneneFF</td>
    </tr>

</table>











</body>
</html>
