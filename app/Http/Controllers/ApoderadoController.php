<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Apoderado;
use App\Models\AlumnoApoderado;


class ApoderadoController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = Apoderado::with(['persona',
            ])->findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo(){
        $select = Apoderado::select('id', 'Apoderado')->where('activo',true)->get();
        return $select;
    }
   
    public function listar(){
        $lista = Apoderado::select()->with([
            'persona',
            ]);
        return $lista->get();
    }
    public function listarEstudianteApoderado($id){
        $lista = AlumnoApoderado::select()->with([
            'apoderado','apoderado.persona'
            ])->where('id_est',$id);
        return $lista->get();
    }
    public function buscar($dni){
        $persona = Persona::select()->where('num_docid',$dni)->first();
        if($persona){
            $lista = Apoderado::select()->with([
                'persona'
                ])->where('id_per',$persona->id);
            return $lista->first();
        }
        else{
            return;        
 
        }
    }

    public function crear(Request $request){
        if($request->existe==''){
            $nuevoPersona = new Persona($request->persona);       
             $nuevoPersona->save();
            $nuevoApoderado = new Apoderado($request->alumno);
            $nuevoApoderado->id_per= $nuevoPersona->id;
            $nuevoApoderado->save();
            return response()->json(['message' => 'El alumno se creó correctamente', 'idApoderado' => $nuevoApoderado->id,'idPersona' => $nuevoPersona->id]);        
        }
        else {
            
            $nuevoApoderado = new Apoderado($request->alumno);
            $nuevoApoderado->save();
            return response()->json(['message' => 'El alumno se creó correctamente', 'idApoderado' => $nuevoApoderado->id]);
            
        }
        
    }

    public function estudianteApoderado(Request $request){

            $estudianteApoderado = new AlumnoApoderado($request->all());
            $estudianteApoderado->save();
            return response()->json(['message' => 'Se Agrego el Apoderado correctamente', 'idApoderado' => $estudianteApoderado->id]);        
    }

    public function modificar(Request $request,$id_al, $id_per){
        $editadoApoderado = Apoderado::findOrFail($id_al);        
        $editadoApoderado->update($request->alumno);
        $editadoPersona = Persona::findOrFail($id_per);        
        $editadoPersona->update($request->persona);  
        return response()->json(['message' => 'El alumno se actualizó correctamente']);
    }

    public function eliminar($id){
        $eliminado = Apoderado::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = Apoderado::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
