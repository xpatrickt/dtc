<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Pregunta;
use App\Models\PreguntaEtiqueta;
use App\Models\PreguntaUsuario;
use App\Services\HelperServices;

class PreguntaController extends Controller
{
    //funciones generales de mantenimiento
    public function ver($id){
        $ver = Pregunta::select('id','user_id','titulo','cuerpo','me_gusta','no_gusta','denunciado','verificado','c_respuestas','tipo','created_at')
        ->where('id', $id)
        ->with([
            'etiquetas:id,nombre',
            'usuarios:id,nombres,apellidos,usuario',
            ]);
        return $ver->first();
    }

    public function verCompleto($tipo, $id){
        if($tipo == 1){
            HelperServices::contadorPuntaje(auth()->user()->id, 'abrir_pregunta');//puntaje
            $tipoRes = 3;
        }
        else{
            HelperServices::contadorPuntaje(auth()->user()->id, 'abrir_publicacion');//puntaje
            $tipoRes = 4;
        }
        $ver = Pregunta::select('id','user_id','titulo','cuerpo','me_gusta','no_gusta','denunciado','verificado','c_respuestas','tipo','created_at')
        ->where('id', $id)
        ->with(
            [   'respuestas' => function ($query) use ($tipoRes) {
                    $query->select('id','pregunta_id','user_id','titulo','cuerpo','me_gusta','no_gusta','denunciado','verificado','created_at')
                    ->where('activo', true)->where('tipo',$tipoRes);
                }
            ,'respuestas.usuario:id,nombres,apellidos,usuario,ocupacion_id,avatar'
            ,'respuestas.usuario.ocupacion:id,nombre'
            ,'respuestas.calificaciones_usuario:user_id,pregunta_id,tipo'
            ,'usuario:id,nombres,apellidos,usuario,ocupacion_id'
            ,'etiquetas:id,nombre,url'
            ,'usuario.ocupacion:id,nombre'
            ,'calificaciones_usuario:user_id,pregunta_id,tipo']);
        return $ver->first();
    }

