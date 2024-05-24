<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioExperiencia extends Model
{
    use HasFactory;

    protected $table = 't_usuario_experiencia';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'etiqueta_id',
    ];
    
    public function etiqueta(){
        return $this->belongsTo(Etiqueta::class);
    }
}
