<template>
    <div class="principal">
        <nav class="navbar navbar-expand-lg fixed-top shadow header-personal">
        <!-- <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light shadow" style="border: 3px solid #2FEAF0"> -->
            <a class="navbar-brand" href="#"  @click.prevent="redireccionar('Inicio')">
                
                <img :src="$baseUrlVue('img/logo/logotipo-version-5.png')"  height="40" class="d-inline-block align-top" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
                <ul class="navbar-nav mr-auto ml-2">
                    
                </ul>
               
                <div class="row">
                    <span class="row col-6 col-sm-6 col-md-6 col-lg-9 text-right">
                        <strong class="col-12 align-self-center"  v-text="getUserName('nombres')"></strong>
                    </span>
                    <div class="btn-group ml-0 col-6 col-sm-6 col-md-6 col-lg-3 text-center">
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
            <div class="row flex-xl-nowrap">
                <div class="col-12 col-md-3 col-xl-2 bd-sidebar py-3">
                    <div class="py-3">
                        <nav class="bd-links collapse" id="bd-docs-nav">
                            <div class="bd-toc-item">
                               <ul class="list-unstyled ps-0" style="font-size:20px;">
                                    <li class="mb-1" >
                                        <a class="nav-link" href="#" @click.prevent="redireccionar('Inicio')"> 
                                            <i class="fa fa-home  me-3"></i>Inicio
                                        </a>
                                    </li>
                                    <li class="mb-1" >
                                        <a class="nav-link" href="#" @click.prevent="redireccionar('NotasDocente')"> 
                                            <i class="fa fa-file-alt  me-3"></i>Notas
                                        </a>
                                    </li>
                                    <li class="mb-1" >
                                        <a class="nav-link" href="#" @click.prevent="redireccionar('AsistenciaDocente')"> 
                                            <i class="fa fa-check-square  me-3"></i>Asistencia
                                        </a>
                                    </li>
                                    <li class="mb-1">
                                        <a class="nav-link" href="#"  @click.prevent="redireccionar('SalaReunionDocente')">
                                        <i class="fa fa-users me-3"></i>Sala reunión
                                         </a>
                                     </li>
                                     <li class="mb-1">
                                        <a class="nav-link" href="#"  @click.prevent="redireccionar('EvaluacionDocente')">
                                        <i class="fa fa-pencil-alt me-3"></i>Evaluación
                                         </a>
                                     </li>
                                     <li class="mb-1">
                                        <a class="nav-link" href="#"  @click.prevent="redireccionar('RpteAsistencia')">
                                        <i class="fa fa-file me-3"></i>Reporte de Asistencia
                                         </a>
                                     </li>
                                     <li class="mb-1">
                                        <a class="nav-link" href="#"  @click.prevent="redireccionar('RpteNotas')">
                                        <i class="fa fa-file me-3"></i>Reporte de Notas
                                         </a>
                                     </li>
                                </ul>
                            </div>            
                        </nav>
                    </div>
                </div>
                <!-- <div class="d-none d-xl-block col-xl-2 bd-toc">
                    <sidebar-aportar></sidebar-aportar>
                </div> -->
                <main class="col-12 col-md-9 col-xl-10 bd-content" role="main">
                    <router-view :key="$route.params.id"> </router-view>
                </main>
            </div>
            
        </div>

    </div>
</template>

<script>

import Crypt from "../../services/Crypt";

export default {
    name: 'Main',
    data(){
        return {
            usuario: '',
            infoAyuda:{
                    mostrar: false,
                    titulo: 'Ayuda',
                    contenido: [
                        {titulo: 'Recuerda ir al grano con la solución', imagen:'ayuda_inicio.png', descripcion: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In convallis augue ac porttitor interdum. Aenean cursus eros imperdiet ante aliquet imperdiet.', activo:'active',},
                        {titulo: 'Recuerda ir al grano con la solución v2', imagen:'ayuda_inicio.png', descripcion: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In convallis augue ac porttitor interdum.', activo:'false',},
                    ]
                }
        }
    },
    mounted(){
        this.init();
    },
    methods:{
        init(){
            if(!this.$store.getters.getAuthUser('identificador')){
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
        redireccionar(name){
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
