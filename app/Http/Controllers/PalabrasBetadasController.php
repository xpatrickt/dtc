<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PalabrasBetadas;

class PalabrasBetadasController extends Controller
{
    //funciones generales de mantenimiento
    public function ver($id){
        $ver = PalabrasBetadas::findOrFail($id);
        return $ver;
    }

    public function llenarCombo(){
        $select = PalabrasBetadas::select('id', 'nombre')->where('activo',true)->get();
        return $select;
    }

    public function listar(){
        $lista = PalabrasBetadas::select()->get();
        return $lista;
    }

    public function crear(Request $request){
        $existe = PalabrasBetadas::where('palabra', $request->palabra)->first();
        if($existe)
            return response()->json(['message' => 'La palabra ya existe'], 400);
        $nuevo = new PalabrasBetadas($request->all());
        $nuevo->save();
        return response()->json(['message' => 'La palabra se creó correctamente', 'identificador' => $nuevo->id]);
    }

    public function modificar(Request $request, $id){
        $existe = PalabrasBetadas::where('palabra', $request->palabra)->where('id','<>',$id)->first();
        if($existe)
            return response()->json(['message' => 'La palabra ya existe'], 400);
        $editado = PalabrasBetadas::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'La palabra se actualizó correctamente']);
    }

    public function inactivar($id){
        $inactivado = PalabrasBetadas::findOrFail($id);
        $inactivado->activo = false;
        $inactivado->save();
        return response()->json(['message' => 'Se inactivó correctamente']);

    }

    public function activar($id){
        $activado = PalabrasBetadas::findOrFail($id);
        $activado->activo = true;
        $activado->save();
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
