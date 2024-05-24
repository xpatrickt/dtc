<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'persona';
    protected $fillable = [
            'nombres',
            'apellido_pat',
            'apellido_mat',
            'sexo',
            'telefono2',
            'fecha_nac',
            'documento',
            'direccion',
    ];
    public function user(){
        return $this->hasOne(User::class, 'id_persona','id');
    }
    public function docente(){
        return $this->hasOne(PersonaConvocatoria::class, 'id_per','id');
    }
    
}
