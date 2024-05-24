<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grado;
use App\Models\User;

class GradoController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = Grado::findOrFail($id);
        return $ver;
    }

    public function llenarCombo($id_nivel){
        $user = auth()->user();
        if($user->tipo_usuario == 2){
            $select = User::select('grado.id','grado.grado')
            ->where('users.id', $user->id)
            ->join('persona', 'persona.id', '=', 'users.id_persona')
            ->join('docente', 'persona.id', '=', 'docente.id_per')
            ->join('docente_curso', 'docente.id', '=', 'docente_curso.id_docente')
            ->join('seccion', 'seccion.id', '=', 'docente_curso.id_seccion')
            ->join('grado', 'grado.id', '=', 'seccion.id_grado')
            ->where('grado.id_nivel', $id_nivel);
        } else if ($user->tipo_usuario == 3){
            $select = User::select('grado.id','grado.grado')
            ->where('users.id', $user->id)
            ->join('persona', 'persona.id', '=', 'users.id_persona')
            ->join('estudiante', 'persona.id', '=', 'estudiante.id_per')
            ->join('matricula_estudiante', 'estudiante.id', '=', 'matricula_estudiante.id_est')
            ->join('seccion', 'seccion.id', '=', 'matricula_estudiante.id_sec')
            ->join('grado', 'grado.id', '=', 'seccion.id_grado')
            ->where('grado.id_nivel', $id_nivel);
        } else {
            $select = Grado::select('id', 'grado','id_nivel')->where('id_nivel',$id_nivel);
        }
        return $select->get();
    }

    public function listar(){
        $lista = Grado::select()->with([
            'nivel:id,nombre',
            ]);
        return $lista->get();
    }
    
    public function crear(Request $request){
        $existe = Grado::where('grado', $request->grado)->first();
        if($existe){
            return response()->json(['message' => 'El grado ya esta registrado'], 400);
        }
        else{
        $nuevo = new Grado($request->all());
        $nuevo->save();
        return response()->json(['message' => 'Registro creado correctamente', 'identificador' => $nuevo->id]);
    }
    }

    public function modificar(Request $request, $id){
        $editado = Grado::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'Registro actualizado correctamente']);
    }

    public function eliminar($id){
        $eliminado = Grado::findOrFail($id);
        $eliminado->delete();
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = Grado::findOrFail($id);
        $activado->activo = true;
        $activado->save();
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
