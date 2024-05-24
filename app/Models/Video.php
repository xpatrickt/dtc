<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $table = 'video';
    protected $fillable = [
        'nombre',
        'autor',
        'descripcion',
        'url',
        'subido',
        'id_grado_cur'      
    ];
    public function grado_curso(){
        return $this->belongsTo(GradoCurso::class, 'id_grado_cur', 'id');
    }
}
