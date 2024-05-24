<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Seguidor;
use App\Models\UsuarioExperiencia;
use App\Models\UsuarioInteres;
use App\Models\Ranking;

use App\Services\HelperServices;

use Validator;

class UsuarioController extends Controller
{
    protected $ruta_avatar = 'user/users/';
    protected $ruta_qr = 'user/users/qr/';

    public function registrarPreferencia(Request $request){
        try {
            DB::beginTransaction();

            $usuario = auth()->user();
            $usuario->usuario = $request->usuario;
            $usuario->ocupacion_id = $request->ocupacion_id;
            $usuario->suscrito = $request->suscribir;
            $usuario->puesto_actual = $request->puesto_actual;
            $usuario->save();

            $experiencia = explode(',',$request->experiencia);
            foreach ($experiencia as $row) {
                $exp = new UsuarioExperiencia();
                $exp->user_id = auth()->user()->id;
                $exp->etiqueta_id = $row;
                $exp->save();
            }

            $interes = explode(',',$request->interes);
            foreach ($interes as $row) {
                $int = new UsuarioInteres();
                $int->user_id = auth()->user()->id;
                $int->etiqueta_id = $row;
                $int->save();
            }

            Ranking::firstOrCreate(['user_id' => auth()->user()->id]);//inicializa la tabla

            DB::commit();
            return response()->json(['message' => 'Se registró las preferencias del usuario.']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Tuvimos un problema inténtelo denuevo más tarde']);
        }

    }

    public function actualizarAvatar(Request $request){
        $validation = Validator::make($request->all(), [
            'avatar' => 'required|image'
        ]);

        if ($validation->fails())
            return response()->json(['message' => $validation->messages()->first()],422);

        $usuario = auth()->user();

        if($usuario->avatar && \File::exists($this->ruta_avatar.$usuario->avatar))
            \File::delete($this->ruta_avatar.$usuario->avatar);

        $extension = $request->file('avatar')->getClientOriginalExtension();
        $filename = uniqid();
        $file = $request->file('avatar')->move($this->ruta_avatar, $filename.".".$extension);
        $usuario->avatar = $filename.".".$extension;
        $usuario->save();

        return response()->json(['message' => 'Se actualizó el avatar.','avatar' => $usuario->avatar]);
    }

    public function actualizarQR(Request $request){
        $validation = Validator::make($request->all(), [
            'qr' => 'required|image',
            'pago' => 'required'
        ]);

        if ($validation->fails())
            return response()->json(['message' => $validation->messages()->first()],422);

        $usuario = auth()->user();

        if($usuario->qr && \File::exists($this->ruta_qr.$usuario->qr))
            \File::delete($this->ruta_qr.$usuario->qr);

        $extension = $request->file('qr')->getClientOriginalExtension();
        $filename = uniqid();
        $file = $request->file('qr')->move($this->ruta_qr, $filename.".".$extension);
        $img = \Image::make($this->ruta_qr.$filename.".".$extension);
        $img->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($this->ruta_qr.$filename.".".$extension);
        $usuario->qr = $filename.".".$extension;
        $usuario->pago = $request->pago;
        $usuario->save();

        return response()->json(['message' => 'Se actualizó el qr.','qr' => $usuario->qr]);
    }

    public function actualizarMsgAyuda(Request $request){
        $usuario = auth()->user();
        if($request->msg_inicio)
            $usuario->msg_inicio = true;
        if($request->msg_pregunta)
            $usuario->msg_pregunta = true;
        if($request->msg_publicacion)
            $usuario->msg_publicacion = true;
        $usuario->save();
        return response()->json(['message' => 'Se actualizó correctamente']);
    }

    public function ver($id){
        HelperServices::registrarVisita(auth()->user()->id, $id);//puntaje
        $usuario = User::findOrFail($id);
        $usuario->ocupacion;
        $usuario->intereses_et;
        $usuario->experiencias_et;
        $usuario->ranking;
        $cantRespuestasLike = DB::select("select count(1) as cant from t_preguntas where user_id=".$id." and tipo=3 and activo=true and me_gusta > 0;")[0];
        $cantRespuestasMejor = DB::select("select count(1) as cant from t_preguntas where user_id=".$id." and tipo=3 and activo=true and mejor_respuesta = true;")[0];
        return compact('usuario', 'cantRespuestasLike', 'cantRespuestasMejor');
    }

    public function llenarCombo(Request $request){
        $lista = User::select('id', 'nombres', 'apellidos', 'usuario', 'puesto_actual');
        if($request->filtro){
            $lista->where(function($query) use ($request) {
                $query->where('nombres','ilike','%'.$request->filtro.'%')
                      ->orWhere('apellidos','ilike','%'.$request->filtro.'%')
                      ->orWhere('email','ilike','%'.$request->filtro.'%');
            });
        }
        return $lista->limit(50)->get();
    }

    public function seguirUsuario(Request $request){
        $seguir = new Seguidor;
        $seguir->user_id = auth()->user()->id;
        $seguir->user_id_seguir = $request->user_id_seguir;
        $seguir->save();
        return response()->json(['message' => 'Estas siguiendo al usuario.']);
    }

    public function listar(Request $request){
        $lista = User::select('id','email','ult_visita', 'es_admin', 'tipo_usuario', 'activo', 'created_at','id_persona')->with(['persona:id,num_docid,nombres,apellido_pat,apellido_mat']);
        // $lista = User::select('id', 'ocupacion_id', 'usuario', 'nombres', 'apellidos', 'usuario', 'email', 'puesto_actual', 'ult_visita', 'es_admin', 'tipo_usuario', 'ult_visita', 'activo', 'created_at');
        if($request->tipo){
            if($request->tipo == 9)
                $lista->where('es_admin', false);
            else
                $lista->where('tipo_usuario', $request->tipo);
        }
        // if($request->ocupacion)
        //     $lista->whereIn('ocupacion_id', explode(",", $request->ocupacion));
        if($request->fechaIni && $request->fechaFin)
            $lista->whereBetween('created_at', [$request->fechaIni, $request->fechaFin]);
        return $lista->get();
    }

    public function crear(Request $request){
        $request->merge(['password' => Hash::make($request->password)]);
        $nuevo = new User($request->all());
        $nuevo->nombre = $request->nombres;
        $nuevo->id_persona = $request->id_per;
        $nuevo->save();
        return response()->json(['message' => 'El usuario se creó correctamente', 'identificador' => $nuevo->id]);
    }

    public function modificar(Request $request, $id){
        if($request->editarPassword){
            $request->merge(['password' => Hash::make($request->password)]);
        }  
        $editado = User::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'El usuario se actualizó correctamente']);
    }

    public function inactivar($id){
        $inactivado = User::findOrFail($id);
        $inactivado->delete();
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = User::findOrFail($id);
        $activado->activo = true;
        $activado->save();
        return response()->json(['message' => 'Se activó correctamente']);

    }

    public function obtenerNotificaciones(){
        return User::select('id','denuncias','menciones','mensajes','me_gustas','no_gustas','respuestas')->where('id', auth()->user()->id)->first();
    }

    public function prueba(Request $request){
        HelperServices::contadorPuntaje(auth()->user()->id, -10.6);//puntaje
        return 'test';
    }

    public function dashboard(Request $request){
        // preguntas respondidas
        $totalPreguntas = DB::select("select count(1) as cant from t_preguntas where tipo = 1 and activo = true;")[0]->cant;
        $totalPreguntasNoRespondidas = DB::select("select count(1) as cant from t_preguntas where tipo = 1 and c_respuestas = 0 and activo = true;")[0]->cant;
        $totalPreguntasRespondidas = $totalPreguntas - $totalPreguntasNoRespondidas;
        // preguntas vs publicacioens
        $totalContenido = DB::select("select count(1) as cant from t_preguntas where tipo in (1,2) and activo = true;")[0]->cant;
        $totalContenidoPreguntas = $totalPreguntas;
        $totalContenidoPublicaciones = $totalContenido - $totalPreguntas;

        // usuarios contenido
        $totalUsuarios = DB::select("select count(1) as cant from users where es_admin = false and activo = true;")[0]->cant;
        $totalUsuariosNoContenido = DB::select("select count(1) as cant from users us left join t_preguntas pre on us.id = pre.user_id where us.es_admin = false and us.activo = true and pre.user_id  is null;")[0]->cant;
        $totalUsuariosContenido = $totalUsuarios - $totalUsuariosNoContenido;

        return compact(
            "totalPreguntas",
            "totalPreguntasNoRespondidas",
            "totalPreguntasRespondidas",
            "totalContenido",
            "totalContenidoPreguntas",
            "totalContenidoPublicaciones",
            "totalUsuarios",
            "totalUsuariosNoContenido",
            "totalUsuariosContenido",
        );

    }


}
