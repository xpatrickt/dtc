<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examen;
use App\Models\Persona;
use App\Models\Evaluacion;
use Illuminate\Support\Facades\DB;


class ExamenController extends Controller
{
    
    public function generar(Request $request)
    {   
        $examen = DB::select("select e.id, per.documento, per.apellido_pat, per.apellido_mat, per.nombres, e.nota_examen
        FROM examen e INNER JOIN persona_convocatoria pc on e.id_persona_convocatoria = pc.id
            INNER JOIN persona per on pc.id_persona = per.id
                WHERE pc.id_convocatoria =".$request->convocatoria." ORDER BY per.apellido_pat");
        return $examen;

    }

    public function rankear(Request $request)
    {   
        $ranking = DB::select("update examen
        SET fase2_ponderado = (nota_examen * 0.4) + (total_fase1 * 0.55) + (ponderado1 * 0.05)
        WHERE id_persona_convocatoria IN (
            SELECT id 
            FROM persona_convocatoria 
            WHERE id_sede_provincial = ".$request->provincia." and id_convocatoria = ".$request->convocatoria." );");
            return response()->json(['message' => 'Se Rankeo a los postulantes solicitados',]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reporte(Request $request)
    {
        $examen = DB::select("select e.id, per.documento, per.apellido_pat, per.apellido_mat, per.nombres, e.nota_examen, e.total_fase1,e.ponderado1
        FROM examen e INNER JOIN persona_convocatoria pc on e.id_persona_convocatoria = pc.id
            INNER JOIN persona per on pc.id_persona = per.id
                WHERE pc.id_convocatoria =".$request->convocatoria." and pc.id_sede_provincial = ".$request->provincia." ORDER BY per.apellido_pat");
        return $examen;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function modificar($id,Request $request)
    {
        $editarnota = Examen::findOrFail($id);        
        $editarnota->update(['nota_examen'=>$request->nota]);
        return $editarnota;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