    public function crearPregunta(Request $request){

        try {
            DB::beginTransaction();

            $nuevo = new Pregunta();
            $nuevo->user_id = auth()->user()->id;
            $nuevo->titulo = $request->titulo;
            $nuevo->cuerpo = $request->cuerpo;
            $nuevo->tipo = $request->tipo;
            $nuevo->save();

            $etiquetas = explode(',',$request->etiquetas);
            foreach ($etiquetas as $row) {
                $etiqueta = new PreguntaEtiqueta();
                $etiqueta->pregunta_id = $nuevo->id;
                $etiqueta->etiqueta_id = $row;
                $etiqueta->save();
            }
            if($request->usuarios){
                $usuarios = explode(',',$request->usuarios);
                foreach ($usuarios as $row) {
                    $usuario = new PreguntaUsuario();
                    $usuario->pregunta_id = $nuevo->id;
                    $usuario->user_id = $row;
                    $usuario->save();
                    $user = User::findOrFail($row);
                    $user->menciones += 1;
                    $user->save();
                }  
            }
            if ($request->tipo==1) {
                $msg = 'pregunta';
                HelperServices::contadorPuntaje(auth()->user()->id, 'crear_pregunta');//puntaje
                HelperServices::registrarContadorCampo('r_consultador');
                HelperServices::registrarContadorCampo('t_preguntas');
            }
            else{
                $msg = 'publicación';
                HelperServices::contadorPuntaje(auth()->user()->id, 'crear_publicacion');//puntaje
            }

            DB::commit();
            return response()->json(['message' => 'La '.$msg.' se creó correctamente', 'identificador' => $nuevo->id]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Tuvimos un problema inténtelo denuevo más tarde']);
        }

    }

    public function editarPregunta(Request $request, $id){

        try {
            DB::beginTransaction();

            $nuevo = Pregunta::findOrFail($id);
            $nuevo->titulo = $request->titulo;
            $nuevo->cuerpo = $request->cuerpo;
            $nuevo->tipo = $request->tipo;
            $nuevo->save();

            if($request->etiquetasAgregadas){
                $etiquetas = explode(',',$request->etiquetasAgregadas);
                foreach ($etiquetas as $row) {
                    $etiqueta = new PreguntaEtiqueta();
                    $etiqueta->pregunta_id = $nuevo->id;
                    $etiqueta->etiqueta_id = $row;
                    $etiqueta->save();
                }
            }
           
            if($request->usuariosAgregados){
                $usuarios = explode(',',$request->usuariosAgregados);
                foreach ($usuarios as $row) {
                    $usuario = new PreguntaUsuario();
                    $usuario->pregunta_id = $nuevo->id;
                    $usuario->user_id = $row;
                    $usuario->save();
                    //guardar el contador de menciones adicional
                    $user = User::findOrFail($row);
                    $user->menciones += 1;
                    $user->save();
                }  
            }

            if($request->etiquetasEliminadas){
                $etiquetas = explode(',',$request->etiquetasEliminadas);
                foreach ($etiquetas as $row) {
                    //solo deberiamos inactivar por ahora lo eliminados
                    $etiqueta = PreguntaEtiqueta::findOrFail($row);
                    //$etiqueta->activo = false;
                    $etiqueta->delete();
                }
            }
           
            if($request->usuariosEliminados){
                $usuarios = explode(',',$request->usuariosEliminados);
                foreach ($usuarios as $row) {
                    //solo deberiamos inactivar por ahora lo eliminados
                    $usuario = PreguntaUsuario::findOrFail($row);
                    //$usuario->activo = false;
                    $usuario->delete();
                }  
            }
         
            if ($request->tipo==1) {
                $msg = 'pregunta';
            }
            else{
                $msg = 'publicación';
            }

            DB::commit();
            return response()->json(['message' => 'La '.$msg.' se modificó correctamente', 'identificador' => $nuevo->id]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Tuvimos un problema inténtelo denuevo más tarde']);
        }

    }

    public function crearRespuesta(Request $request){

        try {
            DB::beginTransaction();

            $nuevo = new Pregunta();
            $nuevo->user_id = auth()->user()->id;
            $nuevo->pregunta_id = $request->pregunta_id;
            $nuevo->cuerpo = $request->cuerpo;
            $nuevo->tipo = $request->tipo;
            $nuevo->save();

            if($request->usuarios){
                $usuarios = explode(',',$request->usuarios);
                foreach ($usuarios as $row) {
                    $usuario = new PreguntaUsuario();
                    $usuario->pregunta_id = $nuevo->id;
                    $usuario->user_id = $row;
                    $usuario->save();
                    $user = User::findOrFail($row);
                    $user->menciones += 1;
                    $user->save();
                }  
                
            }

            if ($request->tipo==3) {
                $msg = 'La respuesta';
                HelperServices::contadorPuntaje(auth()->user()->id, 'crear_respuesta');//puntaje
                HelperServices::registrarContadorCampo('r_solucionador');
                HelperServices::registrarContadorCampo('t_respuestas');
                HelperServices::contadorRespuestas($request->pregunta_id);
                HelperServices::registrarContadorCampo('respuestas', $request->preguntaCreadorId);
                Pregunta::where('id', $request->pregunta_id)->update(['interaccion'=>true]);

                $hoy = new DateTime("now");
                $fCreacionPregunta = new DateTime($request->fCreacionPregunta);
                $diferencia  = $fCreacionPregunta->diff($hoy);
                if($diferencia->days < 1 ){
                    HelperServices::registrarContadorCampo('r_veloz');
                }
            }
            else{
                $msg = 'El comentario';
                HelperServices::contadorRespuestas($request->pregunta_id);
                HelperServices::registrarContadorCampo('respuestas', $request->preguntaCreadorId);
                HelperServices::contadorPuntaje(auth()->user()->id, 'crear_comentario');//puntaje
                Pregunta::where('id', $request->pregunta_id)->update(['interaccion'=>true]);
            }

            $nuevo->usuario;
            $nuevo->usuario->ocupacion;

            $respuesta = $nuevo;

            DB::commit();
            return response()->json(['message' => $msg.' se creó correctamente', 'identificador' => $nuevo->id,'respuesta' => $respuesta]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Tuvimos un problema inténtelo denuevo más tarde']);
        }

    }


    public function editrRespuesta(Request $request){

        try {
            DB::beginTransaction();

            $nuevo = Pregunta::findOrFail($id);
            $nuevo->cuerpo = $request->cuerpo;
            $nuevo->save();

            if($request->usuariosAgregados){
                $usuarios = explode(',',$request->usuariosAgregados);
                foreach ($usuarios as $row) {
                    $usuario = new PreguntaUsuario();
                    $usuario->pregunta_id = $nuevo->id;
                    $usuario->user_id = $row;
                    $usuario->save();
                    //guardar el contador de menciones adicional
                    $user = User::findOrFail($row);
                    $user->menciones += 1;
                    $user->save();
                }  
            }

            if($request->usuariosEliminados){
                $usuarios = explode(',',$request->usuariosEliminados);
                foreach ($usuarios as $row) {
                    //solo deberiamos inactivar por ahora lo eliminados
                    $usuario = PreguntaUsuario::findOrFail($row);
                    //$usuario->activo = false;
                    $usuario->delete();
                }  
            }
            
            if ($request->tipo==3) {
                $msg = 'La respuesta';
            }
            else{
                $msg = 'El comentario';
            }

            $nuevo->usuario;
            $nuevo->usuario->ocupacion;

            $respuesta = $nuevo;

            DB::commit();
            return response()->json(['message' => $msg.' se creó correctamente', 'identificador' => $nuevo->id,'respuesta' => $respuesta]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Tuvimos un problema inténtelo denuevo más tarde']);
        }

    }


    public function inactivar($id){
        $inactivado = Pregunta::findOrFail($id);
        $inactivado->activo = false;
        $inactivado->save();
        return response()->json(['message' => 'Se inactivó correctamente']);

    }

    public function activar($id){
        $activado = Pregunta::findOrFail($id);
        $activado->activo = true;
        $activado->save();
        return response()->json(['message' => 'Se activó correctamente']);

    }

    //personalizado

    public function listar(Request $request){
        $regPagina = 10;
        $lista = Pregunta::select('id','user_id','titulo','cuerpo','tipo','me_gusta','no_gusta','denunciado','verificado','interaccion','c_respuestas','activo','created_at');
        if($request->opcion != 'misMenciones') // en el caso de mis menciones las preguntas no son del mismo usuario
            $lista->where('user_id', auth()->user()->id);
        if($request->activo)
            $lista->where('activo', $request->activo);
        if($request->tipo)
            $lista->whereIn('tipo', explode(',',$request->tipo));
        if($request->etiqueta){
            $lista->whereHas('preguntas_etiquetas', function ($query) use ($request)  {
                $query->where('etiqueta_id',$request->etiqueta);
            });
        }
        //seccion de administrador de contenido
        if($request->opcion=='misLikes')
            $lista->whereHas('calificaciones', function ($query){
                $query->where('reciente',true);
            });
        elseif($request->opcion=='misMenciones'){
            $lista->with(['preguntas_usuarios:id,pregunta_id,user_id,visto']);
            $lista->with(['respuestas:id,user_id,pregunta_id']);
            $lista->with(['respuestas.preguntas_usuarios:id,pregunta_id,user_id,visto']);
            $lista->where(function ($query) {
                $query->whereHas('preguntas_usuarios', function ($q){
                    $q->where('user_id',auth()->user()->id);
                });
                $query->orWhereHas('respuestas.preguntas_usuarios', function ($q){
                    $q->where('user_id',auth()->user()->id);
                });
                
            });
            HelperServices::reiniciarContadorCampo('menciones'); 
        }



        //fin administrador
        if($request->orden && $request->ordenPor)
            $lista->orderBy($request->ordenPor, $request->orden);

        if($request->regPagina)//cambiaremos esto en el caso usar table server
            $regPagina = $request->regPagina;

        $data = $lista->get();

        if($request->opcion=='todos' ){
            HelperServices::reiniciarContadorCampo('me_gustas');
            HelperServices::reiniciarContadorCampo('no_gustas');
            HelperServices::reiniciarContadorCampo('denuncias');
            HelperServices::reiniciarContadorCampo('respuestas');
            Pregunta::where('user_id', auth()->user()->id)->update(['interaccion'=>false]);
        }
        elseif($request->opcion=='misMenciones'){
           PreguntaUsuario::where('user_id', auth()->user()->id)->update(['visto'=>true]);
        }
        return $data;
    }

    public function listarBasico(Request $request){

        $lista = Pregunta::select('id','user_id','titulo','cuerpo','tipo','me_gusta','no_gusta','denunciado','verificado','activo','c_respuestas','created_at')
        ->with([
            'usuario:id,nombres,apellidos,usuario,ocupacion_id',
            'etiquetas:id,nombre,url',
            'usuario.ocupacion:id,nombre',
            'calificaciones_usuario:user_id,pregunta_id,tipo'
        ])->where('activo', true);
        if($request->tipo)
            $lista->whereIn('tipo', explode(',', $request->tipo));
        if($request->moderar)
            $lista->where('denunciado','>',0);
        if($request->etiqueta){
            $lista->whereHas('preguntas_etiquetas', function ($query) use ($request)  {
                $query->where('etiqueta_id',$request->etiqueta);
            });
        }
        if($request->listaEtiquetas){
            $lista->whereHas('preguntas_etiquetas', function ($query) use ($request)  {
                $query->whereIn('etiqueta_id',explode(',', $request->listaEtiquetas));
            });
        }
        $lista->orderBy('id', 'desc');
        return $lista->get();
    }

    public function listarSugerencias(Request $request){
        $user_id = auth()->user()->id;
        $q = $request->texto;
        $tipo = $request->tipo;
        $etiquetas = $request->etiquetas;
        $query = "
        select DISTINCT *
        from (
            select t1.pregunta_id, t1.titulo
            from (
                select p.id as pregunta_id, p.titulo, PE.id as etiqueta_id from t_preguntas P
                left join t_pregunta_etiqueta PE ON P.id = PE.pregunta_id
                where P.titulo ilike '%".$q."%' and p.tipo = '".$tipo."' ".($etiquetas ? "and PE.id in (".$etiquetas.")" : "")." ) T1
            LEFT JOIN (
                select u.id, ui.id as etiqueta_id from users U
                LEFT join t_usuario_intereS UI on u.id = UI.user_id
                where u.id = ".$user_id.") T2 on T1.etiqueta_id = T2.etiqueta_id
            order by t2.etiqueta_id asc) f1
        limit 15;
        ";
        $lista = DB::select($query);
        return $lista;
    }

    public function listarBusquedaAvanzada(Request $request){
        $q = $request->texto;
        $etiquetas = DB::select("select id, nombre as titulo, url from t_etiquetas 	where nombre ilike '%".$q."%' order by id desc limit 5;");
        $preguntas = DB::select("select id, titulo from t_preguntas where titulo ilike '%".$q."%' and tipo = 1 order by id desc limit 5;");
        $publicaciones = DB::select("select id, titulo from t_preguntas where titulo ilike '%".$q."%' and tipo = 2 order by id desc limit 5;");
        $usuarios = DB::select("select id, nombres, apellidos from users where es_admin = false and activo = true and (nombres ilike '%".$q."%' or apellidos ilike '%".$q."%' or email ilike '%".$q."%') limit 5;");

        return compact('etiquetas', 'preguntas', 'publicaciones','usuarios');
    }

    public function listarPreguntasCalificar(Request $request){
        $user_id = auth()->user()->id;
        $q = $request->texto;
        $lista = DB::select("
        select DISTINCT *
        from (
            select t1.pregunta_id, t1.titulo
            from (
                select p.id as pregunta_id, p.titulo, e.id as etiqueta_id from t_preguntas P
                left join t_pregunta_etiqueta  PE ON P.id = PE.pregunta_id
                left join t_etiquetas E on E.id = PE.etiqueta_id
                where P.titulo ilike '%".$q."%' or E.nombre ilike'%".$q."%') T1
            LEFT JOIN (
                select u.id, ui.id as etiqueta_id from users U
                LEFT join t_usuario_intereS UI on u.id = UI.user_id
                where u.id = ".$user_id.") T2 on T1.etiqueta_id = T2.etiqueta_id
            order by t2.etiqueta_id asc) f1
        limit 15;
        ");
        return $lista;
    }

    public function listarCalificarTop(Request $request){

        $lista = DB::select("
        select *
        from t_preguntas
        where tipo = 1
        order by random()
        limit 5;
        ");
        return $lista;
    }

    public function listarModerarTop(Request $request){

        $lista = DB::select("
        select*
        from t_preguntas
        where denunciado > 0 and tipo = 1
        order by denunciado desc
        limit 5;
        ");
        return $lista;
    }

    public function listarPreguntasMencionadas(Request $request){

    }

    public function listarAdmin(Request $request){
        //return $request->all();
        $lista = Pregunta::select('id','pregunta_id','user_id','titulo','cuerpo','tipo','me_gusta','no_gusta','denunciado','verificado','activo','c_respuestas','created_at')
        ->with([
            'usuario:id,nombres,apellidos,usuario,ocupacion_id',
            'pregunta:id,pregunta_id,titulo',
            'respuestas:id,pregunta_id,titulo',
        ]);
        if($request->tipo)
            $lista->whereIn('tipo', explode(',', $request->tipo));
        if($request->moderar)
            $lista->where('denunciado','>',0);
        if($request->fechaIni)
            $lista->where('created_at','>',$request->fechaIni);
        if($request->fechaFin)
            $lista->where('created_at','<',$request->fechaFin);   
        if($request->contenidoPeligroso)
            $lista->where('es_peligroso', $request->contenidoPeligroso );
        $lista->orderBy('id', 'desc');
        return $lista->get();
    }

    

}
