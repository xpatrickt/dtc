<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionRespuestaMatricula extends Model
{
    use HasFactory;
    protected $table = 'evaluacion_respuesta_matricula';
    protected $fillable = [
        'envio',
        'id_evaluacion_alter',
        'id_matricula',
        'id_evaluacion_docen_curso',
    ];
    public function evaluacion_alternativa(){
        return $this->belongsTo(EvaluacionAlternativa::class, 'id_evaluacion_alter', 'id');
    }
    public function matricula(){
        return $this->belongsTo(Matricula::class, 'id_matricula', 'id');
    }
    public function evaluacion_docente_curso(){
        return $this->belongsTo(EvaluacionDocenteCurso::class, 'id_evaluacion_docen_curso', 'id');
    }
}
