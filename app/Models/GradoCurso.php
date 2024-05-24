<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradoCurso extends Model
{
    use HasFactory;
    protected $table = 'grado_curso';
    protected $fillable = [
        'id_grado',
        'id_curso',       
    ];
    public function grado(){
        return $this->belongsTo(Grado::class, 'id_grado', 'id');
    }
    public function curso(){
        return $this->belongsTo(Curso::class, 'id_curso', 'id');
    }
    public function docente_curso(){
        return $this->belongsTo(DocenteCurso::class, 'id_curso', 'id_curso');
    }
}
