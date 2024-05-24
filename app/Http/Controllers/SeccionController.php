<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seccion;
use App\Models\User;

class SeccionController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = Seccion::findOrFail($id);
        return $ver;
    }

    public function llenarCombo($id_grado){
        $user = auth()->user();
        if($user->tipo_usuario == 2){
            $select = User::select('seccion.id','seccion.seccion')
            ->where('users.id', $user->id)
            ->join('persona', 'persona.id', '=', 'users.id_persona')
            ->join('docente', 'persona.id', '=', 'docente.id_per')
            ->join('docente_curso', 'docente.id', '=', 'docente_curso.id_docente')
            ->join('seccion', 'seccion.id', '=', 'docente_curso.id_seccion')
            ->where('seccion.id_grado', $id_grado);
        } else if ($user->tipo_usuario == 3){
            $select = User::select('seccion.id','seccion.seccion')
            ->where('users.id', $user->id)
            ->join('persona', 'persona.id', '=', 'users.id_persona')
            ->join('estudiante', 'persona.id', '=', 'estudiante.id_per')
            ->join('matricula_estudiante', 'estudiante.id', '=', 'matricula_estudiante.id_est')
            ->join('seccion', 'seccion.id', '=', 'matricula_estudiante.id_sec')
            ->where('seccion.id_grado', $id_grado);
        } else {
            $select = Seccion::select('id', 'seccion')->where('id_grado', $id_grado);
        }
        return $select->get();
    }

    public function listar(){
        $lista = Seccion::select()->with([
            'grado','grado.nivel'
            ]);
        return $lista->get();
    }

    public function crear(Request $request){
        $existe = Seccion::where('seccion', $request->seccion)->first();
        if($existe){
            return response()->json(['message' => 'la Sección ya esta registrada'], 400);
        }
        else{
            $nuevo = new Seccion($request->all());
            $nuevo->save();
            return response()->json(['message' => 'Registro creado correctamente', 'identificador' => $nuevo->id]);
        }

    }

    public function modificar(Request $request, $id){
        $editado = Seccion::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'Registro actualizado correctamente']);
    }

    public function eliminar($id){
        $eliminado = Seccion::findOrFail($id);
        $eliminado->delete();
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = Seccion::findOrFail($id);
        $activado->activo = true;
        $activado->save();
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
