<template>
    <div class="p-3 pt-5">
        <div class="col-md-12" style="margin-left: -15px">

            <h4 class="text-color-2 mb-3">PRESELECCION: Reporte de  CV Evaluados</h4>
        </div>
        <br />
        <div class="form-group col-12">
            <div class=" col-4">
                <p class="m-0">
                    <strong>Convocatoria</strong>
                </p>
                <select name="convocatoria" v-model="idConvocatoria" class="form-control" data-vv-as="Convocatoria"
                    placeholder="Seleccione Convocatoria" v-validate="'required'">
                    <option v-for="row in listarConvocatorias" :key="row.id" :value="row.id" v-text="row.nombre"></option>
                </select>
                <!-- <span class="text-danger">{{errors.first("form_registro.nivel")}}</span> -->

                <button class="btn btn-outline-secondary float-right" type="button" @click="mostrar">
                    Mostrar
                </button>
                <button class="btn btn-outline-secondary float-right" type="button" @click="exportar">
                    reporte
                </button>
            </div>
        </div>
        <div v-if="idConvocatoria == 3">
            <div  class="table-responsive" >
            <vue-good-table :columns="listarRegistrosTAP.columns" :rows="listarRegistrosTAP.data" :search-options="{
                enabled: true,
                placeholder: 'Buscar en la tabla',
            }" :pagination-options="{
                enabled: true,
                mode: 'pages',
                nextLabel: 'Sig',
                prevLabel: 'Ant',
                rowsPerPageLabel: 'Registros por página',
                ofLabel: 'de',
                pageLabel: 'Página', // for 'pages' mode
                allLabel: 'Todo',
            }">
            </vue-good-table>
        </div>
        </div>
        <div v-if="idConvocatoria == 4">
            <div class="table-responsive" >
            <vue-good-table :columns="listarRegistrosCP.columns" :rows="listarRegistrosCP.data" :search-options="{
                enabled: true,
                placeholder: 'Buscar en la tabla',
            }" :pagination-options="{
                enabled: true,
                mode: 'pages',
                nextLabel: 'Sig',
                prevLabel: 'Ant',
                rowsPerPageLabel: 'Registros por página',
                ofLabel: 'de',
                pageLabel: 'Página', // for 'pages' mode
                allLabel: 'Todo',
            }">
            </vue-good-table>
        </div>
        </div>
        <div v-if="idConvocatoria==5">
            <div class="table-responsive" >
            <vue-good-table :columns="listarRegistrosSPA.columns" :rows="listarRegistrosSPA.data" :search-options="{
                enabled: true,
                placeholder: 'Buscar en la tabla',
            }" :pagination-options="{
                enabled: true,
                mode: 'pages',
                nextLabel: 'Sig',
                prevLabel: 'Ant',
                rowsPerPageLabel: 'Registros por página',
                ofLabel: 'de',
                pageLabel: 'Página', // for 'pages' mode
                allLabel: 'Todo',
            }">
            </vue-good-table>
        </div>
    </div>
        <div v-if="idConvocatoria==6">
           <div class="table-responsive" >
            <vue-good-table :columns="listarRegistrosSAS.columns" :rows="listarRegistrosSAS.data" :search-options="{
                enabled: true,
                placeholder: 'Buscar en la tabla',
            }" :pagination-options="{
                enabled: true,
                mode: 'pages',
                nextLabel: 'Sig',
                prevLabel: 'Ant',
                rowsPerPageLabel: 'Registros por página',
                ofLabel: 'de',
                pageLabel: 'Página', // for 'pages' mode
                allLabel: 'Todo',
            }">
            </vue-good-table>
        </div> 
        </div>
        
    </div>
</template>
  
<script>
import Helper from "../../services/helper";
import Crypt from "../../services/Crypt";



