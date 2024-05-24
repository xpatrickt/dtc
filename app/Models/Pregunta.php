<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $table = 't_preguntas';
    protected $fillable = [
        'user_id',
        'pregunta_id',
        'titulo',
        'cuerpo',
        'me_gusta',
        'no_gusta',
        'denunciado',
        'verificado',
        'tipo',
        'c_respuestas',
    ];


    public function usuario(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function etiquetas(){
        return $this->belongsToMany(Etiqueta::class, 't_pregunta_etiqueta');
    }
    public function usuarios(){
        return $this->belongsToMany(User::class, 't_pregunta_usuario', 'pregunta_id', 'user_id');
    }

    public function preguntas_etiquetas(){
        return $this->hasMany(PreguntaEtiqueta::class);
    }

    public function preguntas_usuarios(){
        return $this->hasMany(PreguntaUsuario::class);
    }

    public function pregunta(){
        return $this->belongsTo(Pregunta::class,'pregunta_id','id');
    }

    public function respuestas(){
        return $this->hasMany(Pregunta::class, 'pregunta_id','id')->orderBy('id', 'desc');
    }


    public function calificaciones(){
        return $this->hasMany(CalificacionPregunta::class);
    }

    public function calificaciones_usuario(){
        return $this->hasMany(CalificacionPregunta::class)->where('user_id', auth()->user()->id);
    }

}
