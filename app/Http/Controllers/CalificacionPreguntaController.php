<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\HelperServices;

use App\Models\Pregunta;
use App\Models\CalificacionPregunta;


class CalificacionPreguntaController extends Controller
{
    public function meGusta(Request $request){
        try {
            DB::beginTransaction();
            $calificacion_id = null;
            $accion = 'sumar';
            $existe = CalificacionPregunta::where('user_id', auth()->user()->id)->where('pregunta_id', $request->pregunta_id)->whereIn('tipo',[1,2])->first();
            if($existe){
                if($existe->tipo == 1){//
                    $existe->delete(); //eliminar
                    HelperServices::quitarMeGusta($request->pregunta_id, $request->preguntaCreadorId);
                    $accion = 'restar';
                }
                elseif($existe->tipo == 2){
                    $existe->tipo = 1;//like
                    $existe->save();
                    HelperServices::aumentarMeGusta($request->pregunta_id, $request->preguntaCreadorId);
                    HelperServices::quitarNoMeGusta($request->pregunta_id, $request->preguntaCreadorId);
                    $accion = 'cambiar';
                }

                $calificacion_id = $existe->id;
            }
            else{
                $new = new CalificacionPregunta();
                $new->user_id = auth()->user()->id;
                $new->pregunta_id = $request->pregunta_id;
                $new->tipo = 1;
                $new->save();
                $calificacion_id = $new->id;
                HelperServices::aumentarMeGusta($request->pregunta_id, $request->preguntaCreadorId);
            }

            DB::commit();
            return response()->json(['message' => 'Se registró el me gusta.', 'accion' => $accion, 'identificador' => $calificacion_id]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Tuvimos un problema inténtelo denuevo más tarde']);
        }
    }

    public function noMeGusta(Request $request){
        try {
            DB::beginTransaction();
            $calificacion_id = null;
            $accion = 'sumar';//acciones sumar 2 restar 9 sumar y quitar
            $existe = CalificacionPregunta::where('user_id', auth()->user()->id)->where('pregunta_id', $request->pregunta_id)->whereIn('tipo',[1,2])->first();
            if($existe){
                if($existe->tipo == 1){
                    $existe->tipo = 2;//dislike
                    $existe->save();
                    HelperServices::quitarMeGusta($request->pregunta_id, $request->preguntaCreadorId);
                    HelperServices::aumentarNoMeGusta($request->pregunta_id, $request->preguntaCreadorId);
                    $accion = 'cambiar';
                }
                elseif($existe->tipo == 2){
                    $existe->delete();
                    HelperServices::quitarNoMeGusta($request->pregunta_id, $request->preguntaCreadorId);
                    $accion = 'restar';
                }
                $calificacion_id = $existe->id;
            }
            else{
                $new = new CalificacionPregunta();
                $new->user_id = auth()->user()->id;
                $new->pregunta_id = $request->pregunta_id;
                $new->tipo = 2;
                $new->save();
                $calificacion_id = $new->id;
                HelperServices::aumentarNoMeGusta($request->pregunta_id, $request->preguntaCreadorId);
            }

            DB::commit();
            return response()->json(['message' => 'Se registró correctamente.', 'accion' => $accion, 'identificador' => $calificacion_id]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Tuvimos un problema inténtelo denuevo más tarde']);
        }
    }

    public function denunciado(Request $request){
        try {
            DB::beginTransaction();
            $calificacion_id = null;
            $accion = 'sumar';//acciones sumar 2 restar 9 sumar y quitar
            $existe = CalificacionPregunta::where('user_id', auth()->user()->id)->where('pregunta_id', $request->pregunta_id)->where('tipo',3)->first();
            if($existe){
                $existe->delete(); // elimar el registro
                HelperServices::quitarDenunciado($request->pregunta_id, $request->preguntaCreadorId);
                $accion = 'restar';
            }
            else{
                $new = new CalificacionPregunta();
                $new->user_id = auth()->user()->id;
                $new->pregunta_id = $request->pregunta_id;
                $new->tipo = 3;
                $new->save();
                $calificacion_id = $new->id;
                HelperServices::aumentarDenunciado($request->pregunta_id, $request->preguntaCreadorId);
            }

            DB::commit();
            return response()->json(['message' => 'Se registró correctamente.', 'accion' => $accion, 'identificador' => $calificacion_id]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Tuvimos un problema inténtelo denuevo más tarde']);
        }
    }


}
