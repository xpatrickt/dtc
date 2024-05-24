<template>
    <div class="p-3 pt-5">
        <div class="col-md-12" style="margin-left: -15px;">
            <h4 class="text-color-2 mb-3">REGISTRO DE RECEPCION DE CURRICULUM</h4>
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
                    <label for="">Ingrese Número de DNI</label>
                    <input type="text" name="" class="form-control" v-model="numeroDni" @keyup.enter="buscar">
                </div>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="">DOCUMENTO</label>
                    <input type="text" name="" class="form-control" v-model="mostrar.documento" disabled>
                </div><div class="form-group">
                    <label for="">APELLIDOS</label>
                    <input type="text" name="" class="form-control" v-model="mostrar.apellidos" disabled>
                </div>
                <div class="form-group">
                    <label for="">NOMBRES</label>
                    <input type="text" name="" class="form-control" v-model="mostrar.nombres" disabled>
                </div>
                <div class="form-group" disabled>
                    <label for="">SEDE PROVINCIAL</label>
                    <input type="text" name="" class="form-control" v-model="mostrar.provincia" disabled>
                </div>
                <div class="form-group">
                    <label for="">REGISTRO</label>
                    <input type="text" name="" class="form-control" v-model="mostrar.num_registro" disabled>
                </div>
            </div>
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
                documento : '',
                apellidos: '',
                nombres: '',
                provincia: '',
                num_registro: '',
            }
        }
    },
    created() {
        this.listarConvocatoria();
    },
    methods: {
        buscar() {
            axios.get("api/asistencia/ver/" + this.numeroDni + "/" + this.idConvocatoria)
                .then((response) => {
                    let data = response.data;
                    this.mostrar.documento=this.numeroDni;
                    if (response.data.flag == 0) {
                        this.$toastr.s(response.data.message);
                        this.mostrar.apellidos = data.persona[0].apellido_pat + " " + data.persona[0].apellido_mat;
                        this.mostrar.documento = data.persona[0].documento;
                        this.mostrar.nombres = data.persona[0].nombres;
                        this.mostrar.provincia = data.proceso[0].id_sede_provincial;
                        this.mostrar.num_registro = data.asistencia[0].aula;
                        console.log(data);
                        this.numeroDni='';
                    } else {
                        this.$toastr.w(response.data.message);
                        this.mostrar.apellidos = data.persona[0].apellido_pat + " " + data.persona[0].apellido_mat;
                        this.mostrar.nombres = data.persona[0].nombres;
                        this.mostrar.provincia = data.proceso[0].id_sede_provincial;
                        this.mostrar.num_registro = data.asistencia[0].aula;
                        console.log(data);
                        this.numeroDni='';

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
<style ></style>
