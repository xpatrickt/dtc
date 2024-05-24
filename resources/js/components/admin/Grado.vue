<template>
  <div class="p-3 pt-5">
    <div class="col-md-12" style="margin-left: -15px">

      <h4 class="text-color-2 mb-3">CONVOCATORIA: Reporte de Recepcion de CV</h4>
    </div>
    <br />
    <div class="form-group col-12">
      <div class=" col-4 col-md-4">
        <p class="md-12">
          <strong>Convocatoria</strong>
        </p>
        <select lass=" col-md-4" name="convocatoria" v-model="idConvocatoria" class="form-control" data-vv-as="Convocatoria"
          placeholder="Seleccione Convocatoria" v-validate="'required'">
          <option v-for="row in listarConvocatorias" :key="row.id" :value="row.id" v-text="row.nombre"></option>
        </select>
        <!-- <span class="text-danger">{{errors.first("form_registro.nivel")}}</span> -->
      
        <button class="btn btn-outline-secondary float-right" type="button" @click="mostrar">
          Mostrar
        </button>
        <button class="btn btn-outline-secondary float-right" type="button" @click="exportar">
                    Reporte
                </button>
      </div>
    </div>
    <div class="table-responsive">
      <vue-good-table :columns="listarRegistros.columns" :rows="listarRegistros.data" :search-options="{
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
        <template slot="table-row" slot-scope="props">
          <span v-if="props.column.field == 'options'">
            <button class="btn btn-outline-success btn-sm btn-icon" @click.prevent="agregarCurso()"
              data-toggle="tooltip" title="Agregar">
              <i class="fas fa-plus"></i> Cursos
            </button>
            <button class="btn btn-outline-secondary btn-sm btn-icon" @click.prevent="ver()"
              data-toggle="tooltip" title="Ver">
              <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-outline-info btn-sm btn-icon" @click.prevent="editar()"
              data-toggle="tooltip" title="Editar">
              <i class="fas fa-pencil-alt"></i>
            </button>
            <button class="btn btn-outline-danger btn-sm btn-icon" @click.prevent="eliminar()"
              data-toggle="tooltip" title="Eliminar">
              <i class="fas fa-trash-alt"></i>
            </button>
          </span>
        </template>
      </vue-good-table>
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
      listarRegistros: {
        data: [],
        columns: [
          { label: "Sede Regional", field: "region" },
          { label: "Sede Provincial", field: "provincia" },
          { label: "N° Documento", field: "documento" },
          { label: "Apellido Paterno", field: "apellido_pat" },
          { label: "Apelldio Materno", field: "apellido_mat" },
          { label: "Nombres", field: "nombres" },
          { label: "Numero Registro", field: "num_registro" },
          { label: "Fecha Registro", field: "created_at" },
        ],
        total: 0,
        filtrosBusqueda: {
          tipo: "",
          orden: "asc",
          ordenPor: "num_registro",
          regPagina: "10",
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
    mostrar(){
          console.log("llego");
          axios
            .get("api/evaluacion/mostrar/"+ this.idConvocatoria)
            .then((response) => {
              this.listarRegistros.data=response.data;
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
    exportar() {
      let usuario = Crypt.decrypt(this.$store.getters.getAuthUser('identificador'));
      switch (this.idConvocatoria) {
                        case 3:
                        
                        this.listarRegistros.filtrosBusqueda.cargo=usuario;
                        let urlTAP = process.env.MIX_APP_URL +"/exportar/reporteRecepcionTAP" +
                        Helper.getFilterURL(this.listarRegistros.filtrosBusqueda);
                        window.open(urlTAP);                            
                        break;
                        case 4:
                        this.listarRegistros.filtrosBusqueda.cargo=usuario;
                        let urlCP =process.env.MIX_APP_URL +"/exportar/reporteRecepcionCP" +
                        Helper.getFilterURL(this.listarRegistros.filtrosBusqueda);
                        window.open(urlCP); 
                        break;
                        case 5:
                        this.listarRegistros.filtrosBusqueda.cargo=usuario;
                        let urlSPA =
                            process.env.MIX_APP_URL +"/exportar/reporteRecepcionSPA" +
                        Helper.getFilterURL(this.listarRegistros.filtrosBusqueda);
                        window.open(urlSPA); 
                        break;
                        case 6:
                        this.listarRegistros.filtrosBusqueda.cargo=usuario;
                        let urlSAS =
                            process.env.MIX_APP_URL +"/exportar/reporteRecepcionSAS" +
                        Helper.getFilterURL(this.listarRegistros.filtrosBusqueda);
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
