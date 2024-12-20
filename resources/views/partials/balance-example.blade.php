

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post - Mi Blog de Confianza</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>


<style>
    /* Estilos generales */
body {
    font-family: sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Barra de navegación superior */
.navbar {
    background-color: #3F51B5; /* Azul fuerte */
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.logo {
    font-size: 1.5em;
    margin: 0;
}

.tagline {
    font-size: 1.1em;
    margin: 0;
}

/* Estilo del post */
main {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    background
}

.post {
    background-color: #ffffff;
    padding: 20px;
    max-width: 800px;
    width: 100%;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center; /* Centrar el contenido */
}

.post h2 {
    font-size: 2em;
    color: #333;
    margin-bottom: 10px;
}

.post p {
    font-size: 1.2em;
    color: #555;
    margin-top: 0;
}

/* Pie de página (opcional) */
footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px 0;
    width: 100%;
}

.balance-section {
    text-align: center;
    margin-bottom: 20px;
    margin: 100px;
}

.balance-section h2 {
    font-size: 36px;
    font-weight: bold;
    color: #00a3b7; /* Color del texto */
    margin: 0;
}

.divider {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
}

.divider-line {
    width: 40px;
    height: 4px;
    background-color: #00a3b7; /* Color del subrayado */
}

.divider::before, .divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background-color: #e0e0e0; /* Color de la línea de los lados */
    margin: 0 15px;
}

.balance-section::after {
    content: "";
    display: block;
    height: 4px;
    width: 100%;
    background-color: #00a3b7; /* Color de la línea inferior */
    margin-top: 10px;
}

/* estilo de los botones de accion */
.container {
      margin: 0px; /* Añade 20px de espacio en todos los lados */
      padding: 10px; /* Opcional: Añade 20px de espacio interno */
      border: 1px solid #000; /* Un borde para visualizar la sección */
      background-color: #f9f9f9; /* Fondo de ejemplo */
    }
.custom-btn {
    background-color: #58c0d3; /* Color del botón basado en la imagen */
    border-color: #58c0d3; /* Borde del botón con el mismo color */
}

.custom-btn i {
    color: white; /* Cambia el color del ícono */
}


</style>

<body>
    <!-- Barra de navegación superior -->
    <header>
        <!-- <div class="navbar">
            <h1 class="logo">Hyperblog</h1>
            <p class="tagline">Tu blog de confianza</p>

        </div> -->

        <div class="balance-section position-relative">
            <h2>BALANCE</h2>
            <div class="divider">
                <span class="divider-line"></span>
            </div>
        </div>
    </header>
