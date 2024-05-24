<?php
namespace App\Services;

use \Datetime;
use App\Models\Pregunta;
use App\Models\Etiqueta;
use App\Models\User;
use App\Models\Ranking;

class HelperServices
{
    public function __construct()
    {
        //--
    }

    public static function consumirWebService($ruta, $token, $post = 0, $json = null){

        if($post==0){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ruta);
            curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
                'Authorization: ' . $token,
                )
            );
            curl_setopt($ch, CURLOPT_POST, $post);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $respuesta  = curl_exec($ch);
            curl_close($ch);
        }
        else{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ruta);
            curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: ' . $token,
                )
            );
            curl_setopt($ch, CURLOPT_POST, $post);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $respuesta  = curl_exec($ch);

            curl_close($ch);
        }


        return $respuesta;

    }



    public static function prepararEtiquetasJson($etiqueta){

        $nivel1 = Etiqueta::select(['id','padre_id','nombre','url','nivel'])
        ->where('activo',1)->where('url',$etiqueta)->get();
        $nivel2 = Etiqueta::select(['id','padre_id','nombre','url','nivel'])
        ->where('activo',1)->where('padre_id',$nivel1[0]->id)->orderBy('id','desc')->get();
        $idsNivel2=[];

        print_r($nivel1);
        print_r($nivel2);
        foreach ($nivel2 as $row) {
            array_push($idsNivel2,$row->id);
        }

        $nivel3 = Etiqueta::select(['id','padre_id','nombre','url','nivel'])
        ->where('activo',1)->whereIn('padre_id',$idsNivel2)->orderBy('id','desc')->get();

        $array = array_merge($nivel2,$nivel3);
        return self::prepararEtiquetas($array,null,0);
    }

    public static  function prepararEtiquetas($etiquetas,$padre_id=null,$nivel=0){
        $array =[];
        foreach($etiquetas as $etiqueta){
            if($etiqueta->padre_id != $padre_id) continue;

            $hijos =[];
            if($etiqueta->hijos->count() > 0){
                $hijos = self::prepararEtiquetas($etiquetas,$etiqueta->id,$nivel+1) ;
            }

            $etiqueta_array = array(
                'id'=>$etiqueta->id,
                'nivel'=>$nivel,
                'titulo'=>$etiqueta->titulo,
                'icono'=>$etiqueta->icono,
                'link'=>$etiqueta->link,
                'orden'=>$etiqueta->orden,
                'hijos'=>$hijos,
            );
            array_push($array,$etiqueta_array);
        }
        return $array;

    }

    public static  function calcularTotales(){

        $rubros = Etiqueta::select('id','')->where('nivel',1)->get();
        foreach ($rubros as $key => $value) {
            # code...
        }
        return '';

    }

    public static  function aumentarMeGusta($id, $userID){
        $pregunta = Pregunta::findOrFail($id);
        $pregunta->me_gusta += 1;
        $pregunta->interaccion = true;
        $pregunta->save();
        self::registrarContadorCampo('me_gustas', $userID);
        if($pregunta->tipo == 1){
            HelperServices::contadorPuntaje(auth()->user()->id, 'me_gusta_pregunta');//puntaje
        }
        if($pregunta->tipo == 2){
            HelperServices::contadorPuntaje(auth()->user()->id, 'me_gusta_publicacion');//puntaje
        }
        if($pregunta->tipo == 3){
            HelperServices::contadorPuntaje(auth()->user()->id, 'me_gusta_respuesta');//puntaje
            HelperServices::contadorPuntaje( $pregunta->user_id, 'recibe_gusta_respuesta');//puntaje
        }
        else{
            HelperServices::contadorPuntaje(auth()->user()->id, 'me_gusta_comentario');//puntaje
            HelperServices::contadorPuntaje( $pregunta->user_id, 'recibe_gusta_comentario');//puntaje
        }
    }

    public static  function aumentarNoMeGusta($id, $userID){
        $pregunta = Pregunta::findOrFail($id);
        $pregunta->no_gusta += 1;
        $pregunta->interaccion = true;
        $pregunta->save();
        self::registrarContadorCampo('no_gustas', $userID);
        HelperServices::contadorPuntaje( $pregunta->user_id, 'no_gusta');//puntaje
    }

    public static  function quitarMeGusta($id, $userID){
        $pregunta = Pregunta::findOrFail($id);
        $pregunta->me_gusta -= 1;
        $pregunta->save();
        //self::registrarContadorCampo('me_gustas', $userID);
    }

    public static  function quitarNoMeGusta($id, $userID){
        $pregunta = Pregunta::findOrFail($id);
        $pregunta->no_gusta -= 1;
        $pregunta->save();
        //self::registrarContadorCampo('no_gustas', $userID);
    }

    public static function aumentarDenunciado($id, $userID){
        $pregunta = Pregunta::findOrFail($id);
        $pregunta->denunciado += 1;
        $pregunta->interaccion = true;
        $pregunta->save();
        self::registrarContadorCampo('denuncias', $userID);
        HelperServices::contadorPuntaje(auth()->user()->id, 'denunciar');//puntaje
        self::registrarContadorCampo('r_conflictivo', $pregunta->user_id);//puntaje
    }

    public static function quitarDenunciado($id, $userID){
        $pregunta = Pregunta::findOrFail($id);
        $pregunta->denunciado -= 1;
        $pregunta->save();
        //self::registrarContadorCampo('denuncias', $userID);
    }

    public static function contadorRespuestas($id){
        $pregunta = Pregunta::findOrFail($id);
        $pregunta->c_respuestas += 1;
        $pregunta->save();
    }

    public static function registrarContadorCampo($columna, $userId = NULL){
        if($userId == null)
            $user = auth()->user();
        else
            $user = User::findOrFail($userId);
        $user[$columna] += 1;
        $user->save();

    }

    public static function reiniciarContadorCampo($columna){
        $user = auth()->user();
        $user[$columna] = 0;
        $user->save();
    }

    public static function contadorPuntaje($userId, $columna){
        $ranking = Ranking::where('user_id', $userId)->first();
        $ranking[$columna] += 1;
        $ranking->save();
    }

    public static function registrarVisita($userActual, $userId){
        if($userActual != $userId ){
            self::registrarContadorCampo('c_visitas', $userId );
            $user = User::findOrFail($userId);
            $user->c_visitas +=1;
            $user->save();
        }
    }

    public static function registrarUltimoAcceso(){
        $user = auth()->user();
        $user->ult_visita = date("Y-m-d H:i:s");
        $user->save();
    }

    //Puntaje final
    public static function obtenerPuntajeFinalUsuario($ranking){

        $user = User::where('id', $ranking->user_id)->first();
        $peso = [
            'login_linkedin' => 0.01,
            'abrir_publicacion' => 0.02,
            'abrir_pregunta' => 0.03,
            'buscar_usuarios' => 0.01,
            'buscar_publicaciones' => 0.02,
            'buscar_preguntas' => 0.02,
            'buscar_respuestas' => 0.02,
            'aumentar_seguidor' => 0.05,
            'buscar_etiquetas' => 0.01,
            'abrir_etiquetas' => 0.02,
            'crear_publicacion' => 0.26,
            'crear_pregunta' => 0.18,
            'crear_respuesta' => 0.28,
            'crear_comentario' => 0.14,
            'mencion_util' => 0.14,
            'testimonio' => 0.06,
            'me_gusta_publicacion' => 0.04,
            'me_gusta_pregunta' => 0.04, 
            'me_gusta_comentario' => 0.02,
            'me_gusta_respuesta' => 0.02,
            'recibe_gusta_publicacion' => 0.00, //Falta
            'recibe_gusta_pregunta' => 0.00, //Falta
            'recibe_gusta_comentario' => 0.06,
            'recibe_gusta_respuesta' => 0.08,
            'respuesta_mejor_valorada' => 0.25,
            'comentario_mejor_valorado' => 0.25,
            'testimonio_positivo' => 0.06,
            'denunciar' => 0.06,
            'moderar' => 0.06,
            'ayudar_solucion_existente' => 0.12,
            'enviar_inbox' => 0.02,
            'editar_publicacion' => 0.12,
            'reporte_confirmado' => -0.3,
            'no_gusta' => -0.16,
            'inactividad' => -0.30,
            'testimonio_negativo' => -0.30,
        ];

        $puntaje = 0 ;
        $puntaje += ($ranking->login_linkedin * $peso['login_linkedin']);
        $puntaje += ($ranking->abrir_publicacion * $peso['abrir_publicacion']);
        $puntaje += ($ranking->abrir_pregunta * $peso['abrir_pregunta']);
        $puntaje += ($ranking->buscar_usuarios * $peso['buscar_usuarios']);
        $puntaje += ($ranking->buscar_publicaciones * $peso['buscar_publicaciones']);
        $puntaje += ($ranking->buscar_preguntas * $peso['buscar_preguntas']);
        $puntaje += ($ranking->buscar_respuestas * $peso['buscar_respuestas']);
        $puntaje += ($ranking->aumentar_seguidor * $peso['aumentar_seguidor']);
        $puntaje += ($ranking->buscar_etiquetas * $peso['buscar_etiquetas']);
        $puntaje += ($ranking->abrir_etiquetas * $peso['abrir_etiquetas']);
        $puntaje += ($ranking->crear_publicacion * $peso['crear_publicacion']);
        $puntaje += ($ranking->crear_pregunta * $peso['crear_pregunta']);
        $puntaje += ($ranking->crear_respuesta * $peso['crear_respuesta']);
        $puntaje += ($ranking->crear_comentario * $peso['crear_comentario']);
        $puntaje += ($ranking->mencion_util * $peso['mencion_util']);
        $puntaje += ($ranking->testimonio * $peso['testimonio']);
        $puntaje += ($ranking->me_gusta_publicacion * $peso['me_gusta_publicacion']);
        $puntaje += ($ranking->me_gusta_pregunta * $peso['me_gusta_pregunta']);
        $puntaje += ($ranking->me_gusta_comentario * $peso['me_gusta_comentario']);
        $puntaje += ($ranking->me_gusta_respuesta * $peso['me_gusta_respuesta']);
        $puntaje += ($ranking->recibe_gusta_publicacion * $peso['recibe_gusta_publicacion']);
        $puntaje += ($ranking->recibe_gusta_pregunta * $peso['recibe_gusta_pregunta']);
        $puntaje += ($ranking->recibe_gusta_comentario * $peso['recibe_gusta_comentario']);
        $puntaje += ($ranking->recibe_gusta_respuesta * $peso['recibe_gusta_respuesta']);
        $puntaje += ($ranking->respuesta_mejor_valorada * $peso['respuesta_mejor_valorada']);
        $puntaje += ($ranking->comentario_mejor_valorado * $peso['comentario_mejor_valorado']);
        $puntaje += ($ranking->testimonio_positivo * $peso['testimonio_positivo']);
        $puntaje += ($ranking->denunciar * $peso['denunciar']);
        $puntaje += ($ranking->moderar * $peso['moderar']);
        $puntaje += ($ranking->ayudar_solucion_existente * $peso['ayudar_solucion_existente']);
        $puntaje += ($ranking->enviar_inbox * $peso['enviar_inbox']);
        $puntaje += ($ranking->editar_publicacion * $peso['editar_publicacion']);
        $puntaje += ($ranking->reporte_confirmado * $peso['reporte_confirmado']);
        $puntaje += ($ranking->no_gusta * $peso['no_gusta']);
        $puntaje += ($ranking->inactividad * $peso['inactividad']);
        $puntaje += ($ranking->testimonio_negativo * $peso['testimonio_negativo']);


        $datetime1 = new DateTime();
        $datetime2 = new DateTime($user->created_at);
        $interval = $datetime2->diff($datetime1);
        $semanas = floor(($interval->format('%a') / 7));

        $P = $ranking->crear_pregunta;
        $S = $ranking->crear_respuesta;

        $PU = $ranking->recibe_gusta_pregunta;
        $SU = $ranking->recibe_gusta_respuesta;

        $RT = $puntaje;
        $RS = $puntaje / ($semanas == 0 ? 1 : $semanas); // if zero

        $CTT = ($P + $S) == 0 ? 1 : ($P + $S);
        $CIT = ($PU + $SU) ==0 ? 1 : ($PU + $SU);
        $SMV = ($ranking->respuesta_mejor_valorada == 0 ? 1 : $ranking->respuesta_mejor_valorada);
        $S = $S == 0 ? 1 : $S; 


        $RF = $RS * (1 + ($CIT / $CTT)) * (1 + ($SMV / $S));

        $ranking->update(['total'=>$RF]);

        return true;
    }
}
