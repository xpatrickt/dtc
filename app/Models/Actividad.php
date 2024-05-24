<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;
    protected $table = 'actividad';
    protected $fillable = [
        'nombre',
        'id_gracur'
    ];
    public function grado_curso(){
    return $this->belongsTo(GradoCurso::class, 'id_gracur', 'id');
}
}

