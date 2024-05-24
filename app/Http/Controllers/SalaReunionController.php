<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalaReunion;
use App\Models\DocenteCurso;

class SalaReunionController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = SalaReunion::findOrFail($id);
        return $ver;
    }

    public function llenarCombo($id_nivel){
        $select = SalaReunion::select('id', 'grado','id_nivel')->where('id_nivel',$id_nivel)->get();
        return $select;
    }

    public function listar($tipo){
        if($tipo == 2){
            $lista = SalaReunion::select()->with([
                'docente_curso:id,id_seccion,id_curso,id_docente','docente_curso.seccion','docente_curso.seccion.grado','docente_curso.seccion.grado.nivel','docente_curso.curso','docente_curso.docente.persona','docente_curso.docente.persona.user'
                ]);
            $lista->whereHas('docente_curso.docente.persona.user',function ($q){
                $q->where('id', auth()->user()->id);
            });
        }
        else if($tipo == 3){
            // $lista = SalaReunion::select()->with([
            //     'docente_curso:id,id_seccion,id_curso,id_docente','docente_curso.seccion','docente_curso.seccion.grado','docente_curso.seccion.grado.nivel','docente_curso.curso','docente_curso.docente.persona','docente_curso.docente.persona.user'
            //     ]);
            // $lista->whereHas('docente_curso.docente.persona.user',function ($q){
            //     $q->where('id', auth()->user()->id);
            // });
            $lista = SalaReunion::select()->with([
                'docente_curso:id,id_seccion,id_curso,id_docente','docente_curso.seccion','docente_curso.seccion.grado','docente_curso.seccion.grado.nivel','docente_curso.curso','docente_curso.docente.persona'
                ]);
        } else{
            $lista = SalaReunion::select()->with([
                'docente_curso:id,id_seccion,id_curso,id_docente','docente_curso.seccion','docente_curso.seccion.grado','docente_curso.seccion.grado.nivel','docente_curso.curso','docente_curso.docente.persona'
                ]);
        }
        return $lista->get();
    }
    
    public function crear(Request $request){
        $existe = DocenteCurso::where('id_seccion', $request->id_seccion)->where('id_curso', $request->id_curso)->first();
        if(!$existe){
            return response()->json(['message' => 'El curso de la sección no tiene asignado un docente', 'identificador'=>null]);
        }
        else{
        $nuevo = new SalaReunion($request->sala_reunion);
        $nuevo->id_docen_curso= $existe->id;
        $nuevo->save();
        return response()->json(['message' => 'Registro creado correctamente', 'identificador' => $nuevo->id]);
    }
    }

    public function modificar(Request $request, $id){
        $editado = SalaReunion::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'Registro actualizado correctamente']);
    }

    public function eliminar($id){
        $eliminado = SalaReunion::findOrFail($id);
        $eliminado->delete();
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = SalaReunion::findOrFail($id);
        $activado->activo = true;
        $activado->save();
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
