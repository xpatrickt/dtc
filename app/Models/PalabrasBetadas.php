<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PalabrasBetadas extends Model
{
    use HasFactory;

    protected $table = 't_palabras_betadas';
    protected $fillable = [
        'palabra',
        'descripcion',
        'activo',
    ];
}
