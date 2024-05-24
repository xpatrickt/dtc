<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreguntaUsuario extends Model
{
    use HasFactory;

    protected $table = 't_pregunta_usuario';
    protected $fillable = [
        'pregunta_id',
        'user_id',
    ];

}
