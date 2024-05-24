<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalificacionPregunta extends Model
{
    use HasFactory;

    protected $table = 't_calificacion_pregunta';
    protected $fillable = [
        'user_id',
        'pregunta_id',
        'tipo',
        'reciente',
    ];
}
