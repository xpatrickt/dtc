<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocenteCurso;
use App\Models\Grado;
use App\Models\Seccion;

class DocenteCursoController extends Controller
{

    //funciones generales de mantenimiento
     public function ver($id){
        $ver = DocenteCurso::findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo($id_docente){
        $select = DocenteCurso::select('id', 'id_docente','id_curso')
        ->where('id_docente', $id_docente)
        ->with([ 'docente:id,docente','curso:id,nombre']);
        return $select->get();
    }

    public function llenarComboGrado($id_nivel, $id_docente){
        $select = Grado::select()
        ->where('id_nivel', $id_nivel)
        ->with([ 'seccion','seccion.docente_curso']);
        $select->whereHas('seccion.docente_curso',function($q) use ($id_docente){
            $q->where('id_docente', $id_docente);
        });
        return $select->get();
    }

    public function llenarComboSeccion($id_grado, $id_docente){
        $select = Seccion::select()
        ->where('id_grado', $id_grado)
        ->with([ 'docente_curso']);
        $select->whereHas('docente_curso',function($q) use ($id_docente){
            $q->where('id_docente', $id_docente);
        });
        return $select->get();
    }

    public function llenarComboCurso($id_seccion, $id_docente){
        $select = DocenteCurso::select()
        ->where('id_seccion', $id_seccion)
        ->where('id_docente', $id_docente)
        ->with([ 'curso']);
        return $select->get();
    }
   
    public function listar($id_docente){
        $select = DocenteCurso::select()
        ->where('id_docente', $id_docente)
        ->with([ 'seccion:id,id_grado,seccion','seccion.grado:id,grado,id_nivel','seccion.grado.nivel:id,nombre','curso:id,nombre']);
        return $select->get();
    }

    public function crear(Request $request){
        $nuevo = new DocenteCurso($request->all());       
        $nuevo->save();
        return response()->json(['message' => 'Se asignó el curso correctamente', 'identificador' => $nuevo->id]);
    }

    public function modificar(Request $request, $id){
        $editado = DocenteCurso::findOrFail($id);        
        $editado->update($request->all()); 
        return response()->json(['message' => 'El curso se actualizó correctamente']);
    }

    public function eliminar($id){
        $eliminado = DocenteCurso::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se eliminó correctamente']);

    }

    public function activar($id){
        $activado = DocenteCurso::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
