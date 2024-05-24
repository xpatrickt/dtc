<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Persona;
use App\Models\Convocatoria;
use App\Models\Evaluacion;
use App\Models\User;
use App\Models\PersonaConvocatoria;
use App\Models\SedeProvincial;
use Illuminate\Support\Facades\DB;


class EvaluacionController extends Controller
{
    //
     //funciones generales de mantenimiento
     public function ver($dni){
        // $hash= Hash::make($dni);
        // return $hash;
        $persona = Persona::select('id','nombres','apellido_pat','apellido_mat','documento' )->where('documento',$dni)->get();
        $proceso = PersonaConvocatoria::select('id','id_persona','id_convocatoria','id_sede_provincial')
        ->where('id_persona',$persona[0]['id'])->latest('id')->first();
        if ($proceso->id_convocatoria != 3) {
            $cargo = Convocatoria::select('nombre')->where('id',$proceso->id_convocatoria)->first();
        $user = auth()->user();
        $id_region_user = DB::select("select sr.id from sede_regional sr RIGHT JOIN sede_provincial sp on sr.id = sp.id_sede_regional where sp.id=".$user->id_sede_provincial);            
        $id_region_proceso = DB::select("select sr.id from sede_regional sr RIGHT JOIN sede_provincial sp on sr.id = sp.id_sede_regional where sp.id=". $proceso->id_sede_provincial);
        if ($id_region_user==$id_region_proceso) {
            $duplicado = Evaluacion::where('id_persona_convocatoria',$proceso->id)->first();
            if ($duplicado) {
                $provincia = SedeProvincial::where('id',$proceso->id_sede_provincial)->first();
                $resultado = Evaluacion::select('num_registro')->where('id_persona_convocatoria',$proceso->id)->first();
                return response()->json(['message' => 'Ya esta registrado', 'persona' => $persona, 'proceso'=>$proceso,'num_registro'=>$resultado->num_registro,'provincia'=>$provincia, 'cargo' => $cargo->nombre, 'flag'=>0]);
            }
            else {
                $resultado = DB::select("select MAX(num_registro) as max_num_registro from persona_convocatoria pc 
                INNER JOIN evaluacion e ON pc.id = e.id_persona_convocatoria
                WHERE pc.id_convocatoria = ".$proceso->id_convocatoria." and pc.id_sede_provincial = ".$proceso->id_sede_provincial);
                $provincia = SedeProvincial::where('id',$proceso->id_sede_provincial)->first();
                $evaluacion = Evaluacion::create(['id_persona_convocatoria' => $proceso->id, 'num_registro' => $resultado[0]->max_num_registro+1,'estado'=> 0 ,]);
                return response()->json(['message' => 'Se realizo el registro', 'persona' => $persona, 'proceso'=>$proceso,'num_registro'=>$evaluacion->num_registro,'provincia'=>$provincia,'cargo' => $cargo->nombre,'flag'=>1]);
            }
        } 
        else {
            return response()->json(['message' => 'El postulante no petenece a la sede Regional', 'persona' => $persona, 'proceso'=>$proceso,'cargo' => $cargo->nombre,'flag'=>1]);
        }            
        } else {
            # code...
            return response()->json(['message' => 'EL proceso de recepcion de TAP concluyo','flag'=>2]);

        }
        
            
}
    
    public function evaluar($dni,$id_convocatoria){
        $persona = Persona::select('id','nombres','apellido_pat','apellido_mat','documento' )->where('documento',$dni)->get();
        $per_con = PersonaConvocatoria::select('id','id_persona','id_convocatoria','id_sede_provincial')
        ->where('id_persona',$persona[0]['id'])->latest('id')
        ->first();
        if ($id_convocatoria !=3 && $id_convocatoria !=4 && $id_convocatoria !=5 && $id_convocatoria !=6) {
            # code...
            if ($per_con->id_convocatoria == $id_convocatoria) {
                # code...
                $user = auth()->user();
            $id_region_user = DB::select("select sr.id from sede_regional sr RIGHT JOIN sede_provincial sp on sr.id = sp.id_sede_regional where sp.id=".$user->id_sede_provincial);            
            $proceso = DB::select("select e.id as id,pc.id as id_persona_convocatoria,p.documento,CONCAT(p.apellido_pat , ' ' , p.apellido_mat , ' ' , p.nombres) as datos,e.num_registro,rnp,office,certificado_lengua,
            e.profesion,e.grado,e.criterio_cv_1,e.criterio_cv_2,e.criterio_cv_3,e.criterio_cv_4,e.criterio_cv_5,e.criterio_cv_6,e.estado_cv,e.updated_at
            from evaluacion e INNER JOIN persona_convocatoria pc on e.id_persona_convocatoria= pc.id
                                                INNER JOIN persona p ON pc.id_persona=p.id
                                                INNER JOIN sede_provincial sp on sp.id=pc.id_sede_provincial
                                                INNER JOIN sede_regional sr on sp.id_sede_regional=sr.id 
                                                                    WHERE sr.id=" . $id_region_user[0]->id . " and id_convocatoria=" .$per_con->id_convocatoria. " and documento=" .$dni);
            return response()->json(['message' => 'Datos cargados OK', 'data' => $proceso,'flag' => 1]);
            } else {
                # code...
            return response()->json(['message' => 'DNI no pertenece al cargo', 'flag' => 0]);
    
            }
        } else {
            # code...
            return response()->json(['message' => 'El proceso de Evaluacion de CV ya concluyo', 'flag' => 0]);

        }
        
        
        
        
    }
   
    public function listar(){
        $lista = Evaluacion::select()->with([
            'docente:id,id_per', 'docente.persona:id,apellido_pat,apellido_mat,nombres','pregunta'
            ]);
        return $lista->get();
    }

    public function guardar(Request $request){
        $guardar = Evaluacion::findOrFail($request->id);
        $guardar->estado=1; 
        $guardar->update($request->all());
        return response()->json(['message' => 'Guardado correctamente', 'identificador' => $guardar]);
    }

    public function mostrar($id){
        $user = auth()->user();
        $id_region_user = DB::select("select sr.id from sede_regional sr RIGHT JOIN sede_provincial sp on sr.id = sp.id_sede_regional where sp.id=".$user->id_sede_provincial);            
        $mostrar = DB::select("select e.id,pc.id,p.documento,p.apellido_pat,p.apellido_mat,p.nombres,e.num_registro,sp.nombre_sede as provincia, sr.nombre_sede as region,e.created_at
        from evaluacion e INNER JOIN persona_convocatoria pc on e.id_persona_convocatoria= pc.id
                                            INNER JOIN sede_provincial sp on pc.id_sede_provincial=sp.id
                                            INNER JOIN sede_regional sr on sp.id_sede_regional=sr.id
                                            INNER JOIN persona p ON pc.id_persona=p.id 
                                                                WHERE ((".$id_region_user[0]->id." != 1 and sr.id=" . $id_region_user[0]->id . ") or ".$id_region_user[0]->id." = 1) and id_convocatoria=".$id);
        return $mostrar;
    }
    public function mostrarReporte($id){
        $user = auth()->user();
        $id_region_user = DB::select("select sr.id from sede_regional sr RIGHT JOIN sede_provincial sp on sr.id = sp.id_sede_regional where sp.id=".$user->id_sede_provincial);            
        switch ($id) {
            case 1:
                # code...
                $mostrar = DB::select("select e.id as id,pc.id as id_persona_convocatoria,p.documento,CONCAT(p.apellido_pat , ' ' , p.apellido_mat , ' ' , p.nombres) as datos,e.num_registro,e.rnp,e.office,e.certificado_lengua,
                    p.profesion as per_profesion,e.profesion as eva_profesion,e.grado,e.criterio_cv_1,e.criterio_cv_2,e.criterio_cv_3,e.criterio_cv_4,e.criterio_cv_5,e.criterio_cv_6,e.estado_cv, e.created_at,sp.nombre_sede as provincia, sr.nombre_sede as region
                    from evaluacion e INNER JOIN persona_convocatoria pc on e.id_persona_convocatoria= pc.id
                        INNER JOIN sede_provincial sp on pc.id_sede_provincial=sp.id
                        INNER JOIN sede_regional sr on sp.id_sede_regional=sr.id
                        INNER JOIN persona p ON pc.id_persona=p.id 
                        WHERE sr.id=" . $id_region_user[0]->id . " and id_convocatoria=" .$id ." and e.estado=" . 1);
                return $mostrar;
                break;
            case 2:
                # code...
                break;
            case 3:
                # code...
                $mostrar = DB::select("select e.id as id,pc.id as id_persona_convocatoria,p.documento,CONCAT(p.apellido_pat , ' ' , p.apellido_mat , ' ' , p.nombres) as datos,p.fecha_nac, p.grado as per_grado,e.num_registro,rnp,office,
                    p.profesion as per_profesion,e.profesion as eva_profesion,e.grado,e.criterio_cv_1,e.criterio_cv_2,e.criterio_cv_3,e.criterio_cv_4,e.criterio_cv_5,e.criterio_cv_6,e.estado_cv, e.created_at,sp.nombre_sede as provincia, sr.nombre_sede as region
                    from evaluacion e INNER JOIN persona_convocatoria pc on e.id_persona_convocatoria= pc.id
                        INNER JOIN sede_provincial sp on pc.id_sede_provincial=sp.id
                        INNER JOIN sede_regional sr on sp.id_sede_regional=sr.id
                        INNER JOIN persona p ON pc.id_persona=p.id 
                        WHERE ((".$id_region_user[0]->id." != 1 and sr.id=" . $id_region_user[0]->id . ") or ".$id_region_user[0]->id." = 1) and id_convocatoria=" .$id ." and e.estado=" . 1);
                return $mostrar;
                break;
            case 4:
                # code...
                $mostrar = DB::select("select e.id as id,pc.id as id_persona_convocatoria,p.documento,CONCAT(p.apellido_pat , ' ' , p.apellido_mat , ' ' , p.nombres) as datos,p.fecha_nac, p.grado as per_grado,e.num_registro,rnp,office,
                    p.profesion as per_profesion,e.profesion as eva_profesion,e.grado,e.criterio_cv_1,e.criterio_cv_2,e.criterio_cv_3,e.criterio_cv_4,e.criterio_cv_5,e.criterio_cv_6,e.estado_cv, e.created_at,sp.nombre_sede as provincia, sr.nombre_sede as region
                    from evaluacion e INNER JOIN persona_convocatoria pc on e.id_persona_convocatoria= pc.id
                        INNER JOIN sede_provincial sp on pc.id_sede_provincial=sp.id
                        INNER JOIN sede_regional sr on sp.id_sede_regional=sr.id
                        INNER JOIN persona p ON pc.id_persona=p.id 
                        WHERE ((".$id_region_user[0]->id." != 1 and sr.id=" . $id_region_user[0]->id . ") or ".$id_region_user[0]->id." = 1) and id_convocatoria=" .$id ." and e.estado=" . 1);
                return $mostrar;
                break;
            case 5:
                # code...
                $mostrar = DB::select("select e.id as id,pc.id as id_persona_convocatoria,p.documento,CONCAT(p.apellido_pat , ' ' , p.apellido_mat , ' ' , p.nombres) as datos,p.fecha_nac, p.grado as per_grado,e.num_registro,rnp,office,
                    p.profesion as per_profesion,e.profesion as eva_profesion,e.grado,e.criterio_cv_1,e.criterio_cv_2,e.criterio_cv_3,e.criterio_cv_4,e.criterio_cv_5,e.criterio_cv_6,e.estado_cv, e.created_at,sp.nombre_sede as provincia, sr.nombre_sede as region
                    from evaluacion e INNER JOIN persona_convocatoria pc on e.id_persona_convocatoria= pc.id
                        INNER JOIN sede_provincial sp on pc.id_sede_provincial=sp.id
                        INNER JOIN sede_regional sr on sp.id_sede_regional=sr.id
                        INNER JOIN persona p ON pc.id_persona=p.id 
                        WHERE ((".$id_region_user[0]->id." != 1 and sr.id=" . $id_region_user[0]->id . ") or ".$id_region_user[0]->id." = 1) and id_convocatoria=" .$id ." and e.estado=" . 1);
                return $mostrar;
                break;
            case 6:
                # code...
                $mostrar = DB::select("select e.id as id,pc.id as id_persona_convocatoria,p.documento,CONCAT(p.apellido_pat , ' ' , p.apellido_mat , ' ' , p.nombres) as datos,p.fecha_nac, p.grado as per_grado,e.num_registro,rnp,office,
                    p.profesion as per_profesion,e.profesion as eva_profesion,e.grado,e.criterio_cv_1,e.criterio_cv_2,e.criterio_cv_3,e.criterio_cv_4,e.criterio_cv_5,e.criterio_cv_6,e.estado_cv, e.created_at,sp.nombre_sede as provincia, sr.nombre_sede as region
                    from evaluacion e INNER JOIN persona_convocatoria pc on e.id_persona_convocatoria= pc.id
                        INNER JOIN sede_provincial sp on pc.id_sede_provincial=sp.id
                        INNER JOIN sede_regional sr on sp.id_sede_regional=sr.id
                        INNER JOIN persona p ON pc.id_persona=p.id 
                        WHERE ((".$id_region_user[0]->id." != 1 and sr.id=" . $id_region_user[0]->id . ") or ".$id_region_user[0]->id." = 1) and id_convocatoria=" .$id ." and e.estado=" . 1);
                return $mostrar;
                break;
        }
        
    }

   

    public function activar($id){
        $activado = Evaluacion::findOrFail($id);
        $activado->activo = true;
        $activado->save(); 
        return response()->json(['message' => 'Se activó correctamente']);
    }

    public function getDateTimeActual(){
        $fechaActual = date('Y-m-d');
        $horaActual = date('H:i:s');
        return response()->json(compact('fechaActual','horaActual'));
    }

}
