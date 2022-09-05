<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\v1\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->guard = "api";
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth("api")->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth("api")->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth("api")->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 1
        ]);
    }
    public function joblist()
    {
        $user = auth("api")->user();

            $pass = "jghsdjfg626FFDS5266s";
            $pass1 = "jkdhjk54858DDS55";

        $respuesta = [['uid'=>"123456789", 'did'=>'999999', 'ssid'=>'wifi_123456789',
            'password'=>"00000000", 'nombre'=>'cliente 1', 'direccion'=>'direccion cliente 1', 'departamento'=>'meta',
            'municipio'=>'villavicencio', 'ubicacion'=>"", "latitud"=>"", "longitud"=>"",
            'celular'=>"", "fecha_lectura"=>"25/12/2021", "estado"=>"habilitado", "orden"=>"lectura", "pass"=>$pass, "macAddres"=>"CC:50:E3:95:F4:B6"],
            ['uid'=>"123456789", 'did'=>'888888', 'ssid'=>'wifi_00000',
                'password'=>"00000001", 'nombre'=>"cliente 2", 'direccion'=>"direccion cliente 2", 'departamento'=>"meta",
                'municipio'=>"villavicewncio", 'ubicacion'=>"", "latitud"=>"", "longitud"=>"",
                'celular'=>"", "fecha_lectura"=>"25/12/2021", "estado"=>"inhabilitado", "orden"=>"conexion", "pass"=>$pass1, "macAddres"=>"00:19:06:35:7F:27"],
            ['uid'=>123456789, 'did'=>"444444", 'ssid'=>'wifi_44444444',
                'password'=>"00000004", 'nombre'=>"SNEIDER FUENTES", 'direccion'=>'CRR 22 38-47', 'departamento'=>'META',
                'municipio'=>"VILLAVICENCIO", 'ubicacion'=>"VILLAVICENCIO", "latitud"=>"", "longitud"=>"",
                'celular'=>"3444444444", "fecha_lectura"=>"25/12/2021", "estado"=>"habilitado", "orden"=>"lectura", "pass"=>"kedjidjiosdjsio", "macAddres"=>"00:19:06:35:7F:27"],
            ['uid'=>123456789, 'did'=>"555555", 'ssid'=>'wifi_55555555',
                'password'=>"00000005", 'nombre'=>"ENERTEC PRUEBA", 'direccion'=>"LLANOCENTRO", 'departamento'=>"META",
                'municipio'=>"VILLAVICENCIO", 'ubicacion'=>"VILLAVICENCIO", "latitud"=>"", "longitud"=>"",
                'celular'=>"3959758995", "fecha_lectura"=>"25/12/2021", "estado"=>"habilitado", "orden"=>"corte", "pass"=>"lmnd848ojeoijef3", "macAddres"=>"00:19:06:35:7F:27"],

        ];

        return response()->json($respuesta);
    }
}
