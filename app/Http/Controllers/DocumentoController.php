<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;

class DocumentoController extends Controller
{
    //
    public function listar(){
        $lista = Documento::select();
        return $lista->get();
    }
}
