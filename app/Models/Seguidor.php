<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguidor extends Model
{
    use HasFactory;
    protected $table = 't_seguidores';


    protected $fillable = [
        'user_id',
        'user_id_seguir',
    ];

    public function etiquetas_seguidor(){
        return $this->hasMany(UsuarioInteres::class, 'user_id', 'user_id_seguir');
    }

}
