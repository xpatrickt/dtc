<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Docente;

class DocenteController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = Docente::findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo(){
        $select = Docente::select('id', 'Docente')->where('activo',true)->get();
        return $select;
    }
   
    public function listar(){
        $lista = Docente::select()->with([
            'persona',
            ]);
        return $lista->get();
    }

    public function crear(Request $request){
        if($request->existe==''){
            $existe2 = Persona::where('num_docid', $request->persona['num_docid'])->first();
            if($existe2){
                return response()->json(['message' => 'El DNI ya esta registrado'], 400);
            }
            else{
            $nuevoPersona = new Persona($request->persona);       
             $nuevoPersona->save();
            $nuevoDocente = new Docente($request->docente);
            $nuevoDocente->id_per= $nuevoPersona->id;
            $nuevoDocente->save();
            return response()->json(['message' => 'El docente se creó correctamente', 'idDocente' => $nuevoDocente->id,'idPersona' => $nuevoPersona->id]);        
        }}
        else {
            
            $nuevoDocente = new Docente($request->docente);
            $nuevoDocente->save();
            return response()->json(['message' => 'El docente se creó correctamente', 'idDocente' => $nuevoDocente->id]);
            
        }
        
    }

    public function modificar(Request $request,$id_al, $id_per){
        $editadoDocente = Docente::findOrFail($id_al);        
        $editadoDocente->update($request->docente);
        $editadoPersona = Persona::findOrFail($id_per);        
        $editadoPersona->update($request->persona);  
        return response()->json(['message' => 'El docente se actualizó correctamente']);
    }

    public function eliminar($id){
        $eliminado = Docente::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = Docente::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
