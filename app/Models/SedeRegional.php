<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SedeRegional extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'sede_regional';
    protected $fillable = [
            'nombre_sede',
            'codigo_sede',
    ];
}
