<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SedeProvincial extends Model
{
    use HasFactory;
    protected $table = 'sede_provincial';
    protected $fillable = [
            'nombre_sede',
            'codigo_sede',
            'id_sede_regional'
    ];

    public function sede_regional(){
        return $this->belongsTo(SedeRegional::class, 'id_sede_regional', 'id');
    }
    
}
