<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

class LibroController extends Controller
{
    protected $rutaLibros = 'recursos/libros/';
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = Libro::findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo($id_nivel){
        $select = Libro::select('id', 'grado','id_nivel')->where('id_nivel',$id_nivel)->get();
        return $select;
    }
   
    public function listar(){
        $lista = Libro::select()->with([
            'grado_curso', 'grado_curso.curso', 'grado_curso.grado'
            ]);
        return $lista->get();
    }

    public function listarFiltro($id_grado_cur){
        $lista = Libro::select()->where('id_grado_cur',$id_grado_cur)->with([
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
                $file = $request->file('url')->move($this->rutaLibros, $filename.".".$extension);
                $path = $filename.".".$extension;
            }
            $nuevo = new Libro();
            $nuevo->nombre = $request->nombre;
            $nuevo->descripcion = $request->descripcion;
            $nuevo->autor = $request->autor;
            $nuevo->subido = true;
            $nuevo->url = $path;
            $nuevo->id_grado_cur = $request->id_grado_cur;
            $nuevo->save();
            return response()->json(['message' => 'El libro se creó correctamente', 'identificador' => $nuevo->id]);
        } else {
            $nuevo = new Libro($request->all()); 
            $nuevo->save();
            return response()->json(['message' => 'El libro se creó correctamente', 'identificador' => $nuevo->id]);
        } 
       
    }

    public function modificar(Request $request, $id){
        $editado = Libro::findOrFail($id);        
        $editado->update($request->all()); 
        return response()->json(['message' => 'El libro se actualizó correctamente']);
    }

    public function eliminar($id){
        $eliminado = Libro::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = Libro::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
