<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

class BuscarContenidoPeligroso extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:contenido_peligroso';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca contenido peligroso en el sistema básicamente en los contenidos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $queryPalabras = "select distinct(palabra) from t_palabras_betadas where activo = true order by palabra asc;";
            $palabras = DB::select($queryPalabras);
            foreach($palabras as $palabra){
                $q = "update t_preguntas set es_peligroso = true where cuerpo ilike '%".$palabra->palabra."%' or titulo ilike  '%".$palabra->palabra."%';";
                $contenido = DB::update($q);
            }
        DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }
}