export default {
    name: "Ayudas",
    components: {},
    data() {
        return {
            listarRegistrosTAP: {
                data: [],
                columns: [
                    { label: "Sede Regional", field: "region" },
                    { label: "Sede Provincial", field: "provincia" },
                    { label: "N° DNI", field: "documento" },
                    { label: "Apellidos y nombres", field: "datos" },
                    { label: "F. Nacimiento", field: "fecha_nac" },
                    { label: "Profesion", field: "per_profesion" },
                    { label: "Grado", field: "per_grado" },
                    { label: "RNP", field: "rnp" },
                    { label: "Minimo Grado", field: "eva_profesion" },
                    { label: "Minimo 6 meses", field: "criterio_cv_6" },
                    { label: "Minimo 1 experiencia", field: "criterio_cv_1" },
                    { label: "Manejo de herramientas", field: "office" },
                    { label: "Maximo Grado Alcanzado", field: "grado" },
                    { label: "Experiencia Especifica", field: "criterio_cv_2" },
                    { label: "Numero Registro", field: "num_registro" },
                    { label: "Fecha Registro", field: "created_at" },
                ],
                total: 0,
                filtrosBusqueda: {
                    tipo: "",
                    orden: "asc",
                    ordenPor: "datos",
                    regPagina: "10",
                    cargo: '',
                },

                deshabilitarEdicion: false,
            },
            listarRegistrosCP: {
                data: [],
                columns: [
                    { label: "Sede Regional", field: "region" },
                    { label: "Sede Provincial", field: "provincia" },
                    { label: "N° DNI", field: "documento" },
                    { label: "Apellidos y nombres", field: "datos" },
                    { label: "F. Nacimiento", field: "fecha_nac" },
                    { label: "Profesion", field: "per_profesion" },
                    { label: "Grado", field: "per_grado" },
                    { label: "RNP", field: "rnp" },
                    { label: "Minimo Grado", field: "eva_profesion" },
                    { label: "Minimo 2 experiencias", field: "criterio_cv_1" },
                    { label: "Maximo Grado Alcanzado", field: "grado" },
                    { label: "Experiencia Especifica", field: "criterio_cv_2" },
                    { label: "Experiendia general 1", field: "criterio_cv_3" },
                    { label: "Experiendia general 2", field: "criterio_cv_4" },
                    { label: "Numero Registro", field: "num_registro" },
                    { label: "Fecha Registro", field: "created_at" },
                ],
                total: 0,
                filtrosBusqueda: {
                    tipo: "",
                    orden: "asc",
                    ordenPor: "datos",
                    regPagina: "10",
                    cargo: '',
                },

                deshabilitarEdicion: false,
            },
            listarRegistrosSPA: {
                data: [],
                columns: [
                    { label: "Sede Regional", field: "region" },
                    { label: "Sede Provincial", field: "provincia" },
                    { label: "N° DNI", field: "documento" },
                    { label: "Apellidos y nombres", field: "datos" },
                    { label: "F. Nacimiento", field: "fecha_nac" },
                    { label: "Profesion", field: "per_profesion" },
                    { label: "Grado", field: "per_grado" },
                    { label: "RNP", field: "rnp" },
                    { label: "Minimo Grado", field: "eva_profesion" },
                    { label: "PERFIL", field: "criterio_cv_1" },
                    { label: "Formacion Academica", field: "grado" },
                    { label: "Experiencia Especifica", field: "criterio_cv_2" },
                    { label: "Experiendia general 1", field: "criterio_cv_3" },
                    { label: "Experiendia general 2", field: "criterio_cv_4" },
                    { label: "Numero Registro", field: "num_registro" },
                    { label: "Fecha Registro", field: "created_at" },
                ],
                total: 0,
                filtrosBusqueda: {
                    tipo: "",
                    orden: "asc",
                    ordenPor: "datos",
                    regPagina: "10",
                    cargo: '',
                },

                deshabilitarEdicion: false,
            },
            listarRegistrosSAS: {
                data: [],
                columns: [
                    { label: "Sede Regional", field: "region" },
                    { label: "Sede Provincial", field: "provincia" },
                    { label: "N° DNI", field: "documento" },
                    { label: "Apellidos y nombres", field: "datos" },
                    { label: "F. Nacimiento", field: "fecha_nac" },
                    { label: "Profesion", field: "per_profesion" },
                    { label: "Grado", field: "per_grado" },
                    { label: "RNP", field: "rnp" },
                    { label: "Minimo Grado", field: "eva_profesion" },
                    { label: "PERFIL", field: "criterio_cv_1" },
                    { label: "Formacion Academica", field: "grado" },
                    { label: "Experiencia Especifica", field: "criterio_cv_2" },
                    { label: "Experiendia general 1", field: "criterio_cv_3" },
                    { label: "Numero Registro", field: "num_registro" },
                    { label: "Fecha Registro", field: "created_at" },
                ],
                total: 0,
                filtrosBusqueda: {
                    tipo: "",
                    orden: "asc",
                    ordenPor: "datos",
                    regPagina: "10",
                    cargo: '',
                },

                deshabilitarEdicion: false,
            },
            idConvocatoria: '',


            listarConvocatorias: [],
        };
    },
    created() {
        this.listarConvocatoria();
    },
    methods: {
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
        mostrar() {
            axios
                .get("api/evaluacion/mostrarReporte/" + this.idConvocatoria)
                .then((response) => {
                    switch (this.idConvocatoria) {
                        case 3:
                        this.listarRegistrosTAP.data = response.data;                            
                        break;
                        case 4:
                        this.listarRegistrosCP.data = response.data;
                        break;
                        case 5:
                        this.listarRegistrosSPA.data = response.data;
                        break;
                        case 6:
                        this.listarRegistrosSAS.data = response.data;
                        break;
                    }
                    
                })
                .catch((error) => {
                    console.log(error);
                    this.$toastr.e(error.response.data.message);
                });
        },
        guardar() {
            this.$validator.validateAll("form_registro").then((result) => {
                if (result) {
                    axios
                        .post("api/grado/crear", this.modal.grado)
                        .then((response) => {
                            this.$toastr.s(response.data.message);
                            $("#modal-grado").modal("hide");
                            this.listarGrado();
                        })
                        .catch((error) => {
                            console.log(error);
                            this.$toastr.e(error.response.data.message);
                        });
                }
            });
        },
        excel() {
                    axios
                        .get("api/export/reporte_cv", this.idConvocatoria)
                        .then((response) => {
                            this.$toastr.s(response.data.message);
                        })
                        .catch((error) => {
                            console.log(error);
                            this.$toastr.e(error.response.data.message);
                        });
        },
        exportar() {

            let usuario = Crypt.decrypt(this.$store.getters.getAuthUser('identificador'));
            switch (this.idConvocatoria) {
                        case 3:
                        
                        this.listarRegistrosTAP.filtrosBusqueda.cargo=usuario;
                        let urlTAP = process.env.MIX_APP_URL +"/exportar/reporteEvaluacionTAP" +
                        Helper.getFilterURL(this.listarRegistrosTAP.filtrosBusqueda);
                        window.open(urlTAP);                            
                        break;
                        case 4:
                        this.listarRegistrosCP.filtrosBusqueda.cargo=usuario;
                        let urlCP =process.env.MIX_APP_URL +"/exportar/reporteEvaluacionCP" +
                        Helper.getFilterURL(this.listarRegistrosCP.filtrosBusqueda);
                        window.open(urlCP); 
                        break;
                        case 5:
                        this.listarRegistrosSPA.filtrosBusqueda.cargo=usuario;
                        let urlSPA =
                            process.env.MIX_APP_URL +"/exportar/reporteEvaluacionSPA" +
                        Helper.getFilterURL(this.listarRegistrosSPA.filtrosBusqueda);
                        window.open(urlSPA); 
                        break;
                        case 6:
                        this.listarRegistrosSAS.filtrosBusqueda.cargo=usuario;
                        let urlSAS =
                            process.env.MIX_APP_URL +"/exportar/reporteEvaluacionSAS" +
                        Helper.getFilterURL(this.listarRegistrosSAS.filtrosBusqueda);
                        window.open(urlSAS);
                        break; 
                        default:
                        this.$toastr.e("Seleccione el Cargo  en convocatoria");

                            break;
                    }
        },
    },
};
</script>
<style ></style>
  