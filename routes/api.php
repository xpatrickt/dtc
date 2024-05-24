<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\OcupacionController;
use App\Http\Controllers\CalificacionPreguntaController;
use App\Http\Controllers\BandejaController;
use App\Http\Controllers\PalabrasBetadasController;
use App\Http\Controllers\AyudaController;
use App\Http\Controllers\ExportController;

use App\Http\Controllers\NivelController;
use App\Http\Controllers\GradoController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\GradoCursoController;

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\ApoderadoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\DocenteCursoController;

use App\Http\Controllers\LibroController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\SalaReunionController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\ConvocatoriaController;
use App\Http\Controllers\EvaluacionPreguntaController;
use App\Http\Controllers\EvaluacionRespuestaMatriculaController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\CriterioController;
use App\Http\Controllers\CapacitacionController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'auth'], function () {
    Route::post('login_linkendin', [AuthController::class, 'loginLinkedin']);
    Route::post('admin', [AuthController::class, 'loginAdmin']);
    Route::post('invitado', [AuthController::class, 'loginInvitado']);
});


Route::middleware('auth:api')->group(function () {

  //rutas para usuario logueado
  Route::group(['prefix' => 'auth'], function () {
    Route::post('check', [AuthController::class, 'check']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'getPerfilUsuario']);

  });

  Route::group(['prefix' => 'usuario'], function () {
    Route::post('registrar_preferencias', [UsuarioController::class, 'registrarPreferencia']);
    Route::post('actualizar_avatar', [UsuarioController::class, 'actualizarAvatar']);
    Route::post('actualizar_qr', [UsuarioController::class, 'actualizarQR']);
    Route::post('actualizar_ayuda', [UsuarioController::class, 'actualizarMsgAyuda']);
    Route::get('ver/{id}', [UsuarioController::class, 'ver']);
    Route::get('llenar_combo', [UsuarioController::class, 'llenarCombo']);
    Route::post('seguir', [UsuarioController::class, 'seguirUsuario']);
    Route::get('obtener_notificaciones', [UsuarioController::class, 'obtenerNotificaciones']);
    Route::get('prueba', [UsuarioController::class, 'prueba']);
    //admin
    Route::get('listar', [UsuarioController::class, 'listar']);
    Route::post('registrar', [UsuarioController::class, 'crear']);
    Route::post('crear', [UsuarioController::class, 'crear']);
    Route::put('modificar/{id}', [UsuarioController::class, 'modificar']);
    Route::put('inactivar/{id}', [UsuarioController::class, 'inactivar']);
    Route::put('activar/{id}', [UsuarioController::class, 'activar']);
    Route::get('dashboard', [UsuarioController::class, 'dashboard']);

  });

  //Grupo Preguntas
  Route::group(['prefix' => 'pregunta'], function () {
    Route::get('ver_simple/{id}', [PreguntaController::class, 'ver']);
    Route::get('ver/{tipo}/{id}', [PreguntaController::class, 'verCompleto']);
    Route::post('crear_pregunta', [PreguntaController::class, 'crearPregunta']);
    Route::put('editar_pregunta/{id}', [PreguntaController::class, 'editarPregunta']);
    Route::post('crear_respuesta', [PreguntaController::class, 'crearRespuesta']);
    Route::put('modificar/{id}', [PreguntaController::class, 'modificar']);
    Route::put('inactivar/{id}', [PreguntaController::class, 'inactivar']);
    Route::put('activar/{id}', [PreguntaController::class, 'activar']);
    Route::get('listar', [PreguntaController::class, 'listar']);
    Route::get('listar_basico', [PreguntaController::class, 'listarBasico']);
    Route::get('listar_sugerencias', [PreguntaController::class, 'listarSugerencias']);
    Route::get('listar_busqueda_avanzada', [PreguntaController::class, 'listarBusquedaAvanzada']);
    Route::get('listar_calificar_top', [PreguntaController::class, 'listarCalificarTop']);
    Route::get('listar_moderar_top', [PreguntaController::class, 'listarModerarTop']);
    
    //admin
    Route::get('listar_admin', [PreguntaController::class, 'listarAdmin']);

  });

  //Grupo Persona
  Route::group(['prefix' => 'persona'], function () {
    Route::get('ver/{id}', [PersonaController::class, 'ver']);
    Route::get('buscar/{dni}', [PersonaController::class, 'buscar']);
    Route::get('llenar_combo', [PersonaController::class, 'llenarCombo']);
    Route::get('listar', [PersonaController::class, 'listar']);
    Route::post('crear', [PersonaController::class, 'crear']);
    Route::put('modificar/{id}', [PersonaController::class, 'modificar']);
    Route::put('inactivar/{id}', [PersonaController::class, 'inactivar']);
    Route::put('activar/{id}', [PersonaController::class, 'activar']);
    Route::get('listar_basico', [PersonaController::class, 'listarBasico']);
    Route::get('listar_sugerencias', [PersonaController::class, 'listarSugerencias']);
    Route::get('listar_tendencia_rubros', [PersonaController::class, 'listarTendenciaRubros']);
    Route::get('temas_rubro', [PersonaController::class, 'listarTemasRubro']);
    Route::get('rubros_criterio', [PersonaController::class, 'listarRubrosCriterio']);
    Route::get('listar_temas', [PersonaController::class, 'listarTemasCriterio']);

  });
  //extrasss
  Route::group(['prefix' => 'documento'], function () {
    Route::get('listar', [DocumentoController::class, 'listar']);
  });

  //Grupo Oxupaciones
  Route::group(['prefix' => 'ocupacion'], function () {
    Route::get('ver/{id}', [OcupacionController::class, 'ver']);
    Route::get('llenar_combo', [OcupacionController::class, 'llenarCombo']);
    Route::get('listar', [OcupacionController::class, 'listar']);
    Route::post('crear', [OcupacionController::class, 'crear']);
    Route::put('modificar/{id}', [OcupacionController::class, 'modificar']);
    Route::put('inactivar/{id}', [OcupacionController::class, 'inactivar']);
    Route::put('activar/{id}', [OcupacionController::class, 'activar']);

  });

  //Grupo Ocupaciones
  Route::group(['prefix' => 'calificacion'], function () {
    Route::post('me_gusta', [CalificacionPreguntaController::class, 'meGusta']);
    Route::post('no_gusta', [CalificacionPreguntaController::class, 'noMeGusta']);
    Route::post('denunciado', [CalificacionPreguntaController::class, 'denunciado']);
  });

  //Grupo Bandeja
  Route::group(['prefix' => 'bandeja'], function () {
    Route::post('crear', [BandejaController::class, 'crear']);
    Route::put('inactivar/{id}', [BandejaController::class, 'inactivar']);
    Route::get('listar_mensajes_usuario', [BandejaController::class, 'listarMensajesUsuario']);
    Route::get('listar_mensajes_admin/{id}', [BandejaController::class, 'listarMensajesAdmin']);
    Route::get('listar_bandeja_admin', [BandejaController::class, 'listarBandejaAdmin']);
  });

   //Grupo Bandeja
  Route::group(['prefix' => 'palabras'], function () {
    Route::get('ver/{id}', [PalabrasBetadasController::class, 'ver']);
    Route::get('llenar_combo', [PalabrasBetadasController::class, 'llenarCombo']);
    Route::get('listar', [PalabrasBetadasController::class, 'listar']);
    Route::post('crear', [PalabrasBetadasController::class, 'crear']);
    Route::put('modificar/{id}', [PalabrasBetadasController::class, 'modificar']);
    Route::put('inactivar/{id}', [PalabrasBetadasController::class, 'inactivar']);
    Route::put('activar/{id}', [PalabrasBetadasController::class, 'activar']);
  });

  //Grupo Ayuda
  Route::group(['prefix' => 'ayudas'], function () {
    Route::get('ver/{id}', [AyudaController::class, 'ver']);
    Route::get('llenar_combo', [AyudaController::class, 'llenarCombo']);
    Route::get('listar', [AyudaController::class, 'listar']);
    Route::post('crear', [AyudaController::class, 'crear']);
    Route::put('modificar/{id}', [AyudaController::class, 'modificar']);
    Route::post('modificar_imagen', [AyudaController::class, 'modificarImagen']);
    Route::put('inactivar/{id}', [AyudaController::class, 'inactivar']);
    Route::put('activar/{id}', [AyudaController::class, 'activar']);
    Route::get('obtener_ayudas', [AyudaController::class, 'obtenerAyudas']);
  });

  

  //Grupo Grado
  Route::group(['prefix' => 'grado'], function () {
    Route::get('ver/{id}', [GradoController::class, 'ver']);
    Route::get('llenar_combo/{id}', [GradoController::class, 'llenarCombo']);
    Route::get('listar', [GradoController::class, 'listar']);
    Route::post('crear', [GradoController::class, 'crear']);
    Route::put('modificar/{id}', [GradoController::class, 'modificar']);
    Route::post('modificar_imagen', [GradoController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [GradoController::class, 'eliminar']);
    Route::put('activar/{id}', [GradoController::class, 'activar']);
    Route::get('obtener_ayudas', [GradoController::class, 'obtenerAyudas']);
  });

  //Grupo Seccion
  Route::group(['prefix' => 'seccion'], function () {
    Route::get('ver/{id}', [SeccionController::class, 'ver']);
    Route::get('llenar_combo/{id}', [SeccionController::class, 'llenarCombo']);
    Route::get('listar', [SeccionController::class, 'listar']);
    Route::post('crear', [SeccionController::class, 'crear']);
    Route::put('modificar/{id}', [SeccionController::class, 'modificar']);
    Route::post('modificar_imagen', [SeccionController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [SeccionController::class, 'eliminar']);
    Route::put('activar/{id}', [SeccionController::class, 'activar']);
    Route::get('obtener_ayudas', [SeccionController::class, 'obtenerAyudas']);
  });

  //Grupo Curso
  Route::group(['prefix' => 'curso'], function () {
    Route::get('ver/{id}', [CursoController::class, 'ver']);
    Route::get('llenar_combo', [CursoController::class, 'llenarCombo']);
    Route::get('listar', [CursoController::class, 'listar']);
    Route::get('listar_primaria', [CursoController::class, 'listarPrimaria']);
    Route::get('listar_secundaria', [CursoController::class, 'listarSecundaria']);
    Route::post('crear', [CursoController::class, 'crear']);
    Route::put('modificar/{id}', [CursoController::class, 'modificar']);
    Route::post('modificar_imagen', [CursoController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [CursoController::class, 'eliminar']);
    Route::put('activar/{id}', [CursoController::class, 'activar']);
    Route::get('obtener_ayudas', [CursoController::class, 'obtenerAyudas']);
  });

  //Grupo Actividad
  Route::group(['prefix' => 'actividad'], function () {
    Route::get('ver/{id}', [ActividadController::class, 'ver']);
    Route::get('llenar_combo/{id_gracur}', [ActividadController::class, 'llenarCombo']);
    Route::get('listar', [ActividadController::class, 'listar']);
    Route::post('crear', [ActividadController::class, 'crear']);
    Route::put('modificar/{id}', [ActividadController::class, 'modificar']);
    Route::post('modificar_imagen', [ActividadController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [ActividadController::class, 'eliminar']);
    Route::put('activar/{id}', [ActividadController::class, 'activar']);
    Route::get('obtener_ayudas', [ActividadController::class, 'obtenerAyudas']);
  });

  //Grupo Grado Curso
  Route::group(['prefix' => 'grado_curso'], function () {
    Route::get('ver/{id}', [GradoCursoController::class, 'ver']);
    Route::get('llenar_combo/{id}', [GradoCursoController::class, 'llenarCombo']);
    Route::get('listar/{id_grado}', [GradoCursoController::class, 'listar']);
    Route::post('crear', [GradoCursoController::class, 'crear']);
    Route::put('modificar/{id}', [GradoCursoController::class, 'modificar']);
    Route::put('eliminar/{id}', [GradoCursoController::class, 'eliminar']);
    Route::put('activar/{id}', [GradoCursoController::class, 'activar']);
  });
  
  //Grupo Alumno
  Route::group(['prefix' => 'alumno'], function () {
    Route::get('ver', [AlumnoController::class, 'ver']);
    Route::get('llenar_combo', [AlumnoController::class, 'llenarCombo']);
    Route::get('listar', [AlumnoController::class, 'listar']);
    Route::post('crear', [AlumnoController::class, 'crear']);
    Route::put('modificar/{id_al}/{id_per}', [AlumnoController::class, 'modificar']);
    Route::post('modificar_imagen', [AlumnoController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [AlumnoController::class, 'eliminar']);
    Route::put('activar/{id}', [AlumnoController::class, 'activar']);
    Route::get('obtener_ayudas', [AlumnoController::class, 'obtenerAyudas']);
  });
  //Grupo Docente
  Route::group(['prefix' => 'docente'], function () {
    Route::get('ver/{id}', [DocenteController::class, 'ver']);
    Route::get('llenar_combo', [DocenteController::class, 'llenarCombo']);
    Route::get('listar', [DocenteController::class, 'listar']);
    Route::post('crear', [DocenteController::class, 'crear']);
    Route::put('modificar/{id_al}/{id_per}', [DocenteController::class, 'modificar']);
    Route::post('modificar_imagen', [DocenteController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [DocenteController::class, 'eliminar']);
    Route::put('activar/{id}', [DocenteController::class, 'activar']);
    Route::get('obtener_ayudas', [DocenteController::class, 'obtenerAyudas']);
  });

   //Grupo Libro
   Route::group(['prefix' => 'libro'], function () {
    Route::get('ver/{id}', [LibroController::class, 'ver']);
    Route::get('llenar_combo/{id}', [LibroController::class, 'llenarCombo']);
    Route::get('listar', [LibroController::class, 'listar']);
    Route::get('listar_filtro/{id_grado_cur}', [LibroController::class, 'listarFiltro']);
    Route::post('crear', [LibroController::class, 'crear']);
    Route::put('modificar/{id}', [LibroController::class, 'modificar']);
    Route::post('modificar_imagen', [LibroController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [LibroController::class, 'eliminar']);
    Route::put('activar/{id}', [LibroController::class, 'activar']);
    Route::get('obtener_ayudas', [LibroController::class, 'obtenerAyudas']);
  });

  //Grupo Video
  Route::group(['prefix' => 'video'], function () {
    Route::get('ver/{id}', [VideoController::class, 'ver']);
    Route::get('llenar_combo/{id}', [VideoController::class, 'llenarCombo']);
    Route::get('listar', [VideoController::class, 'listar']);
    Route::get('listar_filtro/{id_grado_cur}', [VideoController::class, 'listarFiltro']);
    Route::post('crear', [VideoController::class, 'crear']);
    Route::put('modificar/{id}', [VideoController::class, 'modificar']);
    Route::post('modificar_imagen', [VideoController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [VideoController::class, 'eliminar']);
    Route::put('activar/{id}', [VideoController::class, 'activar']);
    Route::get('obtener_ayudas', [VideoController::class, 'obtenerAyudas']);
  });

  //Grupo SalaReunion
  Route::group(['prefix' => 'sala_reunion'], function () {
    Route::get('ver/{id}', [SalaReunionController::class, 'ver']);
    Route::get('llenar_combo/{id}', [SalaReunionController::class, 'llenarCombo']);
    Route::get('listar/{tipo}', [SalaReunionController::class, 'listar']);
    Route::post('crear', [SalaReunionController::class, 'crear']);
    Route::put('modificar/{id}', [SalaReunionController::class, 'modificar']);
    Route::post('modificar_imagen', [SalaReunionController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [SalaReunionController::class, 'eliminar']);
    Route::put('activar/{id}', [SalaReunionController::class, 'activar']);
    Route::get('obtener_ayudas', [SalaReunionController::class, 'obtenerAyudas']);
  });

   //Grupo Docente Curso
   Route::group(['prefix' => 'docente_curso'], function () {
    Route::get('ver/{id}', [DocenteCursoController::class, 'ver']);
    Route::get('llenar_combo/{id}', [DocenteCursoController::class, 'llenarCombo']);
    Route::get('llenar_combo_grado/{id_nivel}/{id_docente}', [DocenteCursoController::class, 'llenarComboGrado']);
    Route::get('llenar_combo_seccion/{id_grado}/{id_docente}', [DocenteCursoController::class, 'llenarComboSeccion']);
    Route::get('llenar_combo_curso/{id_seccion}/{id_docente}', [DocenteCursoController::class, 'llenarComboCurso']);
    Route::get('listar/{id_docente}', [DocenteCursoController::class, 'listar']);
    Route::post('crear', [DocenteCursoController::class, 'crear']);
    Route::put('modificar/{id}', [DocenteCursoController::class, 'modificar']);
    Route::put('eliminar/{id}', [DocenteCursoController::class, 'eliminar']);
    Route::put('activar/{id}', [DocenteCursoController::class, 'activar']);
  });

  //Grupo Apoderado
  Route::group(['prefix' => 'apoderado'], function () {
    Route::get('ver/{id}', [ApoderadoController::class, 'ver']);
    Route::get('listarEstudianteApoderado/{id}', [ApoderadoController::class, 'listarEstudianteApoderado']);
    Route::get('listar', [ApoderadoController::class, 'listar']);
    Route::post('crear', [ApoderadoController::class, 'crear']);
    Route::post('estudianteApoderado', [ApoderadoController::class, 'estudianteApoderado']);
    Route::put('modificar/{id}', [ApoderadoController::class, 'modificar']);
    Route::get('buscar/{dni}', [ApoderadoController::class, 'buscar']);
    Route::put('eliminar/{id}', [ApoderadoController::class, 'eliminar']);
    Route::put('activar/{id}', [ApoderadoController::class, 'activar']);
    Route::get('obtener_ayudas', [ApoderadoController::class, 'obtenerAyudas']);
  });
  //Grupo Matricula
  Route::group(['prefix' => 'matricula'], function () {
    Route::get('ver/{id}', [MatriculaController::class, 'ver']);
    Route::get('llenar_combo/{id}', [MatriculaController::class, 'llenarCombo']);
    Route::get('listar/{id_docente}', [MatriculaController::class, 'listar']);
    Route::post('crear', [MatriculaController::class, 'crear']);
    Route::post('guardarApoderado', [MatriculaController::class, 'guardarApoderado']);
    Route::put('modificar/{id}', [MatriculaController::class, 'modificar']);
    Route::put('eliminar/{id}', [MatriculaController::class, 'eliminar']);
    Route::put('activar/{id}', [MatriculaController::class, 'activar']);
  });

  //Grupo Evaluacion
  Route::group(['prefix' => 'evaluacion'], function () {
    Route::get('ver/{id}', [EvaluacionController::class, 'ver']);
    Route::get('llenar_combo', [EvaluacionController::class, 'llenarCombo']);
    Route::get('evaluar/{id}/{convocatoria}', [EvaluacionController::class, 'evaluar']);
    Route::post('guardar', [EvaluacionController::class, 'guardar']);
    Route::get('mostrar/{id}', [EvaluacionController::class, 'mostrar']);
    Route::get('mostrarReporte/{id}', [EvaluacionController::class, 'mostrarReporte']);
    Route::post('modificar_imagen', [EvaluacionController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [EvaluacionController::class, 'eliminar']);
    Route::put('activar/{id}', [EvaluacionController::class, 'activar']);
    Route::get('obtener_ayudas', [EvaluacionController::class, 'obtenerAyudas']);
    Route::get('getDateTimeActual', [EvaluacionController::class, 'getDateTimeActual']);
  });
  //Grupo Evaluacion
  Route::group(['prefix' => 'capacitacion'], function () {
    Route::get('ver/{id}', [CapacitacionController::class, 'ver']);
    Route::get('llenar_combo', [CapacitacionController::class, 'llenarCombo']);
    Route::get('aulas/{convocatoria}', [CapacitacionController::class, 'aulas']);
    Route::post('generar', [CapacitacionController::class, 'generar']);
    Route::post('guardarSN', [CapacitacionController::class, 'guardarSN']);
    Route::post('guardarMN', [CapacitacionController::class, 'guardarMN']);
    Route::post('guardarTAP', [CapacitacionController::class, 'guardarTAP']);
    Route::post('guardarCR', [CapacitacionController::class, 'guardarCR']);
    Route::post('guardarCP', [CapacitacionController::class, 'guardarCP']);
    Route::post('guardarSPA', [CapacitacionController::class, 'guardarSPA']);
    Route::post('guardarSAS', [CapacitacionController::class, 'guardarSAS']);
    Route::get('mostrar/{id}', [CapacitacionController::class, 'mostrar']);
    Route::get('mostrarReporte/{id}', [CapacitacionController::class, 'mostrarReporte']);
    Route::post('modificar_imagen', [CapacitacionController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [CapacitacionController::class, 'eliminar']);
    Route::put('activar/{id}', [CapacitacionController::class, 'activar']);
    Route::get('obtener_ayudas', [CapacitacionController::class, 'obtenerAyudas']);
    Route::get('getDateTimeActual', [CapacitacionController::class, 'getDateTimeActual']);
  });

  //Grupo Convocatoria
  Route::group(['prefix' => 'convocatoria'], function () {
    Route::get('ver/{id}', [ConvocatoriaController::class, 'ver']);
    Route::get('llenar_combo', [ConvocatoriaController::class, 'llenarCombo']);
    Route::get('listar', [ConvocatoriaController::class, 'listar']);
    Route::get('listarEvaluacion/{id_evaluacion}', [ConvocatoriaController::class, 'listarEvaluacion']);
    Route::post('crear', [ConvocatoriaController::class, 'crear']);
    Route::put('modificar/{id}', [ConvocatoriaController::class, 'modificar']);
    Route::post('modificar_imagen', [ConvocatoriaController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [ConvocatoriaController::class, 'eliminar']);
    Route::put('activar/{id}', [ConvocatoriaController::class, 'activar']);
    Route::get('obtener_ayudas', [ConvocatoriaController::class, 'obtenerAyudas']);
  });
  //Grupo Asistencia
  Route::group(['prefix' => 'asistencia'], function () {
    Route::get('ver/{id}/{convocatoria}', [AsistenciaController::class, 'ver']);
    Route::get('llenar_combo', [AsistenciaController::class, 'llenarCombo']);
    Route::get('listar', [AsistenciaController::class, 'listar']);
    Route::get('listarEvaluacion/{id_evaluacion}', [AsistenciaController::class, 'listarEvaluacion']);
    Route::post('crear', [AsistenciaController::class, 'crear']);
    Route::put('modificar/{id}', [AsistenciaController::class, 'modificar']);
    Route::post('modificar_imagen', [AsistenciaController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [AsistenciaController::class, 'eliminar']);
    Route::put('activar/{id}', [AsistenciaController::class, 'activar']);
    Route::get('obtener_ayudas', [AsistenciaController::class, 'obtenerAyudas']);
  });
   //Grupo examen
   Route::group(['prefix' => 'examen'], function () {
    Route::get('ver/{id}', [ExamenController::class, 'ver']);
    Route::get('llenar_combo', [ExamenController::class, 'llenarCombo']);
    Route::get('listar', [ExamenController::class, 'listar']);
    Route::get('listarEvaluacion/{id_evaluacion}', [ExamenController::class, 'listarEvaluacion']);
    Route::post('generar', [ExamenController::class, 'generar']);
    Route::post('rankear', [ExamenController::class, 'rankear']);
    Route::post('reporte', [ExamenController::class, 'reporte']);
    Route::put('modificar/{id}', [ExamenController::class, 'modificar']);
    Route::post('modificar_imagen', [ExamenController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [ExamenController::class, 'eliminar']);
    Route::put('activar/{id}', [ExamenController::class, 'activar']);
    Route::get('obtener_ayudas', [ExamenController::class, 'obtenerAyudas']);
  });
  //Grupo Criterio
  Route::group(['prefix' => 'criterio'], function () {
    Route::get('ver/{id}', [CriterioController::class, 'ver']);
    Route::get('llenar_combo/{id_act}', [CriterioController::class, 'llenarCombo']);
    Route::get('listar/{id_act}', [CriterioController::class, 'listar']);
    Route::get('listarEvaluacion/{id_evaluacion}', [CriterioController::class, 'listarEvaluacion']);
    Route::post('crear', [CriterioController::class, 'crear']);
    Route::put('modificar/{id}', [CriterioController::class, 'modificar']);
    Route::post('modificar_imagen', [CriterioController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [CriterioController::class, 'eliminar']);
    Route::put('activar/{id}', [CriterioController::class, 'activar']);
    Route::get('obtener_ayudas', [CriterioController::class, 'obtenerAyudas']);
  });

  //Grupo EvaluacionPregunta
  Route::group(['prefix' => 'evaluacion_pregunta'], function () {
    Route::get('ver/{id}', [EvaluacionPreguntaController::class, 'ver']);
    Route::get('llenar_combo', [EvaluacionPreguntaController::class, 'llenarCombo']);
    Route::get('listar', [EvaluacionPreguntaController::class, 'listar']);
    Route::get('listarEvaluacion/{id_evaluacion}', [EvaluacionPreguntaController::class, 'listarEvaluacion']);
    Route::get('listarEvaluacionMatricula/{id_evaluacion}/{id_matricula}/{id_evaluacion_curso}', [EvaluacionPreguntaController::class, 'listarEvaluacionMatricula']);
    Route::post('crear', [EvaluacionPreguntaController::class, 'crear']);
    Route::put('modificar/{id}', [EvaluacionPreguntaController::class, 'modificar']);
    Route::post('modificar_imagen', [EvaluacionPreguntaController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [EvaluacionPreguntaController::class, 'eliminar']);
    Route::put('activar/{id}', [EvaluacionPreguntaController::class, 'activar']);
    Route::get('obtener_ayudas', [EvaluacionPreguntaController::class, 'obtenerAyudas']);
  });

  //Grupo EvaluacionRespuestaMatricula
  Route::group(['prefix' => 'evaluacion_respuesta_matricula'], function () {
    Route::get('ver/{id}', [EvaluacionRespuestaMatriculaController::class, 'ver']);
    Route::get('llenar_combo', [EvaluacionRespuestaMatriculaController::class, 'llenarCombo']);
    Route::get('listar', [EvaluacionRespuestaMatriculaController::class, 'listar']);
    Route::get('listarRespuestas/{id_evaluacion}/{id_matricula}', [EvaluacionRespuestaMatriculaController::class, 'listarRespuestas']);
    Route::post('crear', [EvaluacionRespuestaMatriculaController::class, 'crear']);
    Route::put('modificar/{id}', [EvaluacionRespuestaMatriculaController::class, 'modificar']);
    Route::post('modificar_imagen', [EvaluacionRespuestaMatriculaController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [EvaluacionRespuestaMatriculaController::class, 'eliminar']);
    Route::put('activar/{id}', [EvaluacionRespuestaMatriculaController::class, 'activar']);
    Route::get('obtener_ayudas', [EvaluacionRespuestaMatriculaController::class, 'obtenerAyudas']);
  });
  //Grupo EvaluacionRespuestaMatricula
  Route::group(['prefix' => 'export'], function () {
    Route::post('reporte_cv', [ExportController::class, 'reporteCV']);
    Route::post('examen', [ExportController::class, 'reporteCV']);
    Route::get('llenar_combo', [ExportController::class, 'llenarCombo']);
    Route::get('listar', [ExportController::class, 'listar']);
    Route::get('listarRespuestas/{id_evaluacion}/{id_matricula}', [ExportController::class, 'listarRespuestas']);
    Route::post('crear', [ExportController::class, 'crear']);
    Route::put('modificar/{id}', [ExportController::class, 'modificar']);
    Route::post('modificar_imagen', [ExportController::class, 'modificarImagen']);
    Route::put('eliminar/{id}', [ExportController::class, 'eliminar']);
    Route::put('activar/{id}', [ExportController::class, 'activar']);
    Route::get('obtener_ayudas', [ExportController::class, 'obtenerAyudas']);
  });

});


