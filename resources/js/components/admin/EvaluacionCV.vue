<template>
    <div class="p-3 pt-5">
        <div class="col-md-12" style="margin-left: -15px;">
            <h4 class="text-color-2 mb-3">PRESELECCION: Registro de Evaluacion de CV</h4>
        </div>
        <br>
        <div class="form-group col-3">
            <p class="m-0">
                <strong>Convocatoria</strong>
            </p>
            <select name="convocatoria" v-model="idConvocatoria" class="form-control" data-vv-as="Convocatoria"
                placeholder="Seleccione Convocatoria" v-validate="'required'">
                <option v-for="row in listarConvocatorias" :key="row.id" :value="row.id" v-text="row.nombre"></option>
            </select>
            <!-- <span class="text-danger">{{errors.first("form_registro.nivel")}}</span> -->
        </div>
        <div class="row justify-content-md-center">
            <div class="col-md-5">
                <div class="form-group">
                    <label>Ingrese Número de DNI</label>
                    <input v-if="idConvocatoria" type="text" name="" class="form-control" v-model="numeroDni"
                        @keyup.enter="buscar">
                    <input v-else disabled type="text" name="" class="form-control" v-model="numeroDni"
                        @keyup.enter="buscar">
                </div>
            </div>
        </div>
        <br>
        <label><b>POSTULANTE: </b></label>
        <label v-text="evaluacion1.datos"></label>
        <label><b> - DNI:</b></label>
        <label v-text="evaluacion1.documento"></label>
        <br>
        <div v-if="idConvocatoria == 1 || idConvocatoria == 2">
            <table class="table1 table-bordered">
                <thead>
                    <tr>
                        <th scope="col" colspan="2" style="text-align:center;background-color: #95D0FC;">FORMATO DE
                            EVALUACION
                            DE CV PARA EL CARGO DE SUPERVISO REGIONAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">N° registro de CV recepcionado</td>
                        <td><input type="text" v-model="evaluacion1.num_registro" disabled></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">Requisitos del Perfil</th>
                        <th style="background-color: #95D0FC;">
                            <p>Cumple con el requisito Si NO</p>
                        </th>
                    </tr>
                    <tr>
                        <td scope="row">Inscrito en el Registro Nacional de Proveedores (RNP)
                        </td>
                        <td><input type="radio" name="grado" v-model="evaluacion1.rnp" value="SI"> Si <input type="radio"
                                name="grado" v-model="evaluacion1.rnp" value="NO"> No </td>
                    </tr>
                    <tr>
                        <td scope="row">Minimo bachiller universitario,(Exepto carreras vinculadas al cuidado personal u
                            oficios)
                        </td>
                        <td><input type="radio" name="profesion" v-model="evaluacion1.profesion" value="SI"> Si <input
                                type="radio" name="profesion" v-model="evaluacion1.profesion" value="NO"> No </td>
                    </tr>
                    <tr v-if="idConvocatoria == 1">
                        <td scope="row">
                            <label>Manejo de herramientas de office (Procesador de textos, hoja de calculo, etc)(Constancia
                                o certificado)</label>
                        </td>
                        <td><input type="radio" name="office" v-model="evaluacion1.office" value="SI"> Si <input
                                type="radio" name="office" v-model="evaluacion1.office" value="NO"> No </td>
                    </tr>
                    <tr v-if="idConvocatoria == 2">
                        <td scope="row">
                            <label>Manejo de hojas de cálculo Excel y herramientas de Office (DJ, constancia o
                                certificado)</label>
                        </td>
                        <td><input type="radio" name="office" v-model="evaluacion1.office" value="SI"> Si <input
                                type="radio" name="office" v-model="evaluacion1.office" value="NO"> No </td>
                    </tr>
                    <tr v-if="idConvocatoria == 1">
                        <td scope="row">
                            <label>Experiencia no menor a dos (02) años en actividades de Coordinación y supervisión de
                                procesos de recojo de información a escala nacional en el sector público o privado. o seis
                                (06)
                                meses en coordinación/ supervisión en proyectos de aplicación de instrumentos de evaluación
                                a nivel
                                nacional o regional.</label>
                        </td>
                        <td><input type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="SI"> Si <input
                                type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="NO"> No </td>
                    </tr>
                    <tr v-if="idConvocatoria == 2">
                        <td scope="row">
                            <label>Experiencia no menor a seis (6) meses en monitoreo de procesos de recojo de
                                información a escala nacional en el sector publico o privado, o en proyectos de aplicación
                                de instrumentos de evaluación a nivel nacional o regional, o doce (12) meses en actividades
                                de
                                Coordinación y/o supervisión de procesos de recojo de información a escala nacional en el
                                sector
                                público o privado; o de proyectos de aplicación de instrumentos de evaluación a nivel
                                nacional o regional.</label>
                        </td>
                        <td><input type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_2" value="SI"> Si <input
                                type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_2" value="NO"> No </td>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">CUMPLE CON LO SOLICITADO</th>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.office == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <label :value="evaluacion1.estado_cv">SI</label> </td>
                        <td v-else><label :value="evaluacion1.estado_cv">NO</label></td>

                    </tr>
                    <tr>
                        <th scope="row" colspan="2"></th>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">CRITERIOS</th>
                        <th style="background-color: #95D0FC;">
                            <P>Calificacion (Segun puntuacion de los criterios)</P>
                        </th>
                    </tr>
                    <tr>
                        <td scope="row">Formacion academica</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.office == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="text" name="formacion" v-model="evaluacion1.grado" @keypress="soloNumeros4($event)"
                                maxlength="1"> </td>
                    </tr>
                    <!-- de aqui es para supervisor-->
                    <tr v-if="idConvocatoria == 1">
                        <td scope="row">Experiencia en coordinacion/supervision en proyectos de aplicacionde instrumentos de
                            evaluacion a nivel nacional o regional</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.office == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expAplicacion" v-model="evaluacion1.criterio_cv_2"
                                @keypress="soloNumeros5($event)" maxlength="1"> </td>
                    </tr>
                    <tr v-if="idConvocatoria == 1">
                        <td scope="row">Experiencia en actividades de Coordinacion y Supervison de procesos de recojo de
                            einformacion a escala nacional en el sector publico o privado</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.office == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expRecojo" v-model="evaluacion1.criterio_cv_3"
                                @keypress="soloNumeros5($event)" maxlength="1"> </td>
                    </tr>
                    <!-- de aqui es para monitor-->
                    <tr v-if="idConvocatoria == 2">
                        <td scope="row">Experiencia en monitoreo de procesos de recojo de información a escala nacional en
                            el sector público o privado, o en proyectos de aplicación de instrumentos de evaluación a nivel
                            nacional o regional.</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.office == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expAplicacion" v-model="evaluacion1.criterio_cv_2"
                                @keypress="soloNumeros4($event)" maxlength="1"> </td>
                    </tr>
                    <tr v-if="idConvocatoria == 2">
                        <td scope="row">Experiencia en coordinación y/o supervisión de procesos de recojo de información a
                            escala nacional en el sector público o privado, o en proyectos de aplicación de instrumentos de
                            evaluación a nivel nacional o regional.</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.office == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expRecojo" v-model="evaluacion1.criterio_cv_3"
                                @keypress="soloNumeros5($event)" maxlength="1"> </td>
                    </tr>
                    <tr v-if="idConvocatoria == 1">
                        <td scope="row">Es opcional: cuenta con constancio o certificaso emitido por alguna organizacion
                            indigena o centros de estudios en lengua originaria o declaracion judara en lengua originaria
                            (Ashaninka, Qechua Cusco Collao, Qechua Chanka, Shipibo-Konibo, Aymara, Awajun)</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.office == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="radio" name="lengua" v-model="evaluacion1.certificado_lengua" value="SI"> Si <input
                                type="radio" name="lengua" v-model="evaluacion1.certificado_lengua" value="NO"> No </td>
                    </tr>

                </tbody>
            </table>
            <button class="btn btn-secondary float-right" type="button" @click="guardar">
                Guardar
            </button>
        </div>
        <!-- modal para TAP -->
        <div v-if="idConvocatoria == 3">
            <table class="table1 table-bordered">
                <thead>
                    <tr>
                        <th scope="col" colspan="2" style="text-align:center;background-color: #95D0FC;">FORMATO DE
                            EVALUACION
                            DE CV PARA EL TECNICO ADMINISTRATIVO PROVINCIAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">N° registro de CV recepcionado</td>
                        <td><input type="text" v-model="evaluacion1.num_registro" disabled></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">Requisitos del Perfil</th>
                        <th style="background-color: #95D0FC;">
                            <p>Cumple con el requisito Si NO</p>
                        </th>
                    </tr>
                    <tr>
                        <td scope="row">Inscrito en el Registro Nacional de Proveedores (RNP)
                        </td>
                        <td><input type="radio" name="grado" v-model="evaluacion1.rnp" value="SI"> Si <input type="radio"
                                name="grado" v-model="evaluacion1.rnp" value="NO"> No </td>
                    </tr>
                    <tr>
                        <td scope="row">Mínimo egresado técnico en Administracion, Economia, Ingenieria Industrial,
                            Ingenieria Empresarial o Contabilidad u otras carreras afines.
                        </td>
                        <td><input type="radio" name="profesion" v-model="evaluacion1.profesion" value="SI"> Si <input
                                type="radio" name="profesion" v-model="evaluacion1.profesion" value="NO"> No </td>
                    </tr>
                    <tr>
                        <td scope="row">
                            <label>Mínimo seis (06) meses en el sector público o privado.</label>
                        </td>
                        <td><input type="radio" name="experiencia1" v-model="evaluacion1.criterio_cv_6" value="SI"> Si <input
                                type="radio" name="experiencia1" v-model="evaluacion1.criterio_cv_6" value="NO"> No </td>
                    </tr><tr>
                        <td scope="row">
                            <label>Mínimo una (01) experiencia en labores administrativas en instituciones públicas o
                                privadas.</label>
                        </td>
                        <td><input type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="SI"> Si <input
                                type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="NO"> No </td>
                    </tr>
                    <tr>
                        <td scope="row">
                            <label>Manejo de Hojas de cálculo Excel y herramientas de Office (declaración jurada, constancia o certificado)</label>
                        </td>
                        <td><input type="radio" name="office" v-model="evaluacion1.office" value="SI"> Si <input
                                type="radio" name="office" v-model="evaluacion1.office" value="NO"> No </td>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">CUMPLE CON LO SOLICITADO</th>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.office == 'SI' && evaluacion1.criterio_cv_1 == 'SI'  && evaluacion1.criterio_cv_6 == 'SI' && evaluacion1.profesion == 'SI'">
                            <label :value="evaluacion1.estado_cv">SI</label> </td>
                        <td v-else><label :value="evaluacion1.estado_cv">NO</label></td>

                    </tr>
                    <tr>
                        <th scope="row" colspan="2"></th>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">CRITERIOS</th>
                        <th style="background-color: #95D0FC;">
                            <P>Calificacion (Segun puntuacion de los criterios)</P>
                        </th>
                    </tr>
                    <tr>
                        <td scope="row">Formacion academica</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.office == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.criterio_cv_6 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="text" name="formacion" v-model="evaluacion1.grado" @keypress="soloNumeros5($event)"
                                maxlength="1"> </td>
                    </tr>
                    <tr>
                        <td scope="row">Experiencia administrativas en instituciones públicas o privadas</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.office == 'SI' && evaluacion1.criterio_cv_1 == 'SI'  && evaluacion1.criterio_cv_6 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expAplicacion" v-model="evaluacion1.criterio_cv_2"
                                @keypress="soloNumeros6($event)" maxlength="1"> </td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-secondary float-right" type="button" @click="guardar">
                Guardar
            </button>
        </div>
        <!-- modal para CP -->
        <div v-if="idConvocatoria == 4">
            <table class="table1 table-bordered">
                <thead>
                    <tr>
                        <th scope="col" colspan="2" style="text-align:center;background-color: #95D0FC;">FORMATO DE
                            EVALUACION
                            DE CV PARA EL COORDINANDOR PROVINCIAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">N° registro de CV recepcionado</td>
                        <td><input type="text" v-model="evaluacion1.num_registro" disabled></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">Requisitos del Perfil</th>
                        <th style="background-color: #95D0FC;">
                            <p>Cumple con el requisito Si NO</p>
                        </th>
                    </tr>
                    <tr>
                        <td scope="row">Inscrito en el Registro Nacional de Proveedores (RNP)
                        </td>
                        <td><input type="radio" name="grado" v-model="evaluacion1.rnp" value="SI"> Si <input type="radio"
                                name="grado" v-model="evaluacion1.rnp" value="NO"> No </td>
                    </tr>
                    <tr>
                        <td scope="row">Mínimo egresado universitario o titulado técnico. (Excepto carreras vinculadas al cuidado personal u oficios).
                        </td>
                        <td><input type="radio" name="profesion" v-model="evaluacion1.profesion" value="SI"> Si <input
                                type="radio" name="profesion" v-model="evaluacion1.profesion" value="NO"> No </td>
                    </tr>
                    <tr>
                        <td scope="row">
                            <label>Mínimo dos (2) experiencias en la coordinación o supervisión de operativos de aplicación de instrumentos o de recojo 
                                de información, o en la coordinación de proyectos educativos, o seis  (6) meses de experiencia o tres (3) experiencias 
                                demostradas con un mínimo de dos (2) meses, cada una, en la asistencia de supervisión de operativos de aplicación de 
                                instrumentos de evaluación. </label>
                        </td>
                        <td><input type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="SI"> Si <input
                                type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="NO"> No </td>
                    </tr>
                    
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">CUMPLE CON LO SOLICITADO</th>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <label :value="evaluacion1.estado_cv">SI</label> </td>
                        <td v-else><label :value="evaluacion1.estado_cv">NO</label></td>

                    </tr>
                    <tr>
                        <th scope="row" colspan="2"></th>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">CRITERIOS</th>
                        <th style="background-color: #95D0FC;">
                            <P>Calificacion (Segun puntuacion de los criterios)</P>
                        </th>
                    </tr>
                    <tr>
                        <td scope="row">Formacion academica</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="text" name="formacion" v-model="evaluacion1.grado" @keypress="soloNumeros4($event)"
                                maxlength="1"> </td>
                    </tr>
                    <tr>
                        <td scope="row">Experiencia en la coordinación o supervisión de operativos de aplicación de instrumentos 
                            o de recojo de información, o en la coordinación de proyectos educativos.</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expAplicacion" v-model="evaluacion1.criterio_cv_2"
                                @keypress="soloNumeros5($event)" maxlength="1"> </td>
                    </tr>
                    <tr>
                        <td scope="row">Experiencia en la coordinación o supervisión de recojo de información, o en la coordinación 
                            de proyectos educativos.</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expAplicacion" v-model="evaluacion1.criterio_cv_3"
                                @keypress="soloNumeros5($event)" maxlength="1"> </td>
                    </tr>
                    <tr>
                        <td scope="row">Experiencia en la asistencia de supervisión de operativos de aplicación de instrumentos de evaluación.</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' && evaluacion1.criterio_cv_1 == 'SI' && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expAplicacion" v-model="evaluacion1.criterio_cv_4"
                                @keypress="soloNumeros5($event)" maxlength="1"> </td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-secondary float-right" type="button" @click="guardar">
                Guardar
            </button>
        </div>
        <!-- modal para SPA -->
        <div v-if="idConvocatoria == 5">
            <table class="table1 table-bordered">
                <thead>
                    <tr>
                        <th scope="col" colspan="2" style="text-align:center;background-color: #95D0FC;">FORMATO DE EVALUACION 
                        DE CV PARA SUPERVISOR DE PROCESOS DE APLICACION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">N° registro de CV recepcionado</td>
                        <td><input type="text" v-model="evaluacion1.num_registro" disabled></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">Requisitos del Perfil</th>
                        <th style="background-color: #95D0FC;">
                            <p>Cumple con el requisito Si NO</p>
                        </th>
                    </tr>
                    <tr>
                        <td scope="row">Inscrito en el Registro Nacional de Proveedores (RNP)
                        </td>
                        <td><input type="radio" name="grado" v-model="evaluacion1.rnp" value="SI"> Si <input type="radio"
                                name="grado" v-model="evaluacion1.rnp" value="NO"> No </td>
                    </tr>
                    <tr>
                        <td scope="row">Mínimo egresado universitario o  técnico. (Excepto carreras vinculadas al cuidado personal u oficios).
                        </td>
                        <td><input type="radio" name="profesion" v-model="evaluacion1.profesion" value="SI"> Si <input
                                type="radio" name="profesion" v-model="evaluacion1.profesion" value="NO"> No </td>
                    </tr>
                    <tr >
                        <td scope="row">
                            <label><b>Perfil A:</b> <br>
                            Personal con experiencia mínimo dos (2) meses como supervisor o coordinador de evaluaciones estandarizadas de estudiantes, o<br>
                            <hr><b>Perfil B:<br>Personal sin experiencia<br></b>
                            
                            • Mínimo dos (2) experiencias demostradas, con dos (2) meses, cada una, en la coordinación o supervisión de operativos de 
                            aplicación de instrumentos o de recojo de información, o en la coordinación de proyectos educativos (Diferente a la 
                            evaluación de estudiantes), o <br>
                            • Tres (3) meses de experiencia demostrada en la asistencia de supervisión de operativos de aplicación de instrumentos de
                             evaluación. "		

                        </label>
                        </td>
                        <td><br><input type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="A"> A <br><br><br>
                            <input type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="B"> B<br><br><br><br><br>
                            <input type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="NO"> Ninguno 
                        </td>
                    </tr>
                    <tr >
                        <th scope="row" style="background-color: #95D0FC;">CUMPLE CON LO SOLICITADO</th>
                        <td 
                            v-if="evaluacion1.rnp == 'SI' &&  (evaluacion1.criterio_cv_1 == 'A' ||  evaluacion1.criterio_cv_1 == 'B') && evaluacion1.profesion == 'SI'">
                            <label :value="evaluacion1.estado_cv">SI</label> </td>
                        <td v-else><label :value="evaluacion1.estado_cv">NO</label></td>

                    </tr>
                    
                    <tr>
                        <th scope="row" colspan="2"></th>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">CRITERIOS</th>
                        <th style="background-color: #95D0FC;">
                            <P>Calificacion (Segun puntuacion de los criterios)</P>
                        </th>
                    </tr>
                    <tr>
                        <td scope="row">Formacion academica</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' &&  (evaluacion1.criterio_cv_1 == 'A' ||  evaluacion1.criterio_cv_1 == 'B') && evaluacion1.profesion == 'SI'">
                            <input type="text" name="formacion" v-model="evaluacion1.grado" @keypress="soloNumeros5($event)"
                                maxlength="1"> </td>
                    </tr>
                    <tr>
                        <td scope="row">Experiencia como supervisor o coordinador de evaluaciones estandarizadas</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' &&  (evaluacion1.criterio_cv_1 == 'A' ||  evaluacion1.criterio_cv_1 == 'B') && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expAplicacion" v-model="evaluacion1.criterio_cv_2"
                                @keypress="soloNumeros5($event)" maxlength="1"> </td>
                    </tr>
                    <tr>
                        <td scope="row">Experiencia en la coordinación o supervisión de operativos de aplicación de instrumentos o de recojo de información, o en la coordinación de proyectos educativos</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' &&  (evaluacion1.criterio_cv_1 == 'A' ||  evaluacion1.criterio_cv_1 == 'B') && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expAplicacion" v-model="evaluacion1.criterio_cv_3"
                                @keypress="soloNumeros5($event)" maxlength="1"> </td>
                    </tr>
                    <tr>
                        <td scope="row">Experiencia en la asistencia de supervisión de operativos de aplicación de instrumentos de evaluación</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' &&  (evaluacion1.criterio_cv_1 == 'A' ||  evaluacion1.criterio_cv_1 == 'B') && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expAplicacion" v-model="evaluacion1.criterio_cv_4"
                                @keypress="soloNumeros5($event)" maxlength="1"> </td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-secondary float-right" type="button" @click="guardar">
                Guardar
            </button>
        </div>
        <!-- modal para SPA -->
        <div v-if="idConvocatoria == 6">
            <table class="table1 table-bordered">
                <thead>
                    <tr>
                        <th scope="col" colspan="2" style="text-align:center;background-color: #95D0FC;">FORMATO DE EVALUACION 
                        DE CV PARA SUPERVISOR DE ALACEN Y SOPORTE INFORAMTICO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">N° registro de CV recepcionado</td>
                        <td><input type="text" v-model="evaluacion1.num_registro" disabled></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">Requisitos del Perfil</th>
                        <th style="background-color: #95D0FC;">
                            <p>Cumple con el requisito Si NO</p>
                        </th>
                    </tr>
                    <tr>
                        <td scope="row">Inscrito en el Registro Nacional de Proveedores (RNP)
                        </td>
                        <td><input type="radio" name="grado" v-model="evaluacion1.rnp" value="SI"> Si <input type="radio"
                                name="grado" v-model="evaluacion1.rnp" value="NO"> No </td>
                    </tr>
                    <tr>
                        <td scope="row">Mínimo egresado universitario o  técnico de carreras de sistemas e informática, o 
                            computación e informática, o informática o ingeniería electrónica o ingeniería industrial o 
                            telecomunicaciones o mecatrónica o telemática o ciencias de la computación o ciencias de datos 
                            o ingeniería informática.		

                        </td>
                        <td><input type="radio" name="profesion" v-model="evaluacion1.profesion" value="SI"> Si <input
                                type="radio" name="profesion" v-model="evaluacion1.profesion" value="NO"> No </td>
                    </tr>
                    <tr >
                        <td scope="row">
                            <label><b>Perfil A:</b> <br>
                                <u>Personal con experiencia</u> mínimo dos (2) meses en evaluaciones estandarizadas de estudiantes 
                                como Supervisor de almacén y sistemas, o supervisor de almacén y soporte informático, o 
                                supervisor de inventario y procesos administrativos, o supervisor de sistemas y almacén, o<br>
                                <hr>
                                <b>Perfil B:</b>                            
                                <u>Personal sin experiencia</u> Tres (3) meses o dos (2) experiencias en el soporte técnico, mesa de ayuda, 
                                asistencia técnica de equipos informáticos o actividades pedagógicas integradas a las TIC de equipos 
                                informáticos en instituciones públicas o privadas		

                        </label>
                        </td>
                        <td><br><input type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="A"> A <br><br>
                            <input type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="B"> B<br><br><br>
                            <input type="radio" name="experiencia" v-model="evaluacion1.criterio_cv_1" value="NO"> Ninguno 
                        </td>
                    </tr>
                    <tr >
                        <th scope="row" style="background-color: #95D0FC;">CUMPLE CON LO SOLICITADO</th>
                        <td 
                            v-if="evaluacion1.rnp == 'SI' &&  (evaluacion1.criterio_cv_1 == 'A' ||  evaluacion1.criterio_cv_1 == 'B') && evaluacion1.profesion == 'SI'">
                            <label :value="evaluacion1.estado_cv">SI</label> </td>
                        <td v-else><label :value="evaluacion1.estado_cv">NO</label></td>

                    </tr>
                    
                    <tr>
                        <th scope="row" colspan="2"></th>
                    </tr>
                    <tr>
                        <th scope="row" style="background-color: #95D0FC;">CRITERIOS</th>
                        <th style="background-color: #95D0FC;">
                            <P>Calificacion (Segun puntuacion de los criterios)</P>
                        </th>
                    </tr>
                    <tr>
                        <td scope="row">Formacion academica</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' &&  (evaluacion1.criterio_cv_1 == 'A' ||  evaluacion1.criterio_cv_1 == 'B') && evaluacion1.profesion == 'SI'">
                            <input type="text" name="formacion" v-model="evaluacion1.grado" @keypress="soloNumeros5($event)"
                                maxlength="1"> </td>
                    </tr>
                    <tr>
                        <td scope="row">Experiencia en evaluaciones estandarizadas de estudiantes como supervisor de almacén y sistemas, o supervisor de almacén y 
                            soporte informático, o supervisor de inventario y procesos administrativos, o supervisor de sistemas y almacén</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' &&  (evaluacion1.criterio_cv_1 == 'A' ||  evaluacion1.criterio_cv_1 == 'B') && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expAplicacion" v-model="evaluacion1.criterio_cv_2"
                                @keypress="soloNumeros5($event)" maxlength="1"> </td>
                    </tr>
                    <tr>
                        <td scope="row">Experiencia en el soporte técnico  mesa de ayuda, asistencia técnica de equipos informáticos o actividades pedagógicas 
                            integradas a las TIC de equipos informáticos en instituciones públicas o privadas</td>
                        <td
                            v-if="evaluacion1.rnp == 'SI' &&  (evaluacion1.criterio_cv_1 == 'A' ||  evaluacion1.criterio_cv_1 == 'B') && evaluacion1.profesion == 'SI'">
                            <input type="text" name="expAplicacion" v-model="evaluacion1.criterio_cv_3"
                                @keypress="soloNumeros5($event)" maxlength="1"> </td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-secondary float-right" type="button" @click="guardar">
                Guardar
            </button>
        </div>
        <hr>
    </div>
