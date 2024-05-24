<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class VideoController extends Controller
{
    protected $rutaVideos = 'recursos/videos/';
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = Video::findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo($id_nivel){
        $select = Video::select('id', 'grado','id_nivel')->where('id_nivel',$id_nivel)->get();
        return $select;
    }
   
    public function listar(){
        $lista = Video::select()->with([
            'grado_curso', 'grado_curso.curso', 'grado_curso.grado'
            ]);
        return $lista->get();
    }

    public function listarFiltro($id_grado_cur){
        $lista = Video::select()->where('id_grado_cur',$id_grado_cur)->with([
            'grado_curso', 'grado_curso.curso', 'grado_curso.grado'
            ]);
        return $lista->get();
    }

    public function crear(Request $request){
        if($request->subido){
            $url = $request->url;
            $path = '';
            
            if($url != 'undefined' || !$url ){  
                $extension = $request->file('url')->getClientOriginalExtension();
                $filename = uniqid();
                $file = $request->file('url')->move($this->rutaVideos, $filename.".".$extension);
                $path = $filename.".".$extension;
            }
            $nuevo = new Video();
            $nuevo->nombre = $request->nombre;
            $nuevo->descripcion = $request->descripcion;
            $nuevo->autor = $request->autor;
            $nuevo->subido = true;
            $nuevo->url = $path;
            $nuevo->id_grado_cur = $request->id_grado_cur;
            $nuevo->save();
            return response()->json(['message' => 'El video se creó correctamente', 'identificador' => $nuevo->id]);
        } else {
            $nuevo = new Video($request->all());       
            $nuevo->save();
            return response()->json(['message' => 'El video se creó correctamente', 'identificador' => $nuevo->id]);
        }
    }

    public function modificar(Request $request, $id){
        $editado = Video::findOrFail($id);        
        $editado->update($request->all()); 
        return response()->json(['message' => 'El video se actualizó correctamente']);
    }

    public function eliminar($id){
        $eliminado = Video::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = Video::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
