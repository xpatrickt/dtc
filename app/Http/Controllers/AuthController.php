<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HelperServices;

use App\Models\User;
use App\Models\Ranking;

class AuthController extends Controller
{

    public function loginLinkedin(Request $request){
        $url = 'https://www.linkedin.com/oauth/v2/accessToken';
        $json = array(
            'grant_type' => 'authorization_code',
            'code' => $request->code,
            'redirect_uri' => config('services.linkedin.redirect'),
            'client_id' => config('services.linkedin.client_id'),
            'client_secret' => config('services.linkedin.client_secret'),
        );
        //return $json;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded' ,
            )
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($json));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);
        curl_close($ch);
        $resObject = json_decode($respuesta);

        $access_token = $resObject->access_token;
        $expires_in = $resObject->expires_in;

        /* $access_token = 'AQVbHKODC5dMFYbclcxWGwfJfYb01oi-paPe4_vgZz3FJSJ5SYvW3KARhOcHkBqTnShj0L6vT4UEce7mtUrgpueiEPf1c8j7UtV4WIS3-1fAfHZtFFtdKh7VXBwwZEAm4RDVQwwnv55BC6kv92doILbUliQ1EpmihBmHxWz2zEJGvwYWFdiXYrPFD6nABpGe5DSTVluDbbrkFAMUco3fcXrwIigeeHoAy4btA_t1ZfYYauWmg40CAO9viyuSjMSzIudqtr9FYVC0Cdc-hR0iNT0RoZKKSp1wWWJ1C818Qr8XxNsEtk7JP6GDUWapgygu1nKbQU-1XKMSZfr4Y2cdeKCCbxDR3w';
        $expires_in = 100; */
        $urlProfile = 'https://api.linkedin.com/v2/me';
        $urlEmail = 'https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))';

        $usarioRes = json_decode(HelperServices::consumirWebService($urlProfile,'Bearer ' . $access_token, false));

        $user = User::where('provider_id', $usarioRes->id)->first();
        $nuevoUsuario = false;
        if(!$user){
            $emailRes = json_decode(HelperServices::consumirWebService($urlEmail,'Bearer ' . $access_token, false), true);
            $user = User::create([
                'apellidos' => $usarioRes->localizedLastName,
                'nombres' => $usarioRes->localizedFirstName,
                'provider' => 'linkedin',
                'provider_id' => $usarioRes->id,
                'provider_token' => $access_token,
                'provider_expires_in' => $expires_in,
                'password' => null,
                'email' => $emailRes['elements'][0]['handle~']['emailAddress'],
                'ult_visita' => date("Y-m-d H:i:s"),
            ]);
            Ranking::create(['user_id' => $user->id ]);//inicializa la tabla
            $nuevoUsuario = true;
            HelperServices::contadorPuntaje($user->id, 'login_linkedin');//puntaje
        }

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json(['nuevo'=> $nuevoUsuario , 'token_laravel' => $token, 'token_linkendin' => $access_token, 'message'=>'Inicio correcto'], 200);
    }

    public function check()
    {
        try {
            HelperServices::registrarUltimoAcceso();
            return response(['Unauthenticated' => false]);
        } catch (Exception $e) {
            return response(['Unauthenticated' => true]);
        }
        return response(['Unauthenticated' => true]);
    }

    public function logout()
    {
        if (auth()->user()->token()->revoke()) {
            return response()->json(['message'=>'Cierre de sesión correcto'], 200);
        } else {
            return response()->json(['message' => 'Error al intentar cerrar sesión'], 401);
        }
    }

    public function getPerfilUsuario(){

        $user = auth()->user();
        if (!$user)
            return response()->json(['message' => 'No existe el usuario'], 401);
        $user->persona;
        return $user;
    }

    public function loginAdmin(Request $request){
        $data = [
            'email' => $request->usuario,
            'password' => $request->password
        ];
 
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['message'=>'Bienvenido', 'token_laravel' => $token], 200);
        } else {
            return response()->json(['message' => 'Usuario y/o contraseña incorrectos'], 401);
        }
    }

    public function loginInvitado(Request $request){
        $data = [
            'email' => $request->usuario,
            'password' => $request->password,
        ];
        if(!$request->invitado == true || !$request->clave == config('app.invitado_key'))
            return response()->json(['message' => 'ERROR'], 401);

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token_laravel' => $token], 200);
        } else {
            return response()->json(['message' => 'Usuario y/o contraseña incorrectos'], 401);
        }
    }
}
