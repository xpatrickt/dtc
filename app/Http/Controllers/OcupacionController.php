<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ocupacion;

class OcupacionController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = Ocupacion::findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo(){
        $select = Ocupacion::select('id', 'nombre')->where('activo',true)->get();
        return $select;
    }
   
    public function listar(){
        $lista = Ocupacion::select();
        return $lista->get();
    }

    public function crear(Request $request){
        $nuevo = new Ocupacion($request->all());       
        $nuevo->save();
        return response()->json(['message' => 'La ocupación se creó correctamente', 'identificador' => $nuevo->id]);
    }

    public function modificar(Request $request, $id){
        $editado = Ocupacion::findOrFail($id);        
        $editado->update($request->all()); 
        return response()->json(['message' => 'La ocupación se actualizó correctamente']);
    }

    public function inactivar($id){
        $inactivado = Ocupacion::findOrFail($id);
        $inactivado->activo = false;
        $inactivado->save(); 
        return response()->json(['message' => 'Se inactivó correctamente']);

    }

    public function activar($id){
        $activado = Ocupacion::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
