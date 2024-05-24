<template>
  <div class="col-12">
      <div class="border-grey border-lighten-3 m-0">
        <div class="card-content">
          <div class="card-body pb-0">
              <div class="row">
                    <div class="col-md-4">
                        <div class="border border-gray" style="width:100px;height:100px;">
                            <img :src="$baseUrlVue(getAvatar())" class="img-responsive" style="max-width:100px;max-height:100px;">
                        </div>
                    </div>
                    <div class="col-md-8">
                        Foto de perfil
                        <br>
                        <span id="fileselector">
                            <label class="btn btn-outline-secondary">
                                <input type="file"  @change="previewAvatar" id="avatarUpload" class="upload-button" accept="image/*" pattern="([^\s]+(\.(?i)(jpg|png|gif|bmp))$)" v-show="false">
                                <span> Elegir  una foto</span>
                            </label>
                        </span>
                    </div>
              </div>
              <div class="">
                    <form class="form" data-vv-scope="form_registro">
                        <div class="form-group">
                            <p class="m-0">
                                <strong>Nombre de Usuario</strong>
                            </p>
                            <small>
                                Su nombre se mostrará públicamente.
                            </small>

                            <input type="text"
                            v-model="register.usuario"
                            class="form-control"
                            data-vv-as="Nombre de Usuario"
                            name="usuario"
                            v-validate="'required'">
                            <span class="text-danger">{{errors.first("form_registro.usuario")}}</span>
                        </div>
                        <div class="form-group">
                            <p class="m-0">
                                <strong>Profesión / Ocupación</strong>
                            </p>
                            <small>
                                Ayudará a brindarte contenido relevante.
                            </small>
                            <multiselect
                                v-model="ocupacionesSel"
                                :options="comboOcupacion"
                                data-vv-as="Ocupación"
                                name="ocupacion"
                                v-validate="'required'"
                                placeholder="-- Seleccione --"
                                label="nombre"
                                track-by="id"
                                :close-on-select="true"
                                :searchable="true"
                                :show-labels="false"
                                :multiple="false"
                            >
                            </multiselect>
                            <span class="text-danger">{{errors.first("form_registro.ocupacion")}}</span>
                        </div>
                        <div class="form-group">
                            <p class="m-0">
                                <strong>Puesto actual</strong>
                            </p>
                            <small>
                                Ingrese el puesto actual que se encuentra.
                            </small>
                            <input type="text"
                            v-model="register.puesto_actual"
                            class="form-control"
                            data-vv-as="Puesto actual"
                            placeholder="Puesto actual"
                            name="puesto_actual"
                            v-validate="'required'">
                            <span class="text-danger">{{errors.first("form_registro.puesto_actual")}}</span>
                        </div>
                        <div class="form-group">
                            <p class="m-0">
                                <strong>Conocimiento / Experiencia</strong>
                            </p>
                            <small>
                               Ayudará a mostrarte contenido relevante.
                            </small>
                            <multiselect
                                v-model="experienciasSel"
                                :options="comboEtiquetas"
                                data-vv-as="Experiencia"
                                name="experiencia"
                                v-validate="'required'"
                                placeholder="-- Seleccione --"
                                label="nombre"
                                track-by="id"
                                :close-on-select="false"
                                :searchable="true"
                                :show-labels="false"
                                :multiple="true"

                            >
                            </multiselect>
                            <span class="text-danger">{{errors.first("form_registro.experiencia")}}</span>
                        </div>
                        <div class="form-group">
                            <p class="m-0">
                                <strong>Temas de interés</strong>
                            </p>
                            <small>
                                Ayudará a mostrarle contenido de su interés.
                            </small>
                            <multiselect
                                v-model="temasInteresSel"
                                :options="comboEtiquetas"
                                data-vv-as="Temas de interés"
                                name="interes"
                                v-validate="'required'"
                                placeholder="-- Seleccione --"
                                label="nombre"
                                track-by="id"
                                :close-on-select="false"
                                :searchable="true"
                                :show-labels="false"
                                :multiple="true"
                            >
                            </multiselect>
                            <span class="text-danger">{{errors.first("form_registro.interes")}}</span>
                        </div>
                        <div class="form-group">
                            <p class="m-0">
                                <strong>Apoyo</strong>
                            </p>
                            <small>
                                Sube tu QR para recibir donaciones.
                            </small>
                            <div class="row m-0">
                                <select name="qr" id="qr" class="col-7" v-model="register.pago">
                                    <option value="">-- Seleccione --</option>
                                    <option value="yape"> YAPE </option>
                                    <option value="plin"> PLIN </option>
                                    
                                </select>
                                <div class="col-5">
                                    <span id="fileselector">
                                        <label class="btn btn-outline-secondary">
                                            <input type="file"  @change="previewQR" id="qrUpload" class="upload-button" accept="image/*" pattern="([^\s]+(\.(?i)(jpg|png|gif|bmp))$)" v-show="false" :disabled="register.pago==''">
                                            <span> Subir QR</span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <span class="text-danger">{{errors.first("form_registro.qr")}}</span>
                        </div>

                        <template v-if="esEdicion">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" v-model="register.suscribir" id="check-suscribirse">
                                <label class="form-check-label" for="check-suscribirse">
                                    <small>
                                        Suscribete para recibir actualizaciones, publicaciones anucios y novedades.
                                    </small>
                                </label>
                            </div>
                            <div class="form-group mt-2">
                                <button class="btn btn-primary col" @click.prevent="registrarPreferencias">
                                    Continuar
                                </button>
                            </div>
                            <small>
                                Al crear la cuenta, acepta todos los
                                <a href="" target="_blank">términos  del servico, políticas de privacidad y políticas de las cookies</a>
                            </small>
                        </template>
                        <template v-else>
                            <div class="form-group mt-2">
                                <button class="btn btn-primary col" @click.prevent="registrarPreferencias">
                                    Actualizar
                                </button>
                            </div>

                        </template>

                    </form>
                </div>
            </div>
        </div>
      </div>
  </div>
