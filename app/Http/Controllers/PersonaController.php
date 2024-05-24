<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;

class PersonaController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = Persona::findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo(){
        $select = Persona::select('id', 'Persona')->where('activo',true)->get();
        return $select;
    }
   
    public function listar(){
        $lista = Persona::select();
        return $lista->get();
    }

    public function crear(Request $request){
        $nuevo = new Persona($request->all());       
        $nuevo->save();
        return response()->json(['message' => 'La ocupación se creó correctamente', 'identificador' => $nuevo->id]);
    }

    public function modificar(Request $request, $id){
        $editado = Persona::findOrFail($id);        
        $editado->update($request->all()); 
        return response()->json(['message' => 'La ocupación se actualizó correctamente']);
    }
    public function buscar($dni){
        $buscar = Persona::select()->where('num_docid',$dni)->first();        
        return $buscar;
    }

    public function eliminar($id){
        $eliminado = Persona::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = Persona::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
