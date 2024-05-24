<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Asistencia;
use App\Models\PersonaConvocatoria;
use App\Models\Evaluacion;
use App\Models\DocenteCurso;
use App\Models\Matricula;


class AsistenciaController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($dni,$id_convocatoria){
        $persona = Persona::select('id','nombres','apellido_pat','apellido_mat','documento' )->where('documento',$dni)->get();

        $proceso = PersonaConvocatoria::select('id','id_persona','id_convocatoria','id_sede_provincial')
        ->where('id_persona',$persona[0]['id'])
        ->where('id_convocatoria',$id_convocatoria)
        ->get();
        
                    $resultado = Asistencia::select()->where('id_persona_convocatoria',$proceso[0]['id'])->get();
                    
                if ($resultado[0]->estado==0) {
                    $asistencia = Asistencia::findOrFail($resultado[0]->id);
                    $asistencia->estado = 1;
                    $asistencia->update(['estado' => '1']);
                    return response()->json(['message' => 'Asistencia guardada correctamente', 'persona' => $persona, 'proceso'=>$proceso,'asistencia'=>$resultado,'flag'=>0]);
                }
                else if ($resultado[0]->estado==1) {
                    $asistencia = Asistencia::select()->where('id_persona_convocatoria',$proceso[0]['id'])->get(); 
                    return response()->json(['message' => 'El postulante ya registro su ingreso', 'persona' => $persona, 'proceso'=>$proceso,'asistencia'=>$asistencia,'flag'=>1]);
                
                } else {
                    return response()->json(['message' => 'El postulante no esta registrado','flag'=>3]);
 
                }            
        
    }
    
    public function llenarCombo(){
        $select = Asistencia::select('id', 'Asistencia')->where('activo',true)->get();
        return $select;
    }
   
    public function listar(){
        $lista = Asistencia::select()->with([
            'persona',
            ]);
        return $lista->get();
    }

    public function crear(Request $request){
        $docente = DocenteCurso::where('id_curso',$request->seccion['id_curso'])->where('id_seccion',$request->seccion['id_seccion'])->first();
        $siAsistencia = Asistencia::where('fecha',$request->seccion['fecha'])
        ->where('id_docente_curso',$docente->id)->first();
        if ($siAsistencia) {

            $asistencia1 = Asistencia::where('fecha',$request->seccion['fecha'])
        ->where('id_docente_curso',$docente->id)
        ->with(['matricula:id,id_est','matricula.estudiante:id,id_per','matricula.estudiante.persona:id,apellido_pat,apellido_mat,nombres'])->get();
        return response()->json(['message' => 'Se Cargo correctamente la asistencia', 'lista' => $asistencia1]);        
        }
        else{
        $matricula = Matricula::where('id_sec',$request->seccion['id_seccion'])->get();
        foreach ($matricula as $key => $value) {
            $asistencia = new Asistencia();
            $asistencia->fecha = $request->seccion['fecha'];
            $asistencia->id_matric = $value->id;
            $asistencia->id_docente_curso = $docente->id;
            $asistencia->save();
        }
        $asistencia1 = Asistencia::where('fecha',$request->seccion['fecha'])
        ->where('id_docente_curso',$docente->id)
        ->with(['matricula:id,id_est','matricula.estudiante:id,id_per','matricula.estudiante.persona:id,apellido_pat,apellido_mat,nombres'])->get();
        return response()->json(['message' => 'Se Genero correctamente la asistencia', 'lista' => $asistencia1]);        
        }
    }

    public function modificar($id,Request $request){

        if ($request->estado) {
            $editadoAsistencia = Asistencia::findOrFail($id);        
            $editadoAsistencia->update(['estado'=>$request->estado]);
            if ($request->estado =='.' || $request->estado=='F') {                        
                    $editadoAsistencia->update(['razon'=>'']);
                    return response()->json(['message' => 'Se agrego la razon de la justificacion correctamente']);
                }else{
                    $editadoAsistencia = Asistencia::findOrFail($id);        
                    $editadoAsistencia->update(['razon'=>$request->razon]);
                    return response()->json(['message' => 'Se agrego la razon de la justificacion correctamente']);
                }
            }            
            else{
                $editadoAsistencia = Asistencia::findOrFail($id);        
                $editadoAsistencia->update(['razon'=>$request->razon]);
                return response()->json(['message' => 'Se agrego la razon de la justificacion correctamente']);
            }
            return response()->json(['message' => 'Se registro la asistencia correctamente']);
        
         
        
    }

    public function eliminar($id){
        $eliminado = Asistencia::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = Asistencia::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
