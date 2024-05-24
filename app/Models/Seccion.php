<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;
    protected $table = 'seccion';
    protected $fillable = [
        'seccion',
        'id_grado',       
    ];
    public function grado(){
        return $this->belongsTo(Grado::class, 'id_grado', 'id');
    }

    public function docente_curso(){
        return $this->hasMany(DocenteCurso::class, 'id_seccion','id');
    }
}
