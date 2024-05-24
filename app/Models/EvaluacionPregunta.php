<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionPregunta extends Model
{
    use HasFactory;
    protected $table = 'evaluacion_pregunta';
    protected $fillable = [
        'descripcion',
        'nota',
        'imagen',
        'id_evaluacion',
    ];
    public function evaluacion(){
        return $this->belongsTo(Docente::class, 'id_evaluacion', 'id');
    }
    public function alternativa(){
        return $this->hasMany(EvaluacionAlternativa::class, 'id_evaluacion_pregunta','id');
    }
}
