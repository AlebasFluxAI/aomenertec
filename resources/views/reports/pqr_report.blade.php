<html lang="en">

<style>

.cabeceraprincipal
{

border:1px solid black;
width:660px;
height:80px;
position: absolute;


}
.cabecera
{
font-size: small;
text-align: center;
display: inline-block;
width:220px;
height:70px;
}


.containertabla1
{
border:1px solid black;
width:660px;
position: absolute;
top: 260px;
}

tbody tr:nth-child(odd) {
  background-color: #D2D2D2;
}

tbody tr:nth-child(even) {
  background-color: #ffffff;
}


table {
      width:100%;
  empty-cells: hide;
  text-align: center;
}

body{

      margin-top: 2px;
      margin-bottom: 2px;
      margin-left: 2px;
      margin-right: 2px;
      padding: 1em;

}
html {
	margin: 60.7pt 39.9pt 39.9pt 53.1pt;

}

.title1{
width:200px;
position: relative;
top: 120px; left: 2px;
float:left;
}
.fecha{
border:1px solid black;
text-align: center;
width:260px;
height:80px;
float: right;
}
.datos{
border:1px solid black;
text-align: center;
width:660px;
height:20px;
position: absolute;
top: 200px;
}
.cliente{
text-align: center;
width:660px;
position: relative;
top: 230px;
}
.detalle{
    border:1px solid black;
    text-align: center;
    width:660px;
    height:20px;
    position: absolute;
    top: 330px;
}
.equipo{
    text-align: center;
    width:660px;
    position: relative;
    top: 340px;
}
.descripcion{
    border:1px solid black;
    text-align: justify;
    width:660px;
    height:110px;
    position: absolute;
    top: 400px;
}
.imagenes{
    text-align: justify;
    width:660px;
    position: relative;
    top: 480px;
}
.diagnostico{
    border:1px solid black;
    text-align: justify;
    width:660px;
    height:110px;
    position: absolute;
    top: 710px;
}
#presentacion{
text-align: center;
display: inline-block;
position: relative;
top: 100px;
}
.presentacion1{
width:660px;
height:80px;
position: absolute;
top: 100px;

}
.contacto{

      width:230px;
      text-align: center;
      position: absolute;
      top: 480px; left: 2px;
}
.logo {
      width: 210px; height: 60px;
}
.logo1 {
    width: 210px; height: 60px;
}
img {
    width: 140px; height: 160px;
}
.firma{
    text-align: right;
      position: absolute;
top: 860px; left: 400px;
}

</style>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title >Historial PQR</title>
</head>
<body>
<div class="cabeceraprincipal">
      <div class="cabecera">
           <p><img class="logo" src="./storage/formato/firma_logo.png"></p>
      </div>
      <div class="cabecera">
                  <p><strong>ENERTEC LATINOAMERICA S.A.S.</strong> <br>
                  Av. 40 15-67 Local 148 <br>
                  Villavicencio - Meta
                              </p>
      </div>
      <div class="cabecera">
             <strong>SOPORTE:</strong> <br>
                  soporte@enerteclatam.com <br>
                    (+57) 3058139238
      </div>
</div>
<div class = "presentacion1">
      <div class= "title1" id ="presentacion"><h2>Reporte de PQR</h2></div>

     <div class ="fecha" id ="presentacion"><p><strong> PQR #:&nbsp;&nbsp;</strong>{{$pqr->id}}<br>
                        <strong>Radicado:</strong> {{$pqr->created_at}}<br>
                        <strong>Resuelto:</strong> {{$pqr->fecha_solucion}}</p></div>
                        </div>
      <div class="datos"> <strong> Datos de cliente</strong></div>
    @if($pqr->client_id == null)
      <div class="cliente"><strong>Nombre:</strong>{{$pqr->user->name}}&nbsp;&nbsp;&nbsp;&nbsp;<strong>C.C:</strong>{{$pqr->user->identificacion}}&nbsp;&nbsp;&nbsp;&nbsp;<strong>Celular:</strong>{{$pqr->user->celular}}</div>
    @else
        <div class="cliente"><strong>Nombre:</strong>{{$pqr->cliente->name}}&nbsp;&nbsp;&nbsp;&nbsp;<strong>C.C:</strong>{{$pqr->cliente->identificacion}}&nbsp;&nbsp;&nbsp;&nbsp;<strong>Celular:</strong>{{$pqr->cliente->celular}}</div>
    @endif
        <div class = "containertabla1">
      <table>
            <thead>
                  <tr>
                        <th>Solicitado por:</th>
                        <th>Tramitado por:</th>
                        <th>Tipo de falla</th>
                  </tr>
            </thead>
            <tbody>
                  <tr>
                        <td>{{$pqr->user->name}}</td>
                        <td>{{$ing->name}}</td>
                        <td>{{$pqr->tipo->nombre}}</td>
                  </tr>
            </tbody>
      </table>
      </div>
    <div class="detalle"> <strong> Detalles</strong></div>
    @foreach($equipos as $equipo)
        @if($equipo['id'] == $pqr->tipo_eqipo)
            <div class="equipo"><strong>Equipo:</strong>{{$equipo['name']}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Serial:</strong>{{$pqr->serial_equipo}}</div>
        @endif
    @endforeach
    <div class="descripcion"><p><strong>Descripcion:</strong>{{$pqr->detalle}}
        </p></div>
    <div class="imagenes"><strong>Archivos adjuntos:</strong><br><br><br>
        <?php $counter =0;?>
        @foreach($mensajes as $mensaje)
            @if($mensaje->imagen != null)
                <?php $counter++;?>
                <img src=".{{$mensaje->imagen}}">
                @break($counter == 4)
            @endif
        @endforeach
        @if($counter<4)
            @for($i= $counter; $i<4; $i++)
                <img src="./storage/pqr_imagenes/sin_imagen.png">
            @endfor
        @endif
    </div>
    <div class="diagnostico"><p><strong>Diagnostico y solucion:</strong>{{$pqr->solucion}}
    </p></div>
      <div class="firma">
          <strong>Firma: </strong><img class="logo" src="./storage/{{$ing->avatar}}"><br>
          {{$ing->name}}
        </div>
</body>
</html>
