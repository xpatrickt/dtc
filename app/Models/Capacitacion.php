<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capacitacion extends Model
{
    use HasFactory;
    protected $table = 'capacitacion';
    protected $fillable = [
        
        'id_persona_convocatoria',
        'cap_c1',
        'cap_c2',
        'cap_c3',
        'cap_c4',
        'cap_c5',
        'cap_c6',
        'cap_c7',
        'cap_c8',
        'asiste_d1',
        'asiste_d2',
        'asiste_d3',
        'asiste_d4',
        'asiste_d5',
        'suma_total1',
        'suma_total2',
        'estado_capa1',
        'estado_capa2',
        'observacion',
        'condicion',
        'estado',
        'aula' ,
        'ponderado',
            'estado_capa_total'
];
    public function personaConvocatoria(){
        return $this->belongsTo(PersonaConvocatoria::class, 'id_persona_convocatoria', 'id');
    }
}
