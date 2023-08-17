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
            width: 98%;
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
            width: 200px;
            /* Ajusta el tamaño del logo según tus necesidades */
            margin-right: 20px;
            /* Reducido el margen para dar más espacio */
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
        <td class="flex-item" rowspan="3"> <img src="https://enerteclatam.com/media/nuhcco0k/logotipo-enerteclatam.png" alt="Logo" class="logo"></td>
        <td class="flex-item" rowspan="3">Enertec Latinoamerica SAS<br>Nit: 901091737-7<br>www.enerteclatam.com</td>
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;" ><strong>No. de cuenta:</strong></td>
        <td class="flex-item" style="background: #fff; border-top-right-radius: 15px; text-align: right;">1000</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;"><strong>Total a pagar:</strong></td>
        <td class="flex-item" style="background: #fff; border-top-right-radius: 15px; text-align: right;">$150.000</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="background: #ffdf7e; border-bottom-left-radius: 15px;"><strong>Pago oportuno:</strong></td>
        <td class="flex-item" style="background: #fff; border-top-right-radius: 15px; text-align: right; ">2/05/2023</td>
    </tr>
</table>

<table class="large-container" style="margin-top: 170px;">
    <tr class="flex-row">
        <td class="flex-item" style="padding: 10px; margin: 0; background: #ffdf7e; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="6"><strong>INFORMACIÓN CLIENTE</strong></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" colspan="6"></td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Razón social:</strong></td>
        <td class="flex-item" style="padding:2px;" colspan="2">Luz Costero alba</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Ciudad y Depto</strong></td>
        <td class="flex-item" style="padding:2px;" colspan="2">Villavicencio, Meta</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Nit:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">1234567895</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>No. Medidor</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">1234567</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Dirección:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">Cra 20 28-05</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Codigo cliente</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">8678546</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Clase de servicio:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">Industrial</td>
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>Mercado</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="2">Regulado</td>
    </tr>
    <tr class="flex-row">
        <td class="flex-item" style="padding:2px; text-align: right;"><strong>CUFE:</strong></td>
        <td class="flex-item" style="padding:2px; text-align: left;" colspan="5">hdsgailgDUGSDUIgdugdAOBDKÑBFOPIFD54G8564F56SGH456HG454H4DH</td>
    </tr>


</table>
<table class="table-container">
    <tr>
        <td>
            <table class="middle-container" style="margin-top: 20px; margin-right: 10px;">
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 10px; margin: 0; background: #ffdf7e; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="2"><strong>INFORMACIÓN DE PAGO</strong></td>
                </tr>
                <tr class="flex-row" style="background: #B6B7B7;">
                    <td class="flex-item" style="padding:5px; text-align: left;"><strong>Total a pagar</strong></td>
                    <td class="flex-item" style="padding:5px; text-align: right" >$198.000</td>
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
        </td>
        <td>
            <table class="middle-container" style="margin-top: 20px; margin-left: 10px;">
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 10px; margin: 0; background: #ffdf7e; border-top-left-radius: 10px;border-top-right-radius: 10px; text-align: center;" colspan="3"><strong>DETALLE DE LA FACTURA</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: center;"><strong>Item</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" ><strong>Cantidad</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center" ><strong>Valor</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Activa</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >1203</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >$1.895.541</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Contribución</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >10%</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >$180000</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Reactiva capacitiva</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >10</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >$2565</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Reactiva inductiva</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >120</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >$25.640</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Impuesto AP</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >8%</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >$98000</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 2px; " colspan="3"></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;"><strong>Subtotal energia</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >-</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >$2.895.541</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 15px; " colspan="3"></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 10px; margin: 0; background: #ffdf7e; text-align: center;" colspan="3"><strong>OTROS COBROS</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: center;"></td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" ><strong>Cantidad</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center" ><strong>Valor</strong></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;">Saldo de cartera</td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >1</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >$0</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 2px; " colspan="3"></td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding:2px; text-align: left;"><strong>Subtotal otros cobros</strong></td>
                    <td class="flex-item" style="padding:2px; text-align: center; background: #B6B7B7" >-</td>
                    <td class="flex-item" style="padding:2px; text-align: right" >$2.895.541</td>
                </tr>
                <tr class="flex-row">
                    <td class="flex-item" style="padding: 15px; " colspan="3"></td>
                </tr>
                <tr class="flex-row" style="background: #ffdf7e;">
                    <td class="flex-item" style="padding: 10px; border-bottom-left-radius: 10px; margin: 0;  text-align: left;" colspan="2"><strong>Total a pagar</strong></td>
                    <td class="flex-item" style="padding: 10px;  border-bottom-right-radius: 10px; margin: 0;  text-align: right;"><strong>$2.000.000</strong></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
