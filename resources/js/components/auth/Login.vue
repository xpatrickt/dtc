<template>
    <div class="col-12 login-admin d-flex align-items-center justify-content-center vh-100 " :style="'background-image: url('+$baseUrlVue('img/otros/em.jpg')+'; background-size: cover;'">
        <div class="col-11 col-sm-8 col-md-6 col-lg-4 col-xl-4 p-0 border border-grey bg-white rounded-lg mt-3">
            <div class="col-12">
                <div class="border-grey border-lighten-3 m-0">
                    <div class="card-content">
                        <div class="card-body pb-0">
                            <div class="">
                                <form class="form" data-vv-scope="form_registro">
                                    <h4 class="text-center">
                                    <img :src="$baseUrlVue('img/logo/inei_logo.png')"  height="80" class="logo-admin" alt="">
                                    </h4>
                                    <br>
                                    <div class="form-group">
                                            Usuario
                                        <input type="text"
                                        class="form-control"
                                        data-vv-as="Nombre de Usuario"
                                        name="usuario"
                                        v-model="admin.usuario"
                                        >
                                    </div>
                                    <div class="form-group">
                                            Contraseña
                                        <input type="password"
                                        class="form-control"
                                        data-vv-as="Contraseña"
                                        name="usuario"
                                        v-model="admin.password"
                                        >
                                    </div>
                                    <div class="form-group mt-2">
                                        <button type="button" class="btn btn-primary col" @click="iniciarSesion" >
                                            Ingresar
                                        </button>
                                    </div><br>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>

import Crypt from "../../services/Crypt";

export default {
    name: "LoginAdmin",
    data(){
        return {
            admin: {
                usuario: '',
                password: '',
            }
        }
    },
    methods:{
        iniciarSesion(){
            axios.post("api/auth/admin", this.admin)
            .then((response) => {
                this.$toastr.s(response.data.message);
                localStorage.setItem("token_laravel", response.data.token_laravel);
                axios.defaults.headers.common["Authorization"] = "Bearer " + localStorage.getItem("token_laravel");
                axios.get("api/auth/user")
                .then((response) => {
                    console.log(response);
                    let usuario = response.data;
                    this.$store.dispatch('setAuthUserDetail',{//aqui extrae la info el usuario y lo asigno a la variable
                        identificador: Crypt.encrypt(usuario.id),
                        email: usuario.email,
                        nombres: usuario.persona.nombres,
                        apellidos: usuario.persona.apellido_pat+' '+usuario.persona.apellido_mat,
                        usuario: usuario.usuario,
                        avatar: usuario.avatar,
                        rol: usuario.tipo_usuario
                    });
                    this.$router.push('/admin/inicio'); 
                })
                .catch((error) => {
                    console.log(error);
                    this.$toastr.e(error.response.data.message);
                });
            })
            .catch((error) => {
                console.log(error);
                this.$toastr.e(error.response.data.message);
            });
        },
    }
}
</script>
<style>

</style>