</template>

<script>

import Multiselect from "vue-multiselect";

export default {
    components:{
        Multiselect,
    },
    name: "Register",
    props:{
        esEdicion:{
            type: Boolean,
            default: true
        }
    },
    data(){
      return {
        avatar: null,
        register:{
            usuario: null,
            ocupacion_id: null,
            experiencia: null,
            interes: null,
            suscribir: false,
            puesto_actual: '',
            pago: '',
        },
        ocupacionesSel:null,
        experienciasSel:null,
        temasInteresSel:null,
        comboOcupacion:[],
        comboEtiquetas:[],

    }
  },
  created(){
    this.getOcupaciones();
    this.getEtiquetas();
  },
  mounted(){
  },
  methods:{
    getInfoUsuario(){
        axios.get("api/auth/user")
        .then((response) => {
            this.register.usuario = (response.data.usuario ? response.data.usuario : response.data.nombres + ' ' + response.data.apellidos );
            this.register.pago = (response.data.pago ? response.data.pago : '' );
            this.register.puesto_actual = (response.data.puesto_actual ? response.data.puesto_actual : '' ); 
            this.ocupacionesSel = {id: response.data.ocupacion_id, nombre: response.data.ocupacion.nombre};

            const experiencias = response.data.experiencias.reduce((list, item) => {list.push(item.etiqueta_id); return list;}, []);
            const intereses = response.data.intereses.reduce((list, item) => {list.push(item.etiqueta_id); return list;}, []);
            let etiquetas = this.comboEtiquetas;

            this.experienciasSel = etiquetas.filter(item => experiencias.includes(item.id));

            this.temasInteresSel = etiquetas.filter(item => intereses.includes(item.id));
        })
        .catch((error) => {
            console.log(error);
            this.$toastr.e(error.response.data.message);
        });
    },
    getAvatar(){
        let avatar = this.$store.getters.getAuthUser('avatar');
        if(avatar)
            return 'user/users/' + avatar;
        return 'img/auth/user-alt.png';
    },
    getOcupaciones(){
        axios.get("api/ocupacion/llenar_combo")
        .then((response) => {
            this.comboOcupacion = response.data;
            this.getInfoUsuario();

        })
        .catch((error) => {
            toastr["error"](error.response.data.message);
        });
    },
    getEtiquetas(){
        axios.get("api/etiqueta/llenar_combo")
        .then((response) => {
            this.comboEtiquetas = response.data;
        })
        .catch((error) => {
            toastr["error"](error.response.data.message);
        });
    },
    registrarPreferencias(){
        this.$validator.validateAll('form_registro').then(result => {
            if (result) {
                let ocupacionesSel = this.ocupacionesSel;
                let experienciasSel = this.experienciasSel.map((elem) => elem.id);
                let temasInteresSel = this.temasInteresSel.map((elem) => elem.id);
                this.register.ocupacion_id = ocupacionesSel.id;
                this.register.experiencia = experienciasSel.join(',');
                this.register.interes = temasInteresSel.join(',');
                axios.post("api/usuario/registrar_preferencias", this.register)
                .then((response) => {
                    this.$toastr.s(response.data.message);
                    this.$router.push('/pages/inicio');
                })
                .catch((error) => {
                    console.log(error);
                    this.$toastr.e(error.response.data.message);
                });
            }
        });
    },
    previewAvatar(e) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
            return;
        this.createAvatar(files[0]);
    },
    createAvatar(file) {
        let reader = new FileReader();
        reader.onload = (e) => {
            this.avatar = e.target.result;
        };
        reader.readAsDataURL(file);
        this.subirAvatar();
    },
    subirAvatar() {
        let data = new FormData();
        data.append('avatar', $('#avatarUpload')[0].files[0]);
        axios.post('api/usuario/actualizar_avatar',data)
        .then(response => {//falta
            this.$store.dispatch('setAuthUserDetail',{
                avatar: response.data.avatar,
            });
            this.$toastr.s(response.data.message);
        }).catch(error => {
            console.log(error);
            this.$toastr.e(error.response.data.message);
        });
    },
    previewQR(e) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
            return;
        this.createQR(files[0]);
    },
    createQR(file) {
        let reader = new FileReader();
        reader.onload = (e) => {
            this.avatar = e.target.result;
        };
        reader.readAsDataURL(file);
        this.subirQR();
    },
    subirQR() {
        let data = new FormData();
        data.append('qr', $('#qrUpload')[0].files[0]);
        data.append('pago', this.register.pago);
        axios.post('api/usuario/actualizar_qr',data)
        .then(response => {//falta
            this.$store.dispatch('setAuthUserDetail',{
                qr: response.data.qr,
            });
            this.$toastr.s(response.data.message);
        }).catch(error => {
            console.log(error);
            this.$toastr.e(error.response.data.message);
        });
    },
   
  }//fin methods
}
</script>
