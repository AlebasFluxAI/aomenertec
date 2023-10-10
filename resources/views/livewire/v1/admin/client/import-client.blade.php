<div>
    <section class="login">
        @section("header")
            {{--extended app.blade--}}
        @endsection
        @if (session()->has('success'))
            <div class="alert alert-succes">
                {{ session('success') }}
            </div>
        @endif
        @include("partials.v1.title",[
                "first_title"=>"Importador",
                "second_title"=>"Clientes"
            ])
        @include("partials.v1.table_nav",
   ["mt"=>2,
   "nav_options"=>[
              [
                  "permission"=>[\App\Http\Resources\V1\Permissions::CLIENT_CREATE],
                    "button_align"=>"right",
                    "click_action"=>"",
                    "button_content"=>"Importaciones",
                    "button_icon"=>"fa-solid fa-file-excel",
                    "target_route"=>"v1.admin.client.import-index.client",
              ],
           ]
  ])
        <div class="contenedor-grande ">
            <div
                class="row">                    @include("partials.v1.divider_title",["title"=>"Listado de campos que se deben incluir en el archivo de importacion"])

                <div class="col-md-6">

                    @include("partials.v1.table.primary-details-table",[
                           "table_info"=>[
                                                    [
                                                    "key"=>"NOMBRE",
                                                    "value"=>"NOMBRE DEL CLIENTE --- Enertec (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"APELLIDO",
                                                    "value"=>"APELLIDO DEL CLIENTE --- Latam (Requerido)"
                                                    ],
                                                     [
                                                    "key"=>"ALIAS",
                                                    "value"=>"NOMBRE ALTERNATIVO DE CLIENTE --- Enertec (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"CODIGO",
                                                    "value"=>"CODIGO DE CLIENTE --- 123456 (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"TELEFONO",
                                                    "value"=>"NUMERO TELEFONICO DEL CLIENTE --- 3209720222 (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"INDICATIVO_TELEFONO",
                                                    "value"=>"INDICATIVO DEL TELEFONO --- +57 (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"EMAIL",
                                                    "value"=>"CORREO ELECTRONICO DEL CLIENTE--- enerter@enerteclatam.com (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"TIPO_PERSONA",
                                                    "value"=>"TIPO DE PERSONA DE CLIENTE--- NATURAL/JURIDICA (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"TIPO_IDENTIFICACION",
                                                    "value"=>"TIPO IDENTIFICACION DE CLIENTE--- CC/CE/PEP/PP/NIT (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"IDENTIFICACION",
                                                    "value"=>"IDENTIFICACION DE CLIENTE --- 1225312566 (Requerido)"
                                                    ],
                                                       [
                                                    "key"=>"TIPO_PERSONA_FACTURACION",
                                                    "value"=>"TIPO DE PERSONA DE CLIENTE PARA FACTURACION--- NATURAL/JURIDICA (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"TIPO_IDENTIFICACION_FACTURACION",
                                                    "value"=>"TIPO IDENTIFICACION DE CLIENTE PARA FACTURACION--- CC/CE/PEP/PP/NIT (Requerido)"
                                                    ],

                                                    [
                                                    "key"=>"IDENTIFICACION_FACTURACION",
                                                    "value"=>"IDENTIFICACION DE CLIENTE PARA FACTURACION --- 1225312566 (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"TIPO_FACTURACION",
                                                    "value"=>"TIPO DE FACTURACION DE CLIENTE  --- PREPAGO/POSTPAGO (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"CLIENTE_CON_CONTRIBUCION",
                                                    "value"=>"CLIENTE CON CONTRIBUCION  --- SI/NO (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"CLIENTE_CON_IMPUESTO_ALUMBRADO",
                                                    "value"=>"CLIENTE CON IMPUESTO ALUMBRADO  --- SI/NO (Opcional)"
                                                    ],

                                          ]
                            ])
                </div>
                <div class="col-md-6">
                    @include("partials.v1.table.primary-details-table",[
                           "table_info"=>[

                                                    [
                                                    "key"=>"DIRECCION_FACTURACION",
                                                    "value"=>"DIRECCION DE FACTURACION DE CLIENTE  --- Carrera 5#16-17 (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"RAZON_SOCIAL",
                                                    "value"=>"RAZON SOCIAL DE FACTURACION DE CLIENTE  --- Enertec Latam (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"DIRECCION",
                                                    "value"=>"DIRECCION DEL CLIENTE  --- Carrera 5#16-17 (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"DETALLES_DIRECCION",
                                                    "value"=>"DETALLDES DE DIRECCION DEL CLIENTE  --- Cerca al parque (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"DIRECCION_LATITUD",
                                                    "value"=>"LATITUD DE LA DIRECCION DEL CLIENTE  --- 1.2353 (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"DIRECCION_LONGITUD",
                                                    "value"=>"LONGITUD DE LA DIRECCION DEL CLIENTE  --- 1.2353 (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"ESTRATO",
                                                    "value"=>"ESTRATO DEL CLIENTE  --- E1/E2/E3/E4/E5/E6/COM/IND (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"TIPO_CONEXION",
                                                    "value"=>"TIPO DE CONEXION DEL CLIENTE  --- ZNI_CONV/ZNI_FOTO/ZNI_RURAL/SIN_CONV/MON (Requerido)"
                                                    ],
                                                     [
                                                    "key"=>"TOPOLOGIA_RED",
                                                    "value"=>"TOPOLOGIA DE RED DEL CLIENTE  --- MONO/BI/TRI (Requerido)"
                                                    ],
                                                    [
                                                    "key"=>"TIENE_TELEMETRIA",
                                                    "value"=>"DEFINE SI EL CLIENTE TIENE O NO TELEMETRIA  --- SI/NO (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"TIPO_DE_SUBSIDIO",
                                                    "value"=>"TIPO DE SUBSIDIO PARA CLIENTE --- 173/130 (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"CREAR_SUPERVISOR",
                                                    "value"=>"CREAR SUPERVISOR ASOCIADO A CLIENTE  --- SI/NO (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"ASOCIAR_OPERADOR_DE_RED",
                                                    "value"=>"ASOCIAR OPERADOR DE RED  --- ID del operador de red (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"ASOCIAR_TECNICO",
                                                    "value"=>"ASOCIAR TECNICO  --- ID del tecnico - debe ser un tecnico asociado al operador de red (Opcional)"
                                                    ],
                                                    [
                                                    "key"=>"EQUIPOS_ASOCIADOS",
                                                    "value"=>"SERIALES DE LOS EQUIPOS QUE SE QUIEREN ASOCIAR AL CLIENTE  --- Seriales de equipos separados por slash  - B215/SE256/SE555"
                                                    ],
                                                    [
                                                    "key"=>"TIPO_EQUIPOS_ASOCIADOS",
                                                    "value"=>"TIPOS DE LOS EQUIPOS QUE SE QUIEREN ASOCIAR AL CLIENTE  --- ID de tipos de equipos separados por slash estos deben estar en el orden de los equipos asociados  - 12/167/190"
                                                    ],
                                          ]
                            ])
                </div>
            </div>

            <form wire:submit.prevent="import" id="formulario" class="needs-validation" role="form">
                @include("partials.v1.divider_title",["title"=>"Suba el archivo de importacion de clientes"])
                <div class="form-group mb-2 col-md-6 col-sm-6">
                    <div class="col-md-6">
                        <span style="color: green" class="fas fa-file-excel"></span>
                        <a style="color: #0a53be;font-weight: bold"
                           href="https://enertedevops.s3.us-east-2.amazonaws.com/images/PlantillaClientesFVcsv.csv">
                            Descargar
                            plantilla</a>
                    </div>
                </div>
                @include("partials.v1.form.form_input_file",[
                        "input_type"=>"file",
                        "input_model"=>"file",
                        "icon_class"=>"fas fa-file",
                        "placeholder"=>"Archivo para importar clientes",
                        "col_with"=>12,
                        "file_accepted"=>".csv",
                        "required"=>false,
                ])
                <br>
                <div wire:loading.class="loader">
                </div>
                <div wire:loading.remove class="text-right">
                    <button id="add" type="submit" class="mb-2 py-2 px-4">
                        <b>
                            Importar
                        </b>
                    </button>
                </div>
            </form>
        </div>


</div>






