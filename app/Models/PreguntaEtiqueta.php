<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreguntaEtiqueta extends Model
{
    use HasFactory;

    protected $table = 't_pregunta_etiqueta';
    protected $fillable = [
        'pregunta_id',
        'etiqueta_id',
    ];


    public function etiqueta(){
        return $this->belongsTo(Etiqueta::class);
    }
}
