<template>
    <div class="principal">
        <nav class="navbar navbar-expand-lg fixed-top shadow header-personal">
        <!-- <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light shadow" style="border: 3px solid #2FEAF0"> -->
            <a class="navbar-brand" href="#"  @click.prevent="redireccionarAdmin('/admin/inicio','/admin/inicio')">
                
                <img :src="$baseUrlVue('img/logo/logotipo-version-5.png')"  height="40" class="d-inline-block align-top" alt="">
            </a>
            <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
                <ul class="navbar-nav mr-auto ml-2">
                    
                </ul>
               
                <div class="row">
                    <span class="row col-9 col-sm-10 col-md-9 col-lg-10 text-right">
                        <strong class="col-12 align-self-center"  v-text="getUserName('nombres')"></strong>
                    </span>
                    <div class="btn-group ml-0 col-3 col-sm-2 col-md-3 col-lg-2 text-center">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="avatar avatar-online">
                            <img :src="$baseUrlVue(getAvatar())" class="rounded-circle" alt="avatar" width="40" height="40"/>
                        </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button class="dropdown-item" type="button" @click="cerrarSesion">Cerrar sesión</button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <router-view :key="$route.params.id" :tipo="usuario.tipo"> </router-view>
        </div>
    </div>
</template>

<script>

import Crypt from "../../services/Crypt";

export default {
    name: 'Main',
    data(){
        return {
            usuario: {
            },
        }
    },
    created(){
        this.init();
    },
    methods:{
        init(){
            if(!this.$store.getters.getAuthUser('identificador')){
                axios.get("api/auth/user")
                .then((response) => {
                    console.log(response);
                    let usuario = response.data;
                    // if(usuario.es_admin==false){
                
                    // }
                    this.$store.dispatch('setAuthUserDetail',{//aqui extrae la info el usuario y lo asigno a la variable
                        identificador: Crypt.encrypt(usuario.id),
                        //rol: Crypt.encrypt(usuario.id_rol),
                        email: usuario.email,
                        nombres: usuario.persona.nombres,
                        apellidos: usuario.persona.apellido_pat+' '+usuario.persona.apellido_mat,
                        usuario: usuario.usuario,
                        avatar: usuario.avatar,
                        rol: usuario.tipo_usuario
                    });

                })
                .catch((error) => {
                    console.log(error);
                    this.$toastr.e(error.response.data.message);
                    this.cerrarSesion();
                    return false;
                });
            }
        },
        validarPermisos(rol){
            if(this.$store.getters.getAuthUser('rol') == rol){
                return true;
            }
            return false;
        },
        redireccionarAdmin(name){
            console.log(name+"dasd");
            this.$router.push({name: name})
        },
        getUserName(name){
            return this.$store.getters.getAuthUserNameComplete();
        },
        getAvatar(){
            let avatar = this.$store.getters.getAuthUser('avatar');
            if(avatar)
                return 'user/users/' + avatar;
            return 'img/auth/user-alt.png';
        },
        cerrarSesion(){
            axios.post('api/auth/logout').then(response => {
                localStorage.removeItem('token_linkedin');
                localStorage.removeItem('token_laravel');
                this.$store.dispatch('resetAuthUserDetail');
                this.$router.push('/login');
                axios.defaults.headers.common['Authorization'] = null;
                this.$toastr.s(response.data.message);
                return true;
            }).catch(error => {
                console.log(error);
                return false;
            });
        },

    }
}
</script>
