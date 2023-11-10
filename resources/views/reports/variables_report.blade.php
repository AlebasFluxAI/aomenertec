<html lang="en">

<style>

    .cabeceraprincipal {

        border: 1px solid black;
        width: 660px;
        height: 80px;
        position: absolute;


    }

    .cabecera {
        font-size: small;
        text-align: center;
        display: inline-block;
        width: 218px;
        height: 70px;
    }

    .containertablaaux {
        border: 1px solid black;
        width: 660px;
        position: absolute;
        top: 520px;
    }

    .containerdeuda {
        border: 1px solid black;
        width: 660px;
        position: absolute;
        top: 390px;
    }

    .containertablaaux1 {
        border: 1px solid black;
        width: 660px;
        position: absolute;
        top: 320px;
    }

    .deuda {
        border: 1px solid black;

        width: 660px;
        position: absolute;
        top: 620px;;
    }

    tbody tr:nth-child(odd) {
        background-color: #D2D2D2;
    }

    tbody tr:nth-child(even) {
        background-color: #ffffff;
    }


    table {
        width: 100%;
        empty-cells: hide;
        text-align: center;
    }

    body {

        margin-top: 2px;
        margin-bottom: 2px;
        margin-left: 2px;
        margin-right: 2px;
        padding: 1em;

    }

    html {
        margin: 60.7pt 39.9pt 39.9pt 53.1pt;

    }

    .title1 {
        width: 200px;
        position: relative;
        top: 140px;
        left: 2px;
        float: left;
    }

    .fecha {
        border: 1px solid black;
        text-align: center;
        width: 260px;
        height: 80px;
        float: right;
    }

    .datos {
        border: 1px solid black;
        text-align: center;
        width: 660px;
        height: 20px;
        position: absolute;
        top: 230px;
    }

    .cliente {
        text-align: center;
        width: 660px;
        position: relative;
        top: 260px;
    }

    #presentacion {
        text-align: center;
        display: inline-block;
        position: relative;
        top: 120px;
    }

    .presentacion1 {
        width: 660px;
        height: 80px;
        position: absolute;
        top: 10px;

    }

    .contacto {

        width: 230px;
        text-align: center;
        position: absolute;
        top: 480px;
        left: 2px;
    }

    img {
        width: 210px;
        height: 60px;
    }

    .nota {
        position: relative;
        top: 900px;
    }

    .iva {
        text-align: center;
        position: relative;
        top: 660px;
    }
</style>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Factura de venta</title>
</head>
<body>
<div class="cabeceraprincipal">
    <div class="cabecera">
        <p><img src="./storage/"></p>
    </div>
    <div class="cabecera">

        <p><strong>empresa 1</strong> <br>
            carrera 10<br>
            Villavicencio - Meta
        </p>

    </div>
    <div class="cabecera">

        <p><strong>empresa 1</strong> <br>
            carrera 10<br>
            Villavicencio - Meta
        </p>

    </div>
</div>
<div class="presentacion1">
    <div class="title1" id="presentacion"><h2>Factura de Venta</h2></div>

    <div class="fecha" id="presentacion"><p><strong> Factura #:&nbsp;&nbsp;</strong>xx<br>
            <strong>Radicado:</strong> 2 feb<br>
            <strong>Impreso:</strong> 2feb</p></div>

</div>
<div class="datos"><strong> Datos de cliente</strong></div>
<div class="cliente"><strong>Nombre:</strong>cliente 1&nbsp;&nbsp;&nbsp;&nbsp;<strong>C.C:</strong>1564656&nbsp;&nbsp;&nbsp;&nbsp;<strong>Celular:</strong>14645
</div>
<div class="containertablaaux1">
    <table>
        <thead>
        <tr>
            <th>Vendedor</th>
            <th>Patrocinador</th>
            <th>Terminos</th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td>usiario 1</td>
            <td>cliente 1</td>
            <td>Pago al contado</td>
        </tr>
        <tr>
            <td>cliente 1</td>
            <td>usuario 1</td>
            <td>Pago en linea</td>
        </tr>

        </tbody>

    </table>
</div>
<div class="containertablaaux">
    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th>Descripcion</th>
            <th>Clave</th>
            <th>Cant.(Kwh)</th>
            <th>PUni.</th>
            <th>Total</th>
        </tr>

        </thead>

        <tbody>

        <tr>
            <td>1</td>
            <td>producto</td>
            <td>1615</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <th>ABONOS</th>
            <td></td>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td>1</td>
            <td>xxx</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>100</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <th>Abonos:</th>
            <th>100</th>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <th>Total:</th>
            <th>100</th>
        </tr>
        </tbody>

    </table>
</div>
<div class="containerdeuda">
    <table>
        <thead>
        <tr>
            <th>Deuda</th>
            <th>Descripcion</th>
            <th>Deuda_inicial</th>
            <th>Deuda_actual</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>xx</td>
            <td>xx</td>
            <td>100</td>
            <td>100</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="nota">
    Nota: Recuerde ingresar la clave en su respectivo medidor
</div>


</body>
</html>
