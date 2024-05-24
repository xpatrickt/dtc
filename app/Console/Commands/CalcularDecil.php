<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use App\Services\HelperServices;

class CalcularDecil extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calc:decil_usuarios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcula los deciles del sistema de los susuarios';

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
            $rankings = \App\Models\Ranking::where('activo', true)->get();
            foreach($rankings as $row){
                HelperServices::obtenerPuntajeFinalUsuario($row);
            }
            $rankingOrdenado = \App\Models\Ranking::select('id','user_id','total','decil')->where('activo', true)->orderBy('total', 'asc')->get();
            $total = count($rankingOrdenado);
            $posicion = 0;
            foreach($rankingOrdenado as $row){
                $decil = floor((($posicion +1) * 10) / $total);
                $row->decil = $decil;
                $row->save();
                $posicion++;
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
    }
}
