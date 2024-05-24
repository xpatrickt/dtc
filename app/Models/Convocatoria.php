<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    use HasFactory;
    protected $table = 'convocatoria';
    protected $fillable = [
            'id_cargo',
            'codigo',
            'nombre',
            'nivel'
    ];
    public function grado_curso(){
    return $this->belongsTo(GradoCurso::class, 'id_gracur', 'id');
}
}

