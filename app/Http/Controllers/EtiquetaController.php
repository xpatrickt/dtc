<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HelperServices;
use Illuminate\Support\Facades\DB;


use App\Models\Etiqueta;
use App\Models\User;

class EtiquetaController extends Controller
{
    //funciones generales de mantenimiento
    public function ver($id){
        $ver = Etiqueta::findOrFail($id);
        return $ver;
    }

    public function llenarCombo(){
        $select = Etiqueta::select('id', 'nombre', 'nivel', 'padre_id')->where('activo',true)->get();
        return $select;
    }

    public function crear(Request $request){
        $existe = Etiqueta::where('url' , $request->url)->first();
        if($existe){
            $request->merge(['url' => $request->url . random_int(100, 999)]);
        }            
        $nuevo = new Etiqueta($request->all());
        $nuevo->save();
        return response()->json(['message' => 'La etiqueta se creó correctamente', 'identificador' => $nuevo->id]);
    }

    public function modificar(Request $request, $id){
        $editado = Etiqueta::findOrFail($id);
        $editado->update($request->all());
        return response()->json(['message' => 'La etiqueta se actualizó correctamente']);
    }

    public function inactivar($id){
        $inactivado = Etiqueta::findOrFail($id);
        $inactivado->activo = false;
        $inactivado->save();
        return response()->json(['message' => 'Se inactivó correctamente']);

    }

    public function activar($id){
        $activado = Etiqueta::findOrFail($id);
        $activado->activo = true;
        $activado->save();
        return response()->json(['message' => 'Se activó correctamente']);

    }

    // personalzdas

    public function listarBasico(Request $request){
        $lista = Etiqueta::select('id', 'nombre','url')->where('activo', true);
        if($request->nivel)
            $lista->where('nivel', 1);
        return $lista->get();
    }


    public function listarTemasRubro(Request $request){
        HelperServices::contadorPuntaje(auth()->user()->id, 'abrir_etiquetas');//puntaje
        return Etiqueta::select('id','padre_id','nombre','url')
        ->with(
            ['hijos' => function ($query) {
                $query->select('id','padre_id','nombre','url')->where('activo', true);
            }, 'hijos.hijos' => function ($query) {
                $query->select('id','padre_id','nombre','url')->where('activo', true);
            }])
        ->where('url',$request->etiqueta)->first();

        return HelperServices::prepararEtiquetasJson($request->etiqueta);


        $lista = Etiqueta::select('id', 'nombre','url','contador', 'porcentaje')->where('activo', true);
        if($request->nivel)
            $lista->where('nivel', 1);
        return $lista->get();
    }

    public function listarRubrosCriterio(Request $request){

        // HelperServices::calcularTotales();
         $lista = DB::select('
         select E.id, E.nivel, E.padre_id,  E.nombre, E.url, coalesce(T1.sum, 0) as sum
         from t_etiquetas E left JOIN ( select etiqueta_id, sum(1) as sum  from t_pregunta_etiqueta GROUP BY 1) T1 on E.id = T1.etiqueta_id
         where activo = true;
         ');
         return $lista;
     }

    public function listarTemasCriterio(Request $request){
              // HelperServices::calcularTotales();
        HelperServices::contadorPuntaje(auth()->user()->id, 'buscar_etiquetas');//puntaje
        $query = '
        select T1.id, T1.nivel, T1.padre_id, T1.nombre, T1.url, T1.descripcion, T1.sum as subtemas, T2.sum as actividad_hoy, T3.sum as actividad_sem
        from(
            select E.id, E.nivel,E.padre_id, E.nombre, E.url, E.descripcion, coalesce(T1.sum, 0) as sum  from t_etiquetas E
            left join ( select padre_id, sum(1) sum from t_etiquetas GROUP BY 1  ) T1
            on E.id = T1.padre_id) T1
        left join (
            select E.id, coalesce(T1.sum, 0) as sum
            from t_etiquetas E left JOIN ( select etiqueta_id, sum(1) as sum  from t_pregunta_etiqueta where created_at::date = now()::date GROUP BY 1) T1 on E.id = T1.etiqueta_id
            where activo = true) T2 on T1.id = T2.id
        LEFT JOIN	(
            select E.id, coalesce(T1.sum, 0) as sum
            from t_etiquetas E left JOIN ( select etiqueta_id, sum(1) as sum  from t_pregunta_etiqueta where created_at > (now()-concat(extract(dow from  now())-1,\' day\')::interval)::date GROUP BY 1) T1 on E.id = T1.etiqueta_id
            where activo = true) T3 on T2.id = T3.id
        where T1.nivel > 1';
        if($request->tema){
            $query .= ' and t1.padre_id = ' . $request->tema;
        }
        if($request->opcion == 'paraMi'){
            $intereses = auth()->user()->intereses;
            $ids = [];
            foreach($intereses as $row){
                array_push($ids, $row->id);
            }
            $strIds=implode(',',$ids);
            $query .= ' and (t1.padre_id in (' . $strIds . ') or T1.id in ('.$strIds.') )';
        }
        if($request->opcion == 'siguiendo'){
            $siguiendo = User::where('id',auth()->user()->id);
            $siguiendo->with(
                ['seguidores_rel',
                'seguidores_rel.etiquetas_seguidor']);
            $etiquetasSiguiendo = $siguiendo->first();
            $ids = [];
            foreach($etiquetasSiguiendo->seguidores_rel as $row){
                foreach($row->etiquetas_seguidor as $row2){
                    array_push($ids, $row2->etiqueta_id);
                }
            }
            if(count($ids) == 0){
                return [];
            }
            $strIds=implode(',',$ids);
            $query .= ' and (t1.padre_id in (' . $strIds . ') or T1.id in ('.$strIds.') )';
        }
        $lista = DB::select($query);

        return $lista;
    }

    public function listarSugerencias(Request $request){
        $q = $request->texto;
        $lista = DB::select("
        select * from t_etiquetas where nombre ilike '%".$q."%';
        ");

        return $lista;
    }

    public function listarTendenciaRubros(){
        $lista = DB::select("
        select E.id, E.nombre, E.url, coalesce(T1.sum, 0) as sum
        from t_etiquetas E left JOIN (
            select etiqueta_id, sum(1) as sum
            from t_pregunta_etiqueta where created_at > (now()-' 5 day'::interval)::date
            GROUP BY 1
            ) T1 on E.id = T1.etiqueta_id
        where E.activo = true and t1.sum > 0 and E.nivel = 1
        order by t1.sum desc limit 5;
        ");
        return $lista;
    }

    // temas 

    public function listar(Request $request){
        $lista = Etiqueta::select('id','padre_id','nombre','url','nivel','activo','created_at');
        if($request->nivel)
            $lista->where('nivel', $request->nivel);

        return $lista->get();
    }
}
