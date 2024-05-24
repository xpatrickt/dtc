<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Convocatoria;
use App\Models\User;


class COnvocatoriaController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = Criterio::findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo($id_act){
        $select = Criterio::select('id', 'nombre')->where('id_act',$id_act)->get();
        return $select;
    }
   
    public function listar(){
        $user = auth()->user();        
        $lista = Convocatoria::select('id', 'nombre')->where('nivel','>',$user->tipo_usuario)->get();
        return $lista;
    }

    public function crear(Request $request){
        $nuevo = new Criterio();
        $nuevo->nombre = $request->criterio;
        $nuevo->id_act = $request->id_actividad;
        $nuevo->save();
        return response()->json(['message' => 'La Criterio se creó correctamente', 'identificador' => $nuevo->id]);
    }

    public function modificar(Request $request, $id){
        $editado = Criterio::findOrFail($id);  
        $editado->id_gracur = $request->actividad['id_gracur'];   
        $editado->update($request->all());
        return response()->json(['message' => 'La Criterio se actualizó correctamente']);
    }

    public function eliminar($id){
        $eliminado = Criterio::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = Criterio::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
