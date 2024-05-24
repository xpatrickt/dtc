<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;
use App\Models\Persona;
use App\Models\Alumno;
use App\Models\Apoderado;
use App\Models\AlumnoApoderado;


class MatriculaController extends Controller
{

    //funciones generales de mantenimiento
     public function ver($id){
        $ver = Matricula::findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo($id_grado){
        $select = Matricula::select('id', 'id_grado','id_curso')
        ->where('id_grado', $id_grado)
        ->with([ 'grado:id,grado','curso:id,nombre']);
        return $select->get();
    }
   
    public function listar($id_grado){
        $select = Matricula::select('id', 'id_grado','id_curso')
        ->where('id_grado', $id_grado)
        ->with([ 'grado:id,grado','curso:id,nombre']);
        return $select->get();
    }
    
    public function guardarApoderado(Request $request){
        if($request->existe==''||$request->existe==null){          
            $nuevoPersona = new Persona($request->persona);
            $nuevoPersona->save();

            $nuevoApoderado = new Apoderado($request->apoderado);
            $nuevoApoderado->id_per= $nuevoPersona->id;
            $nuevoApoderado->save();

            $alumnoApoderado = new AlumnoApoderado();
            $alumnoApoderado->id_apo = $nuevoApoderado->id;
            $alumnoApoderado->id_est = $request->apoderadoEstudiante['id_est'];
            $alumnoApoderado->vinculo = $request->apoderadoEstudiante['vinculo'];
            $alumnoApoderado->save();
            return response()->json(['message' => 'Se agrego el apoderado correctamente', 'idPersona' => $nuevoPersona->id]);        
        }
        else {

            $nuevoApoderado = new Apoderado($request->apoderado);
            $nuevoApoderado->id_per= $request->existe;
            $nuevoApoderado->save();

            $alumnoApoderado = new AlumnoApoderado();
            $alumnoApoderado->id_apo = $nuevoApoderado->id;
            $alumnoApoderado->id_est = $request->apoderadoEstudiante['id_est'];
            $alumnoApoderado->vinculo = $request->apoderadoEstudiante['vinculo'];
            $alumnoApoderado->save();
            return response()->json(['message' => 'La Matricula se generó correctamente', 'idAlumnoApoderado' => $alumnoApoderado->id]);        
       
        }
        
    }
    public function crear(Request $request){
        if($request->existe==''||$request->existe==null){          
            $nuevoPersona = new Persona($request->persona);
            $nuevoPersona->save();

            $nuevoAlumno = new Alumno($request->alumno);
            $nuevoAlumno->id_per= $nuevoPersona->id;
            $nuevoAlumno->save();

            $matricula = new Matricula();
            $matricula->anio = $request->anio;
            $matricula->id_sec = $request->seccion['id_seccion'];
            $matricula->id_est = $nuevoAlumno->id;
            $matricula->save();
            return response()->json(['message' => 'La Matricula se generó correctamente', 'idAlumno' => $nuevoAlumno->id,'idPersona' => $nuevoPersona->id]);        
        }
        else {
            $existe = Alumno::where('id_per', $request->existe)->first();
            if($existe){

                $matriculado = Matricula::where('id_est',$existe->id)->where('anio',$request->anio)->first();
                if($matriculado){
                    
                    return response()->json(['message' => 'El alumno ya está matriculado para el año '.$request->anio, 'idMatricula' => $matriculado->id]);
                }
                else{
                $matricula = new Matricula();
                $matricula->anio = $request->anio;  
                $matricula->id_sec = $request->seccion['id_seccion'];
                $matricula->id_est = $existe->id;
                $matricula->save();

                return response()->json(['message' => 'La Matricula se generó correctamente', 'idAlumno' => $nuevoAlumno->id]);
                }
            }
            else{
                $nuevoAlumno = new Alumno($request->alumno);
                $nuevoAlumno->save();

                $matricula = new Matricula();
                $matricula->anio = $request->anio;
                $matricula->id_sec = $request->seccion['id_seccion'];
                $matricula->id_est = $nuevoAlumno->id;
                $matricula->save();
                return response()->json(['message' => 'La Matricula se generó correctamente', 'idAlumno' => $nuevoAlumno->id]);
            }
        }
        
    }

    public function modificar(Request $request, $id){
        $editado = Matricula::findOrFail($id);        
        $editado->update($request->all()); 
        return response()->json(['message' => 'El curso se actualizó correctamente']);
    }

    public function eliminar($id){
        $eliminado = Matricula::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se eliminó correctamente']);

    }

    public function activar($id){
        $activado = Matricula::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
