<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaReunion extends Model
{
    use HasFactory;
    protected $table = 'sala_reunion';
    protected $fillable = [
        'descripcion',
        'url_sala',
        'inicio',
        'fin',
        'id_docen_curso',
        'id_sala',    
    ];
    public function docente_curso(){
        return $this->belongsTo(DocenteCurso::class, 'id_docen_curso', 'id');
    }
}
