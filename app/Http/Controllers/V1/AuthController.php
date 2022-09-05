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
        $client = App\Cliente::find(114);
        $client2 = App\Cliente::find(115);
        if ($client->tipo_ubicacion == 1 || $client->tipo_ubicacion == 2){
            $ubicacion = $client->centro_poblado->name;
        }elseif ($client->tipo_ubicacion == 3){
            $ubicacion = $client->vereda->name;
        }else{
            $ubicacion = $client->resguardo->name;
        }
        if ($client2->tipo_ubicacion == 1 || $client2->tipo_ubicacion == 2){
            $ubicacion1 = $client2->centro_poblado->name;
        }elseif ($client2->tipo_ubicacion == 3){
            $ubicacion1 = $client2->vereda->name;
        }else{
            $ubicacion1 = $client2->resguardo->name;
        }
        if (auth("api")->user()->hasRole('sponsor')){
            $pass = "jghsdjfg626FFDS5266s";
            $pass1 = "jkdhjk54858DDS55";
        }else{
            $pass = "6456dsiksdhhjSDSDA";
            $pass1 = "ghdjsds55dDJSIOd5";
        }
        $respuesta = [['uid'=>"123456789", 'did'=>'999999', 'ssid'=>'wifi_123456789',
            'password'=>"00000000", 'nombre'=>$client->name, 'direccion'=>$client->direccion, 'departamento'=>$client->departamento->departamento,
            'municipio'=>$client->municipio->municipio, 'ubicacion'=>$ubicacion, "latitud"=>$client->latitud, "longitud"=>$client->longitud,
            'celular'=>$client->celular, "fecha_lectura"=>"25/12/2021", "estado"=>"habilitado", "orden"=>"lectura", "pass"=>$pass, "macAddres"=>"CC:50:E3:95:F4:B6"],
            ['uid'=>$client2->user->identificacion, 'did'=>'888888', 'ssid'=>'wifi_'.$client2->identificacion,
                'password'=>"00000001", 'nombre'=>$client2->name, 'direccion'=>$client2->direccion, 'departamento'=>$client2->departamento->departamento,
                'municipio'=>$client2->municipio->municipio, 'ubicacion'=>$ubicacion1, "latitud"=>$client2->latitud, "longitud"=>$client2->longitud,
                'celular'=>$client2->celular, "fecha_lectura"=>"25/12/2021", "estado"=>"inhabilitado", "orden"=>"conexion", "pass"=>$pass1, "macAddres"=>"00:19:06:35:7F:27"],
            ['uid'=>$client2->user->identificacion, 'did'=>"444444", 'ssid'=>'wifi_44444444',
                'password'=>"00000004", 'nombre'=>"SNEIDER FUENTES", 'direccion'=>'CRR 22 38-47', 'departamento'=>'META',
                'municipio'=>"VILLAVICENCIO", 'ubicacion'=>"VILLAVICENCIO", "latitud"=>"", "longitud"=>"",
                'celular'=>"3444444444", "fecha_lectura"=>"25/12/2021", "estado"=>"habilitado", "orden"=>"lectura", "pass"=>"kedjidjiosdjsio", "macAddres"=>"00:19:06:35:7F:27"],
            ['uid'=>$client2->user->identificacion, 'did'=>"555555", 'ssid'=>'wifi_55555555',
                'password'=>"00000005", 'nombre'=>"ENERTEC PRUEBA", 'direccion'=>"LLANOCENTRO", 'departamento'=>"META",
                'municipio'=>"VILLAVICENCIO", 'ubicacion'=>"VILLAVICENCIO", "latitud"=>"", "longitud"=>"",
                'celular'=>"3959758995", "fecha_lectura"=>"25/12/2021", "estado"=>"habilitado", "orden"=>"corte", "pass"=>"lmnd848ojeoijef3", "macAddres"=>"00:19:06:35:7F:27"],

        ];

        return response()->json($respuesta);
    }
}
