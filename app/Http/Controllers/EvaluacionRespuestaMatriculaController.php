<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\EvaluacionRespuestaMatricula;
use App\Models\EvaluacionAlternativa;

class EvaluacionRespuestaMatriculaController extends Controller
{

     //funciones generales de mantenimiento
     public function ver($id){
        $ver = EvaluacionRespuestaMatricula::findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo(){
        $select = EvaluacionRespuestaMatricula::select('id', 'EvaluacionRespuestaMatricula')->where('activo',true)->get();
        return $select;
    }
   
    public function listar(){
        $lista = EvaluacionRespuestaMatricula::select();
        return $lista->get();
    }

    public function listarRespuestas($id_evaluacion, $id_matricula){
        // $lista = EvaluacionRespuestaMatricula::select()
        // ->where('id_evaluacion_docen_curso', $id_evaluacion)
        // ->where('id_matricula', $id_matricula)->with(['evaluacion_alternativa:id,id_evaluacion_pregunta']);
        $query = "select correcta, count(*) as count, SUM(nota) as nota FROM evaluacion_docente_curso edc inner join evaluacion_respuesta_matricula erm on edc.id=erm.id_evaluacion_docen_curso  inner join evaluacion_alternativa ea on ea.id=erm.id_evaluacion_alter inner join evaluacion_pregunta ep on ep.id=ea.id_evaluacion_pregunta where edc.id=".$id_evaluacion." and erm.id_matricula = ".$id_matricula." group by correcta";
        $lista = DB::select($query);
        return $lista;
    }

    public function crear(Request $request){
        $nuevo = new EvaluacionRespuestaMatricula($request->all());
        $nuevo->save();
        return response()->json(['message' => 'Registro creado correctamente', 'identificador' => $nuevo->id]);
    }

    public function modificar(Request $request, $id){
        $editado = EvaluacionRespuestaMatricula::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'Registro actualizado correctamente']);
    }

    public function eliminar($id){
        $eliminado = EvaluacionRespuestaMatricula::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se Elimino correctamente']);

    }
    
    public function activar($id){
        $activado = EvaluacionRespuestaMatricula::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
