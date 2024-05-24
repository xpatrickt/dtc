<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencia';
    protected $fillable = [
        'id_persona_convocatoria',
        'local_capacitacion',
        'aula',
        'hora_tablet',
    ];
        
    public function grado_curso(){
        return $this->belongsTo(PersonaConvocatoria::class, 'id_persona_convocatoria', 'id');
    }
}
