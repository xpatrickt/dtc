<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\EvaluacionDocenteCurso;

class EvaluacionDocenteCursoController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = EvaluacionDocenteCurso::findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo(){
        $select = EvaluacionDocenteCurso::select('id', 'EvaluacionDocenteCurso')->where('activo',true)->get();
        return $select;
    }
   
    public function listar($tipo){
        if($tipo == 1){
            $lista = EvaluacionDocenteCurso::select()->with([
                'evaluacion:id,descripcion,nota,id_docente','evaluacion.pregunta_count:id,id_evaluacion','docente_curso:id,id_curso,id_seccion','docente_curso.curso:id,nombre', 'docente_curso.seccion:id,seccion,id_grado','docente_curso.seccion.grado:id,grado,id_nivel','docente_curso.seccion.grado.nivel:id,nombre'
                ]);
        } else if($tipo ==2){
            $lista = EvaluacionDocenteCurso::select()->with([
                'evaluacion:id,descripcion,nota,id_docente','evaluacion.pregunta_count:id,,id_evaluacion','docente_curso:id,id_curso,id_seccion','docente_curso.curso:id,nombre', 'docente_curso.seccion:id,seccion,id_grado','docente_curso.seccion.grado:id,grado,id_nivel','docente_curso.seccion.grado.nivel:id,nombre','docente_curso.docente.persona','docente_curso.docente.persona.user'
                ]);
            $lista->whereHas('docente_curso.docente.persona.user',function ($q){
            $q->where('id', auth()->user()->id);
            });
        } else {
            $lista = EvaluacionDocenteCurso::select()->with([
                'evaluacion:id,descripcion,nota,id_docente','evaluacion.pregunta_count:id,id_evaluacion','docente_curso:id,id_curso,id_seccion','docente_curso.curso:id,nombre', 'docente_curso.seccion:id,seccion,id_grado','docente_curso.seccion.grado:id,grado,id_nivel','docente_curso.seccion.grado.nivel:id,nombre'
                ]);
        }
        
        return $lista->get();
    }

    public function listarEvaluacion($id_evaluacion){
        $lista = EvaluacionDocenteCurso::select()->with([
            'docente_curso:id,id_curso,id_seccion','docente_curso.curso:id,nombre','docente_curso.seccion:id,seccion,id_grado','docente_curso.seccion.grado:id,grado,id_nivel','docente_curso.seccion.grado.nivel:id,nombre'])
            ->where('id_evaluacion', $id_evaluacion);
        return $lista->get();
    }

    public function crear(Request $request){
        $nuevo = new EvaluacionDocenteCurso($request->all());
        $nuevo->save();
        return response()->json(['message' => 'Registro creado correctamente', 'identificador' => $nuevo->id]);
    }

    public function modificar(Request $request, $id){
        $editado = EvaluacionDocenteCurso::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'Registro actualizado correctamente']);
    }

    public function eliminar($id){
        $eliminado = EvaluacionDocenteCurso::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = EvaluacionDocenteCurso::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
