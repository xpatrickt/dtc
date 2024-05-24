<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;
    protected $table = 'evaluacion';
    protected $fillable = [
        
            'id_persona_convocatoria',
            'rnp',
            'office',
            'certificado_lengua',
            'profesion',
            'grado',
            'criterio_cv_1',
            'criterio_cv_2',
            'criterio_cv_3',
            'criterio_cv_4',
            'criterio_cv_5',
            'criterio_cv_6',
            'ponderado_1',
            'ponderado_2',
            'ponderado_3',
            'ponderado_4',
            'ponderado_5',
            'ponderado_6',
            'total_ponderado',
            'estado_cv',
            'estado',
            'resultado_cv',
            'num_registro'
    ];
    public function personaConvocatoria(){
        return $this->belongsTo(PersonaConvocatoria::class, 'id_persona_convocatoria', 'id');
    }
}
