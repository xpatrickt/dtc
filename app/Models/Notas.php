<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notas extends Model
{
    use HasFactory;
    protected $table = 'notas';
    protected $fillable = [
        'nota',
        'observaciones',  
        'id_matric',
        'id_docen_curso',  
        'id_crit',   
    ];
    public function matricula(){
        return $this->belongsTo(Matricula::class, 'id_matric', 'id');
    }
    public function docente_curso(){
        return $this->belongsTo(DocenteCurso::class, 'id_docente_curso', 'id');
    }
    public function criterio(){
        return $this->belongsTo(Criterio::class, 'id_crit', 'id');
    }
}
