<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;
    protected $table = 'matricula_estudiante';
    protected $fillable = [
        'anio',
        'observaciones',
        'id_est',
        'id_sec'      
    ];
    public function estudiante(){
        return $this->belongsTo(Alumno::class, 'id_est', 'id');
    }
    public function seccion(){
        return $this->belongsTo(Seccion::class, 'id_sec', 'id');
    }
}
