<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'usuario',
        'ult_visita',
        'provider',
        'provider_id',
        'provider_token',
        'provider_expires_in',
        'id_sede_provincial',
    
        'activo',
        'es_admin',
        'tipo_usuario',
        'id_persona',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function persona(){
    //     return $this->belongsTo(Persona::class, 'id_persona', 'id');
    // }

    public function persona(){
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }
    
    public function experiencias(){
        return $this->hasMany(UsuarioExperiencia::class);
    }

    public function experiencias_et(){
        return $this->belongsToMany(Etiqueta::class, 't_usuario_experiencia');
    }

    public function intereses(){
        return $this->hasMany(UsuarioInteres::class);
    }

    public function intereses_et(){
        return $this->belongsToMany(Etiqueta::class, 't_usuario_interes');
    }

    public function preguntas(){
        return $this->hasMany(Pregunta::class);
    }

    public function seguidores_rel(){
        return $this->hasMany(Seguidor::class);
    }

    public function ranking(){
        return $this->hasOne(Ranking::class);
    }
}
