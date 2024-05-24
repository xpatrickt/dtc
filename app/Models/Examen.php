<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;
    protected $table = 'examen';
    protected $fillable = [
        
    'id_persona_convocatoria', 
    'total_fase1', 
    'nota_examen', 
    'ponderado1', 
    'ponderado2', 
    'ponderado3', 
    'fase2_ponderado', 
    'estado', 
];
    public function personaConvocatoria(){
        return $this->belongsTo(PersonaConvocatoria::class, 'id_persona_convocatoria', 'id');
    }
}
