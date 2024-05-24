<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nivel;
use App\Models\User;
class NivelController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = Nivel::findOrFail($id);
        return $ver;
    }

    public function llenarCombo(){
        $select = Nivel::select('id', 'nombre')->where('activo',true)->get();
        return $select;
    }

    public function listar(){
        $user = auth()->user();
        if($user->tipo_usuario == 2){
            $lista = User::select('nivel.id','nivel.nombre')
            ->where('users.id', $user->id)
            ->join('persona', 'persona.id', '=', 'users.id_persona')
            ->join('docente', 'persona.id', '=', 'docente.id_per')
            ->join('docente_curso', 'docente.id', '=', 'docente_curso.id_docente')
            ->join('seccion', 'seccion.id', '=', 'docente_curso.id_seccion')
            ->join('grado', 'grado.id', '=', 'seccion.id_grado')
            ->join('nivel', 'nivel.id', '=', 'grado.id_nivel');
        } else if ($user->tipo_usuario == 3){
            $lista = User::select('nivel.id','nivel.nombre')
            ->where('users.id', $user->id)
            ->join('persona', 'persona.id', '=', 'users.id_persona')
            ->join('estudiante', 'persona.id', '=', 'estudiante.id_per')
            ->join('matricula_estudiante', 'estudiante.id', '=', 'matricula_estudiante.id_est')
            ->join('seccion', 'seccion.id', '=', 'matricula_estudiante.id_sec')
            ->join('grado', 'grado.id', '=', 'seccion.id_grado')
            ->join('nivel', 'nivel.id', '=', 'grado.id_nivel');
        } else {
            $lista = Nivel::select();
        }
        return $lista->get();
    }

    public function crear(Request $request){
        $existe = Nivel::where('nombre', $request->nombre)->first();
        if($existe){
            return response()->json(['message' => 'El nivel ya esta registrado'], 400);
        }
        else{
            $nuevo = new Nivel($request->all());
            $nuevo->save();
            return response()->json(['message' => 'Registro creado correctamente', 'identificador' => $nuevo->id]);
        }
    }

    public function modificar(Request $request, $id){
        $editado = Nivel::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'Registro actualizado correctamente']);
    }

    public function eliminar($id){
        $eliminado = Nivel::findOrFail($id);
        $eliminado->delete();
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = Nivel::findOrFail($id);
        $activado->activo = true;
        $activado->save();
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