</template>

<script>


export default {
    name: 'Ayudas',
    components: {
    },
    data() {
        return {
            numeroDni: '',
            idConvocatoria: '',
            listarConvocatorias: [],
            filtrosBusqueda: {
                tipo: '',
                orden: 'asc',
                ordenPor: 'apellido_pat',
            },
            mostrar: {
                apellidos: '',
                nombres: '',
                provincia: '',
                num_registro: '',
            },
            evaluacion: {
                certificado_lengua: '',
                created_at: '',
                criterio_cv_1: '',
                criterio_cv_2: '',
                criterio_cv_3: '',
                criterio_cv_4: '',
                criterio_cv_5: '',
                criterio_cv_6: '',
                datos: '',
                documento: '',
                estado_cv: '',
                grado: '',
                id: '',
                num_registro: '',
                office: '',
                profesion: '',
                rnp: '',
            },
            evaluacion1: {},
        }
    },
    created() {
        this.listarConvocatoria();
    },
    methods: {
        buscar() {
            axios.get("api/evaluacion/evaluar/" + this.numeroDni + "/" + this.idConvocatoria)
                .then((response) => {
                    let data = response.data;
                    if (data.flag==1) {
                        this.$toastr.s(response.data.message);
                    this.evaluacion = data.data;
                    this.evaluacion1 = this.evaluacion[0];
                    } else {
                        this.$toastr.e(response.data.message);
                    }
                    
                    //         this.listaAlumnos.data=response.data.lista;
                    //         console.log(this.listaAlumnos);
                    // let dni = response.data;
                    // console.log(dni);
                })
                .catch((error) => {
                    console.log("error");
                });
        },
        guardar() {
            axios.post("api/evaluacion/guardar", this.evaluacion1)
                .then((response) => {
                    let data = response.data;
                    this.$toastr.s(response.data.message);
                    console.log(data);
                    this.evaluacion1 = '';
                    this.numeroDni = '';
                })
                .catch((error) => {
                    console.log("error");
                });
        },
        soloNumeros4($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if ((keyCode < 48 || keyCode > 52)) { // 46 is dot
                $event.preventDefault();
            }

        },
        soloNumeros5($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if ((keyCode < 48 || keyCode > 53)) { // 46 is dot
                $event.preventDefault();
            }

        },
        soloNumeros6($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if ((keyCode < 48 || keyCode > 56)) { // 46 is dot
                $event.preventDefault();
            }

        },
        listarConvocatoria() {
            axios.get("api/convocatoria/listar")
                .then((response) => {
                    let data = response.data;
                    this.listarConvocatorias = data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$toastr.e(error.response.data.message);
                });
        },
    },
}
</script>
<style >.table1 th,
.table1 td {
    padding: 0.2rem;
    vertical-align: top;
    border-top: 1px solid #b5d30d;
}</style>
