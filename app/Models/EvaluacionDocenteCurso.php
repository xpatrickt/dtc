<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionDocenteCurso extends Model
{
    use HasFactory;
    protected $table = 'evaluacion_docente_curso';
    protected $fillable = [
        'inicio',
        'fin',
        'id_docen_curso',
        'id_evaluacion',
    ];
    public function docente_curso(){
        return $this->belongsTo(DocenteCurso::class, 'id_docen_curso', 'id');
    }
    public function evaluacion(){
        return $this->belongsTo(Evaluacion::class, 'id_evaluacion', 'id');
    }
}
