<template>
  <div class="col-12 d-flex align-items-center justify-content-center">
    <div class="col-11 col-sm-8 col-md-6 col-lg-4 col-xl-4 p-0 border border-grey bg-white rounded-lg ">
      <preferencias></preferencias>
    </div>
  </div>
</template>

<script>

import Multiselect from "vue-multiselect";
import Preferencias from '../templates/Preferencias';

export default {
    components:{
        Multiselect, Preferencias,
    },
    name: "Register",
    data(){
      return {
        avatar: null,
        register:{
            usuario: null,
            ocupacion_id: null,
            experiencia: null,
            interes: null,
            suscribir: false,
        },
        ocupacionesSel:null,
        experienciasSel:null,
        temasInteresSel:null,
        comboOcupacion:[],
        comboEtiquetas:[],

    }
  },
  mounted(){
    this.getOcupaciones();
    this.getEtiquetas();
  },
  methods:{
    obtenerInfoUsuario(){
        axios.get("api/auth/user")
        .then((response) => {
            console.log(response);
            register.usuario = response.data.usuario;
        })
        .catch((error) => {
            console.log(error);
            this.$toastr.e(error.response.data.message);
        });
    },
    getOcupaciones(){
        axios.get("api/ocupacion/llenar_combo")
        .then((response) => {
            this.comboOcupacion = response.data;
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
                    setTimeout(() => {
                        this.$router.push('/pages/inicio');
                    }, 1500);
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
            /* this.$store.dispatch('setAuthUserDetail',{
                avatar: response.data.persona.avatar
            }); */
            this.$toastr.s(response.data.message);
        }).catch(error => {
            console.log(error);
            this.$toastr.e(error.response.data.message);
        });
    },
  }//fin methods
}
</script>

<style scoped>
.minh-100 {
  height: 100vh;
}
</style>
