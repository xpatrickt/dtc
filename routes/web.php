<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ExportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'exportar'], function () {
    Route::get('ayudas', [ExportController::class, 'exportAyudas']);
    Route::get('mensajes', [ExportController::class, 'exportMensajes']);
    Route::get('contenidos', [ExportController::class, 'exportContenidos']);
    Route::get('etiquetas', [ExportController::class, 'exportEtiquetas']);
    Route::get('ocupaciones', [ExportController::class, 'exportOcupaciones']);
    Route::get('palabras-vetadas', [ExportController::class, 'exportPalabrasVetadas']);
    Route::get('reporte_cv', [ExportController::class, 'reporteCV']);
    Route::get('reporteRecepcionTAP', [ExportController::class, 'reporteRecepcionTAP']);
    Route::get('reporteRecepcionCP', [ExportController::class, 'reporteRecepcionCP']);
    Route::get('reporteRecepcionSPA', [ExportController::class, 'reporteRecepcionSPA']);
    Route::get('reporteRecepcionSAS', [ExportController::class, 'reporteRecepcionSAS']);
    Route::get('reporteEvaluacionTAP', [ExportController::class, 'reporteEvaluacionTAP']);
    Route::get('reporteEvaluacionCP', [ExportController::class, 'reporteEvaluacionCP']);
    Route::get('reporteEvaluacionSPA', [ExportController::class, 'reporteEvaluacionSPA']);
    Route::get('reporteEvaluacionSAS', [ExportController::class, 'reporteEvaluacionSAS']);
    Route::get('reporteCapacitacionSN', [ExportController::class, 'reporteCapacitacionSN']);
    Route::get('reporteCapacitacionCR', [ExportController::class, 'reporteCapacitacionCR']);
    Route::get('reporteCapacitacionMN', [ExportController::class, 'reporteCapacitacionMN']);
    Route::get('reporteCapacitacionTAP', [ExportController::class, 'reporteCapacitacionTAP']);
    Route::get('reporteCapacitacionCP', [ExportController::class, 'reporteCapacitacionCP']);
    Route::get('reporteCapacitacionSPA', [ExportController::class, 'reporteCapacitacionSPA']);
    Route::get('reporteCapacitacionSAS', [ExportController::class, 'reporteCapacitacionSAS']);
    Route::get('examen', [ExportController::class, 'examen']);
    Route::get('usuarios', [ExportController::class, 'exportUsuarios']);

});

Route::get('prueba', [ExportController::class, 'prueba']);


Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');
