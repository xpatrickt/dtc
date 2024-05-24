<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GradoCurso;
use App\Models\User;

class GradoCursoController extends Controller
{

    //funciones generales de mantenimiento
     public function ver($id){
        $ver = GradoCurso::findOrFail($id);
        return $ver;
    }

    public function llenarCombo($id_grado){
        $user = auth()->user();
        if($user->tipo_usuario == 2){
            $select = GradoCurso::select('id', 'id_grado','id_curso')
            ->where('id_grado', $id_grado)
            ->with([ 'grado:id,grado','curso:id,nombre','docente_curso:id,id_curso,id_docente,id_seccion','docente_curso.docente.persona:id','docente_curso.docente.persona.user:id']);
            $select->whereHas('docente_curso.docente.persona.user',function ($q){
                $q->where('id', auth()->user()->id);
            });
        } else {
            $select = GradoCurso::select('id', 'id_grado','id_curso')
            ->where('id_grado', $id_grado)
            ->with([ 'grado:id,grado','curso:id,nombre']);
        }
        return $select->get();
    }

    public function listar($id_grado){
        $select = GradoCurso::select('id', 'id_grado','id_curso')
        ->where('id_grado', $id_grado)
        ->with([ 'grado:id,grado','curso:id,nombre']);
        return $select->get();
    }

    public function crear(Request $request){
        $existe = GradoCurso::where('id_grado', $request->id_grado)->Where('id_curso', $request->id_curso)->first();

        if($existe){
            return response()->json(['message' => 'Curso ya esta asignado a grado'], 400);
        }
        else{
            $nuevo = new GradoCurso($request->all());
            $nuevo->save();
            return response()->json(['message' => 'Se asignó el curso correctamente', 'identificador' => $nuevo->id]);
    }


    }

    public function modificar(Request $request, $id){
        $editado = GradoCurso::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'El curso se actualizó correctamente']);
    }

    public function eliminar($id){
        $eliminado = GradoCurso::findOrFail($id);
        $eliminado->delete();
        return response()->json(['message' => 'Se eliminó correctamente']);

    }

    public function activar($id){
        $activado = GradoCurso::findOrFail($id);
        $activado->activo = true;
        $activado->save();
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
