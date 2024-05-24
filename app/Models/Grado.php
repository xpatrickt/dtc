<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    use HasFactory;
    protected $table = 'grado';
    protected $fillable = [
        'grado',
        'id_nivel',       
    ];
    public function nivel(){
        return $this->belongsTo(Nivel::class, 'id_nivel', 'id');
    }

    public function seccion(){
        return $this->hasMany(Seccion::class, 'id_grado','id');
    }

    
}
