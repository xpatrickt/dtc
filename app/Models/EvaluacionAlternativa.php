<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionAlternativa extends Model
{
    use HasFactory;
    protected $table = 'evaluacion_alternativa';
    protected $fillable = [
        'descripcion',
        'correcta',
        'id_evaluacion_pregunta',
    ];
    public function evaluacion_pregunta(){
        return $this->belongsTo(EvaluacionPregunta::class, 'id_evaluacion_pregunta', 'id');
    }
    public function respuesta_matricula(){
        return $this->hasMany(EvaluacionRespuestaMatricula::class, 'id_evaluacion_alter','id');
    }
}
