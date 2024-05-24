<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Bandeja;
use App\Models\User;
use App\Services\HelperServices;

class BandejaController extends Controller
{
    public function crear(Request $request){
        $nuevo = new Bandeja();       
        $nuevo->usuario_1 = auth()->user()->id;
        $nuevo->usuario_2 = $request->usuario_2;
        $nuevo->mensaje = $request->mensaje;
        $nuevo->save();
        if (auth()->user()->es_admin == true){
            HelperServices::registrarContadorCampo('mensajes', $request->usuario_2);
        }
        return response()->json(['message' => 'Mensaje enviado correctamente', 'identificador' => $nuevo->id]);
    }

    public function inactivar($id){
        $inactivado = Bandeja::findOrFail($id);
        $inactivado->activo = 0 ;
        $inactivado->save(); 
        return response()->json(['message' => 'Se inactivó correctamente']);

    }

    public function listarMensajesUsuario(){
        $usuario_1 = auth()->user()->id;
        $query = "select * from t_bandejas where (usuario_1  = ".$usuario_1." or usuario_2 = ".$usuario_1.")";
        $lista = DB::select($query);
        HelperServices::reiniciarContadorCampo('mensajes'); //reinicia el contador
        return $lista;
    }

    public function listarMensajesAdmin(Request $request, $id){
        $query = "select * from t_bandejas where (usuario_1  = ".$id." or usuario_2 = ".$id.")";
        $lista = DB::select($query);
        return $lista;
    }

    public function listarBandejaAdmin(){
        $query = "SELECT 
        b.id,
        b.mensaje,
        b.created_at,
        u1.id as id_usuario1,
        concat(u1.nombres ,' ', u1.apellidos) as nombre_usuario1,
        u1.avatar as avatar_usuario1,
		u1.es_admin as es_admin_usuario1,
				
        u2.id as id_usuario2,
        concat(u2.nombres ,' ', u2.apellidos) as nombre_usuario2,
        u2.avatar as avatar_usuario2,
        u2.es_admin as es_admin_usuario2
				
        FROM (select usuario_1, usuario_2, max(id) as id from t_bandejas where activo = true GROUP BY 1,2) r 
        LEFT JOIN t_bandejas b on b.id = r.id 
        LEFT JOIN users u1 on u1.id = r.usuario_1
        LEFT JOIN users u2 on u2.id = r.usuario_2
        order by b.created_at desc;";
        $lista = DB::select($query);
        return $lista;
    }

}
