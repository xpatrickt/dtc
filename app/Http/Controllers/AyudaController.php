<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ayuda;

use Validator;

class AyudaController extends Controller
{
    protected $ruta_imagen = 'user/ayuda/';

     //funciones generales de mantenimiento
    public function ver($id){
        $ver = Ayuda::findOrFail($id);
        return $ver;
    }

    public function llenarCombo(){
        $select = Ayuda::select('id', 'nombre')->where('activo',true)->get();
        return $select;
    }

    public function listar(Request $request){
        $lista = Ayuda::select();
        if($request->tipo)
            $lista->where('tipo', $request->tipo);
        return $lista->get();
    }

    public function crear(Request $request){
        $validation = Validator::make($request->all(), [
            'imagenAyuda' => 'required|image',
        ]);

        if ($validation->fails())
            return response()->json(['message' => $validation->messages()->first()],422);

        $extension = $request->file('imagenAyuda')->getClientOriginalExtension();
        $filename = uniqid();
        $file = $request->file('imagenAyuda')->move($this->ruta_imagen, $filename.".".$extension);
        $img = \Image::make($this->ruta_imagen.$filename.".".$extension);
        $img->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($this->ruta_imagen.$filename.".".$extension);
        $request->merge(['imagen' =>  $filename.".".$extension]);
        $nuevo = new Ayuda($request->all());
        $nuevo->save();
        return response()->json(['message' => 'La ayuda se creó correctamente', 'identificador' => $nuevo->id]);
    }

    public function modificar(Request $request, $id){
        $editado = Ayuda::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'La ayuda se actualizó correctamente']);
    }

    public function modificarImagen(Request $request){
        $id = $request->id;
        $validation = Validator::make($request->all(), [
            'imagenAyuda' => 'required|image',
        ]);
        $editado = Ayuda::findOrFail($id);

        if ($validation->fails())
            return response()->json(['message' => $validation->messages()->first()],422);
        
        if($editado->imagen && \File::exists($this->ruta_imagen.$editado->imagen))
            \File::delete($this->ruta_imagen.$editado->imagen);

        $extension = $request->file('imagenAyuda')->getClientOriginalExtension();
        $filename = uniqid();
        $file = $request->file('imagenAyuda')->move($this->ruta_imagen, $filename.".".$extension);
        $img = \Image::make($this->ruta_imagen.$filename.".".$extension);
        $img->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($this->ruta_imagen.$filename.".".$extension);

        $editado->imagen = $filename.".".$extension;
        $editado->save();
        return response()->json(['message' => 'La imagen se modificó correctamente', 'imagen' => $filename.".".$extension]);
    }

    public function inactivar($id){
        $inactivado = Ayuda::findOrFail($id);
        $inactivado->activo = false;
        $inactivado->save();
        return response()->json(['message' => 'Se inactivó correctamente']);

    }

    public function activar($id){
        $activado = Ayuda::findOrFail($id);
        $activado->activo = true;
        $activado->save();
        return response()->json(['message' => 'Se activó correctamente']);

    }

    public function obtenerAyudas(){
        $lista = Ayuda::select('id', 'tipo', 'titulo', 'subtitulo', 'descripcion', 'orden', 'imagen');
        $lista->where('activo', true)->orderBy('orden','asc');
        return $lista->get();
    }
}
