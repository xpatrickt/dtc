<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioInteres extends Model
{
    use HasFactory;

    protected $table = 't_usuario_interes';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'etiqueta_id',
    ];
    
    public function etiqueta(){
        return $this->belongsTo(Etiqueta::class);
    }
}
