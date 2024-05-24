<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    use HasFactory;
    protected $table = 't_ranking';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'login_linkedin',
        'abrir_publicacion',
        'abrir_pregunta',
        'buscar_usuarios',
        'buscar_publicaciones',
        'buscar_preguntas',
        'buscar_respuestas',
        'aumentar_seguidor',
        'buscar_etiquetas',
        'abrir_etiquetas',
        'crear_publicacion',
        'crear_pregunta',
        'crear_respuesta',
        'crear_comentario',
        'mencion_util',
        'testimonio',
        'me_gusta_publicacion',
        'me_gusta_pregunta',
        'me_gusta_comentario',
        'me_gusta_respuesta',
        'recibe_gusta_comentario',
        'recibe_gusta_respuesta',
        'testimonio_positivo',
        'denunciar',
        'moderar',
        'ayudar_solucion_existente',
        'enviar_inbox',
        'editar_publicacion',
        'reporte_confirmado',
        'no_gusta',
        'testimonio_negativo',
        'total',
    ];
}
