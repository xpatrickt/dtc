<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\EvaluacionPregunta;
use App\Models\EvaluacionAlternativa;

class EvaluacionPreguntaController extends Controller
{
    protected $rutaIMGPreguntas = 'images/preguntas/';
    //
     //funciones generales de mantenimiento
     public function ver($id){
        $ver = EvaluacionPregunta::findOrFail($id);
        return $ver;
    }
    
    public function llenarCombo(){
        $select = EvaluacionPregunta::select('id', 'EvaluacionPregunta')->where('activo',true)->get();
        return $select;
    }
   
    public function listar(){
        $lista = EvaluacionPregunta::select()->with([
            'alternativa'
            ]);
        return $lista->get();
    }

    public function listarEvaluacion($id_evaluacion){
        $lista = EvaluacionPregunta::select()
        ->where('id_evaluacion', $id_evaluacion)
        ->with(['alternativa']);
        return $lista->get();
    }

    public function listarEvaluacionMatricula($id_evaluacion, $id_matricula, $id_evaluacion_curso){
        $lista = EvaluacionPregunta::select()
        ->where('id_evaluacion', $id_evaluacion)
        ->with(['alternativa:id,descripcion,id_evaluacion_pregunta','alternativa.respuesta_matricula'=>function($q)use($id_matricula, $id_evaluacion_curso){
            return $q->where('id_matricula', $id_matricula)->where('id_evaluacion_docen_curso', $id_evaluacion_curso);} ]);
        return $lista->get();
    }

    public function crear(Request $request){
        $imagen = $request->imagen;
        $path = '';
        
        if($imagen != 'undefined' || !$imagen ){  
            $extension = $request->file('imagen')->getClientOriginalExtension();
            
            $filename = uniqid();
            
            $file = $request->file('imagen')->move($this->rutaIMGPreguntas, $filename.".".$extension);
            
            $img = \Image::make($this->rutaIMGPreguntas.$filename.".".$extension);
            
            $img->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            
            $img->save($this->rutaIMGPreguntas.$filename.".".$extension);
            $path = $filename.".".$extension;
        }
 
        $pregunta = new EvaluacionPregunta();
        $pregunta->descripcion = $request->descripcion;
        $pregunta->nota = $request->nota;
        $pregunta->imagen = $path;
        $pregunta->id_evaluacion = $request->id_evaluacion;
        $pregunta->save();

        $idPregunta = $pregunta->id;

          

        $alternativaCorrecta = new EvaluacionAlternativa();
        $alternativaCorrecta->descripcion =  $request->alternativa_correcta;  
        $alternativaCorrecta->correcta = true;
        $alternativaCorrecta->id_evaluacion_pregunta = $idPregunta;
        $alternativaCorrecta->save();

        $distractor1 = new EvaluacionAlternativa();
        $distractor1->descripcion =  $request->distractor1;  
        $distractor1->correcta = false;
        $distractor1->id_evaluacion_pregunta = $idPregunta;
        $distractor1->save();
        
        $distractor2 = new EvaluacionAlternativa();
        $distractor2->descripcion =  $request->distractor2;  
        $distractor2->correcta = false;
        $distractor2->id_evaluacion_pregunta = $idPregunta;
        $distractor2->save();

        $distractor3 = new EvaluacionAlternativa();
        $distractor3->descripcion =  $request->distractor3;  
        $distractor3->correcta = false;
        $distractor3->id_evaluacion_pregunta = $idPregunta;
        $distractor3->save();

        $distractor4 = new EvaluacionAlternativa();
        $distractor4->descripcion =  $request->distractor4;  
        $distractor4->correcta = false;
        $distractor4->id_evaluacion_pregunta = $idPregunta;
        $distractor4->save();

        return response()->json(['message' => 'Registro creado correctamente', 'identificador' => $pregunta->id]);
    }

    public function modificar(Request $request, $id){
        $editado = EvaluacionPregunta::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'Registro actualizado correctamente']);
    }

    public function eliminar($id){
        $eliminadoAlternativa = EvaluacionAlternativa::select()
        ->where('id_evaluacion_pregunta', $id);
        $eliminadoAlternativa->delete();
        $eliminado = EvaluacionPregunta::findOrFail($id);
        $eliminado->delete(); 
        return response()->json(['message' => 'Se Elimino correctamente']);

    }

    public function activar($id){
        $activado = EvaluacionPregunta::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);

    }

}