<main>
    <section class="container" >
        <!-- <input type="text" name="hola" id="hola"> -->
        <table class="table table-hover table-centered">
            <thead>
                <tr>
                    <th>Nivel</th>
                    <th>Serial Medidor</th>
                    <th>Voltaje</th>
                    <th>Corriente</th>
                    <th>Potencia</th>
                    <th>Consumo</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <!-- sesion de la tabla y nivel 7 -->
                <tr id="a1">
                    <td>7 <i id="btnAbrir-a1" onclick="abrir('a2','a1')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a1" onclick="cerrar('a2', 'a1')" class="fa-solid fa-caret-up pruebas"></i>
                        <!-- <button id="btnAbrir-a1" class="secondary dropdown-toggle" onclick="abrir('a1')"></button> -->
                    <!-- <button id="btnCerrar-a1" class="btn btn-danger" onclick="cerrar('a1')">Cerrar</button></td> -->
                    <td>11111</td>
                    <td>120</td>
                    <td>10</td>
                    <td>#1</td>
                    <td>#1</td>
                    <td>
                        <button class="btn  btn-sm custom-btn">
                            <i class="bi bi-pc-display-horizontal"></i>
                        </button>
                        <button class="btn  btn-sm custom-btn">
                            <i class="bi bi-currency-pound"></i>
                        </button>
                    </td>
                </tr>

                    <!-- Tsession nivel 6 -->
        
                <tr id="a2" class="a2" style="background-color: rgb(33, 226, 33)" >
                    <td style="padding-left: 20px">6
                    <i id="btnAbrir-a2" onclick="abrir('a3', 'a2')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a2" onclick="cerrar('a3', 'a2')" class="fa-solid fa-caret-up pruebas"></i>
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 20px">11111</td>
                    <td style="padding-left: 20px">120</td>
                    <td style="padding-left: 20px">10</td>
                    <td style="padding-left: 20px">#1</td>
                    <td style="padding-left: 20px">#1</td>
                    <td style="padding-left: 20px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>
                
                <!-- sesion del nivel 5 -->
           
            <div id="a3" >
                <tr class="a3" style="background-color: rgb(173, 165, 165); margin-left: 50px">
                    <td style="padding-left: 30px;">5
                    <i id="btnAbrir-a3" onclick="abrir('a4', 'a3')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a3" onclick="cerrar('a4', 'a3')" class="fa-solid fa-caret-up pruebas"></i>
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 30px;">11111</td>
                    <td style="padding-left: 30px;">120</td>
                    <td style="padding-left: 30px;">10</td>
                    <td style="padding-left: 30px;">#1</td>
                    <td style="padding-left: 30px;">#1</td>
                    <td style="padding-left: 30px;">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a3" style="background-color: rgb(173, 165, 165);">
                    <td style="padding-left: 30px;">5
                    <!-- <i id="btnAbrir-a3" onclick="abrir('a4', 'a3')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a3" onclick="cerrar('a4', 'a3')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 30px;">11111</td>
                    <td style="padding-left: 30px;">120</td>
                    <td style="padding-left: 30px;">10</td>
                    <td style="padding-left: 30px;">#1</td>
                    <td style="padding-left: 30px;">#1</td>
                    <td style="padding-left: 30px;">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a3" style="background-color: rgb(173, 165, 165);">
                    <td style="padding-left: 30px;">5
                    <!-- <i id="btnAbrir-a3" onclick="abrir('a4', 'a3')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a3" onclick="cerrar('a4', 'a3')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 30px;">11111</td>
                    <td style="padding-left: 30px;">120</td>
                    <td style="padding-left: 30px;">10</td>
                    <td style="padding-left: 30px;">#1</td>
                    <td style="padding-left: 30px;">#1</td>
                    <td style="padding-left: 30px;">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>


                <tr class="a3" style="background-color: rgb(173, 165, 165);">
                    <td style="padding-left: 30px;">5
                    <!-- <i id="btnAbrir-a3" onclick="abrir('a4', 'a3')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a3" onclick="cerrar('a4', 'a3')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 30px;">11111</td>
                    <td style="padding-left: 30px;">120</td>
                    <td style="padding-left: 30px;">10</td>
                    <td style="padding-left: 30px;">#1</td>
                    <td style="padding-left: 30px;">#1</td>
                    <td style="padding-left: 30px;">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a3" style="background-color: rgb(173, 165, 165);">
                    <td style="padding-left: 30px;">5
                    <!-- <i id="btnAbrir-a3" onclick="abrir('a4', 'a3')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a3" onclick="cerrar('a4', 'a3')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 30px;">11111</td>
                    <td style="padding-left: 30px;">120</td>
                    <td style="padding-left: 30px;">10</td>
                    <td style="padding-left: 30px;">#1</td>
                    <td style="padding-left: 30px;">#1</td>
                    <td style="padding-left: 30px;">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>
            </div>
           
            






            <!-- sesion del nivel 4 -->
            <div id="a4" >
                <tr class="a4" style="background-color: #92FA73">
                    <td style="padding-left: 40px">4
                    <i id="btnAbrir-a4" onclick="abrir('a5', 'a4')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a4" onclick="cerrar('a5', 'a4')" class="fa-solid fa-caret-up pruebas"></i>
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 40px;">11111</td>
                    <td style="padding-left: 40px;">120</td>
                    <td style="padding-left: 40px;">10</td>
                    <td style="padding-left: 40px;">#1</td>
                    <td style="padding-left: 40px;">#1</td>
                    <td style="padding-left: 40px;">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>

                    
                </tr>

                <tr class="a4" style="background-color: #92FA73"|>
                    <td style="padding-left: 40px">4
                    <!-- <i id="btnAbrir-a4" onclick="abrir('a4')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a4" onclick="cerrar('a4')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 40px">11111</td>
                    <td style="padding-left: 40px">120</td>
                    <td style="padding-left: 40px">10</td>
                    <td style="padding-left: 40px">#1</td>
                    <td style="padding-left: 40px">#1</td>
                    <td style="padding-left: 40px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a4" style="background-color: #92FA73"|>
                    <td style="padding-left: 40px">4
                    <!-- <i id="btnAbrir-a4" onclick="abrir('a4')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a4" onclick="cerrar('a4')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 40px">11111</td>
                    <td style="padding-left: 40px">120</td>
                    <td style="padding-left: 40px">10</td>
                    <td style="padding-left: 40px">#1</td>
                    <td style="padding-left: 40px">#1</td>
                    <td style="padding-left: 40px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a4" style="background-color: #92FA73"|>
                    <td style="padding-left: 40px">4
                    <!-- <i id="btnAbrir-a4" onclick="abrir('a4')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a4" onclick="cerrar('a4')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 40px">11111</td>
                    <td style="padding-left: 40px">120</td>
                    <td style="padding-left: 40px">10</td>
                    <td style="padding-left: 40px">#1</td>
                    <td style="padding-left: 40px">#1</td>
                    <td style="padding-left: 40px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a4" style="background-color: #92FA73"|>
                    <td style="padding-left: 40px">4
                    <!-- <i id="btnAbrir-a4" onclick="abrir('a4')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a4" onclick="cerrar('a4')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 40px">11111</td>
                    <td style="padding-left: 40px">120</td>
                    <td style="padding-left: 40px">10</td>
                    <td style="padding-left: 40px">#1</td>
                    <td style="padding-left: 40px">#1</td>
                    <td style="padding-left: 40px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>
            </div>

            <!-- sesion del nivel 3 -->
            <div id="a5" >
                <tr class="a5" style="background-color: #D96E3B">
                    <td style="padding-left: 50px">3
                    <i id="btnAbrir-a5" onclick="abrir('a6', 'a5')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a5" onclick="cerrar('a6', 'a5')" class="fa-solid fa-caret-up pruebas"></i>
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 50px;">11111</td>
                    <td style="padding-left: 50px;">120</td>
                    <td style="padding-left: 50px;">10</td>
                    <td style="padding-left: 50px;">#1</td>
                    <td style="padding-left: 50px;">#1</td>
                    <td style="padding-left: 50px;">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                    
                </tr>

                <tr class="a5" style="background-color: #D96E3B"|>
                    <td style="padding-left: 50px">3
                    <!-- <i id="btnAbrir-a5" onclick="abrir('a5')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a5" onclick="cerrar('a5')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 50px">11111</td>
                    <td style="padding-left: 50px">120</td>
                    <td style="padding-left: 50px">10</td>
                    <td style="padding-left: 50px">#1</td>
                    <td style="padding-left: 50px">#1</td>
                    <td style="padding-left: 50px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a5" style="background-color: #D96E3B"|>
                    <td style="padding-left: 50px">3
                    <!-- <i id="btnAbrir-a5" onclick="abrir('a5')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a5" onclick="cerrar('a5')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 50px">11111</td>
                    <td style="padding-left: 50px">120</td>
                    <td style="padding-left: 50px">10</td>
                    <td style="padding-left: 50px">#1</td>
                    <td style="padding-left: 50px">#1</td>
                    <td style="padding-left: 50px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a5" style="background-color: #D96E3B"|>
                    <td style="padding-left: 50px">3
                    <!-- <i id="btnAbrir-a5" onclick="abrir('a5')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a5" onclick="cerrar('a5')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 50px">11111</td>
                    <td style="padding-left: 50px">120</td>
                    <td style="padding-left: 50px">10</td>
                    <td style="padding-left: 50px">#1</td>
                    <td style="padding-left: 50px">#1</td>
                    <td style="padding-left: 50px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a5" style="background-color: #D96E3B"|>
                    <td style="padding-left: 50px">3
                    <!-- <i id="btnAbrir-a5" onclick="abrir('a5')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a5" onclick="cerrar('a5')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 50px">11111</td>
                    <td style="padding-left: 50px">120</td>
                    <td style="padding-left: 50px">10</td>
                    <td style="padding-left: 50px">#1</td>
                    <td style="padding-left: 50px">#1</td>
                    <td style="padding-left: 50px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a5" style="background-color: #D96E3B"|>
                    <td style="padding-left: 50px">3
                    <!-- <i id="btnAbrir-a5" onclick="abrir('a5')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a5" onclick="cerrar('a5')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 50px">11111</td>
                    <td style="padding-left: 50px">120</td>
                    <td style="padding-left: 50px">10</td>
                    <td style="padding-left: 50px">#1</td>
                    <td style="padding-left: 50px">#1</td>
                    <td style="padding-left: 50px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>
            </div>


            <!-- sesion del nivel 2 -->

            <div id="a6" >
                <tr class="a6" style="background-color: #DA89CF">
                    <td style="padding-left: 60px">2
                    <i id="btnAbrir-a6" onclick="abrir('a7', 'a6')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a6" onclick="cerrar('a7', 'a6')" class="fa-solid fa-caret-up pruebas"></i>
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 60px;">11111</td>
                    <td style="padding-left: 60px;">120</td>
                    <td style="padding-left: 60px;">10</td>
                    <td style="padding-left: 60px;">#1</td>
                    <td style="padding-left: 60px;">#1</td>
                    <td style="padding-left: 60px;">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                    
                </tr>

                <tr class="a6" style="background-color: #DA89CF"|>
                    <td style="padding-left: 60px">2
                    <!-- <i id="btnAbrir-a6" onclick="abrir('a6')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a6" onclick="cerrar('a6')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 60px">11111</td>
                    <td style="padding-left: 60px">120</td>
                    <td style="padding-left: 60px">10</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a6" style="background-color: #DA89CF"|>
                    <td style="padding-left: 60px">2
                    <!-- <i id="btnAbrir-a6" onclick="abrir('a6')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a6" onclick="cerrar('a6')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 60px">11111</td>
                    <td style="padding-left: 60px">120</td>
                    <td style="padding-left: 60px">10</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a6" style="background-color: #DA89CF"|>
                    <td style="padding-left: 60px">2
                    <!-- <i id="btnAbrir-a6" onclick="abrir('a6')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a6" onclick="cerrar('a6')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 60px">11111</td>
                    <td style="padding-left: 60px">120</td>
                    <td style="padding-left: 60px">10</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a6" style="background-color: #DA89CF"|>
                    <td style="padding-left: 60px">2
                    <!-- <i id="btnAbrir-a6" onclick="abrir('a6')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a6" onclick="cerrar('a6')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 60px">11111</td>
                    <td style="padding-left: 60px">120</td>
                    <td style="padding-left: 60px">10</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a6" style="background-color: #DA89CF"|>
                    <td style="padding-left: 60px">2
                    <!-- <i id="btnAbrir-a6" onclick="abrir('a6')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a6" onclick="cerrar('a6')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 60px">11111</td>
                    <td style="padding-left: 60px">120</td>
                    <td style="padding-left: 60px">10</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a6" style="background-color: #DA89CF"|>
                    <td style="padding-left: 60px">2
                    <!-- <i id="btnAbrir-a6" onclick="abrir('a6')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a6" onclick="cerrar('a6')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 60px">11111</td>
                    <td style="padding-left: 60px">120</td>
                    <td style="padding-left: 60px">10</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">#1</td>
                    <td style="padding-left: 60px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>
            </div>

            <!-- sesion del nivel 1 -->

            <div id="a7" >
                <tr class="a7" style="background-color:#D9A13B">
                    <td style="padding-left: 70px">1
                    <i id="btnAbrir-a7" onclick="abrir('a8', 'a7')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a7" onclick="cerrar('a8', 'a7')" class="fa-solid fa-caret-up pruebas"></i>
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 70px;">11111</td>
                    <td style="padding-left: 70px;">120</td>
                    <td style="padding-left: 70px;">10</td>
                    <td style="padding-left: 70px;">#1</td>
                    <td style="padding-left: 70px;">#1</td>
                    <td style="padding-left: 70px;">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                    
                </tr>

                <tr class="a7" style="background-color:#D9A13B">
                    <td style="padding-left: 70px">1
                    <!-- <i id="btnAbrir-a7" onclick="abrir('a2')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a7" onclick="cerrar('a2')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 70px">11111</td>
                    <td style="padding-left: 70px">120</td>
                    <td style="padding-left: 70px">10</td>
                    <td style="padding-left: 70px">#1</td>
                    <td style="padding-left: 70px">#1</td>
                    <td style="padding-left: 70px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a7" style="background-color:#D9A13B">
                    <td style="padding-left: 70px">1
                    <!-- <i id="btnAbrir-a7" onclick="abrir('a2')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a7" onclick="cerrar('a2')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 70px">11111</td>
                    <td style="padding-left: 70px">120</td>
                    <td style="padding-left: 70px">10</td>
                    <td style="padding-left: 70px">#1</td>
                    <td style="padding-left: 70px">#1</td>
                    <td style="padding-left: 70px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a7" style="background-color:#D9A13B">
                    <td style="padding-left: 70px">1
                    <!-- <i id="btnAbrir-a7" onclick="abrir('a2')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a7" onclick="cerrar('a2')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 70px">11111</td>
                    <td style="padding-left: 70px">120</td>
                    <td style="padding-left: 70px">10</td>
                    <td style="padding-left: 70px">#1</td>
                    <td style="padding-left: 70px">#1</td>
                    <td style="padding-left: 70px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a7" style="background-color:#D9A13B">
                    <td style="padding-left: 70px">1
                    <!-- <i id="btnAbrir-a7" onclick="abrir('a2')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a7" onclick="cerrar('a2')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 70px">11111</td>
                    <td style="padding-left: 70px">120</td>
                    <td style="padding-left: 70px">10</td>
                    <td style="padding-left: 70px">#1</td>
                    <td style="padding-left: 70px">#1</td>
                    <td style="padding-left: 70px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>

                <tr class="a7" style="background-color:#D9A13B">
                    <td style="padding-left: 70px">1
                    <!-- <i id="btnAbrir-a7" onclick="abrir('a2')" class="fa-solid fa-caret-down" style ="hove:cursor-pointer"></i>
                    <i id="btnCerrar-a7" onclick="cerrar('a2')" class="fa-solid fa-caret-up pruebas"></i> -->
                    <!-- <button id="btnAbrir-a2" class="secondary dropdown-toggle" onclick="abrir('a2')"></button>
                    <button id="btnCerrar-a2" class="btn btn-danger btn-secondary dropdown-toggle" onclick="cerrar('a2')">Cerrar</button></td> -->
                    <td style="padding-left: 70px">11111</td>
                    <td style="padding-left: 70px">120</td>
                    <td style="padding-left: 70px">10</td>
                    <td style="padding-left: 70px">#1</td>
                    <td style="padding-left: 70px">#1</td>
                    <td style="padding-left: 70px">
                       <button class="btn  btn-sm custom-btn">
                          <i class="bi bi-pc-display-horizontal"></i>
                       </button>
                       <button class="btn  btn-sm custom-btn">
                           <i class="bi bi-currency-pound"></i>
                       </button>
                    </td>
                </tr>
            </div>
            </tbody>
        </table>
       
        <!-- Paginacion General -->
        <nav aria-label="Page navigation example" style="display: flex; justify-content: center;">
            <ul class="pagination">
                <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">5</a></li>
                <li class="page-item"><a class="page-link" href="#">6</a></li>
                <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
                </li>
            </ul>
        </nav>
    </section>
</main>
 <br><br><br>
   <!-- Pie de página (opcional) -->
    <footer>
        <p>© 2024 Hyperblog. Todos los derechos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>

        function init() {

            $(".otro").hide();
            $(".pruebas").hide();
            $("#a2").hide();
            $(".a3").hide();
            $(".a4").hide();
            $(".a5").hide();
            $(".a6").hide();
            $(".a7").hide();
            

        }

        function abrir(id, id2) {
            console.log(id2);

            $("."+id).show();
            $("#btnAbrir-" + id2).hide();
            $("#btnCerrar-" + id2).show();
        }


        function cerrar(id, id2) {
            $("."+id).hide();
            $("#btnAbrir-" + id2).show();
            $("#btnCerrar-" + id2).hide();
        }

        function cerrar_uno(id) {
            console.log(id);

            $(".hijos-" + id).remove(); // Remover todas las filas hijas
            $("#btnAbrir").show();
            $("#btnCerrar").hide();
        }

        init();


    </script>

    
    </body>
</html>
