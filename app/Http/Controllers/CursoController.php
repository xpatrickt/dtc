<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;

class CursoController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = Curso::findOrFail($id);
        return $ver;
    }

    public function llenarCombo(){
        $select = Curso::select('id', 'Curso')->where('activo',true)->get();
        return $select;
    }

    public function listar(){
        $lista = Curso::select();
        return $lista->get();
    }

    public function listarPrimaria(){
        $lista = Curso::select()->where('codigo', 'like', 'P%');
        return $lista->get();
    }

    public function listarSecundaria(){
        $lista = Curso::select()->where('codigo', 'like', 'S%');
        return $lista->get();
    }


    public function crear(Request $request){
        $existe = Curso::where('codigo', $request->codigo)->first();
        if($existe){
            return response()->json(['message' => 'Codigo ya registrado'], 400);
        }
        else{
            $nuevo = new Curso($request->all());
            $nuevo->save();
            return response()->json(['message' => 'Registro creado correctamente', 'identificador' => $nuevo->id]);
        }

    }

    public function modificar(Request $request, $id){
        $editado = Curso::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'Registro actualizado correctamente']);
    }

    public function eliminar($id){
        $eliminado = Curso::findOrFail($id);
        $eliminado->delete();
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = Curso::findOrFail($id);
        $activado->activo = true;
        $activado->save();
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
