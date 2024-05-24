<template>
    <div>
        <nav class="navbar navbar-expand-lg fixed-top shadow header-personal navbar-inverse bg-inverse">
            <a class="navbar-brand" href="#"  @click.prevent="redireccionarMenu('inicio','/pages/inicio')">
                
                <img :src="$baseUrlVue('img/logo/logotipo-version-5.png')"  height="40" class="d-inline-block align-top menu-desktop" alt="">
                 
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars color-white" aria-hidden="true"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="form-inline my-2 my-lg-0 col-md-5 ml-3 mr-3 row">
                   <div class="input-group col-12 buscador-header">
                        <div class="input-group-prepend">
                            <span class="buscador-text" ><i class="fas fa-pencil-alt"></i></span>
                        </div>
                        <input
                        type="text"
                        class="buscador-input "
                        placeholder="Escribe tu pregunta o post"
                        v-model="redactar.texto"
                        @keyup="keyUpBuscarSugerencias"
                        @change="ocultarSugerencias"
                        @keyup.enter.prevent="redactarPregunta" >
                        <button class="buscador-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{redactar.descripcion}}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" @click.prevent="cambiarRedactar(1)">Pregunta</a>
                            <a class="dropdown-item" @click.prevent="cambiarRedactar(2)">Publicación</a>
                        </div>
                        
                        <div class="btn-redactar-pregunta">
                            <a href="#" target="_blank" @click.prevent="redactarPregunta" title="Continuar"><i class="fas fa-sign-in-alt"></i></a>
                        </div>

                        <div class="buscador-sugerencias" v-show="redactar.mostrarSugerencias">
                            <div class="text">
                                <a v-for="row in sugerencias" class="dropdown-item" href="#" @click.prevent="redireccionarDetallePregunta(redactar.tipo,row.pregunta_id)" :key="row.id" >
                                    <span v-text="row.titulo"></span>
                                </a>
                                <a class="dropdown-item" v-if="sugerencias.length == 0">
                                    <span>No encontramos preguntas sugeridas</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="navbar-nav mr-auto ml-2">
                    <li class="nav-item text-center" @click.prevent="redireccionarMenu('inicio','/pages/inicio')">
                        <a  href="#" class="nav-link" v-if="validarMenuActivo('inicio')" >
                            <img :src="$baseUrlVue('img/iconos/inicio.png')" alt="avatar" width="35" height="35"/>
                            <br>
                            <span class="text-color-3">Inicio</span>
                        </a>
                        <a  href="#" class="nav-link" v-else>
                            <img :src="$baseUrlVue('img/iconos/inicio-alt.png')" alt="avatar" width="35" height="35"/>
                            <br>
                            Inicio
                        </a>
                    </li>
                    <li class="nav-item text-center"  @click.prevent="redireccionarMenu('explorar','/pages/explorar/todos')">
                        <a  href="#" class="nav-link" v-if="validarMenuActivo('explorar')">
                            <img :src="$baseUrlVue('img/iconos/explorar-1.png')" alt="avatar" width="35" height="35"/>
                            <br>
                            <span class="text-color-3">Explorar</span>
                        </a>
                        <a  href="#" class="nav-link" v-else > 
                            <img :src="$baseUrlVue('img/iconos/explorar-alt-1.png')" alt="avatar" width="35" height="35"/>
                            <br>
                            Explorar
                        </a>
                    </li>
                    <li class="nav-item text-center"  @click.prevent="redireccionarMenu('aportar','/pages/aportar')">
                        <a href="#" class="nav-link"  v-if="validarMenuActivo('aportar')">
                            <img :src="$baseUrlVue('img/iconos/aportar-1.png')" alt="avatar" width="35" height="35"/>
                            <br>
                            <span class="text-color-3">Colaborar</span>
                        </a>
                            <a href="#" class="nav-link" v-else>
                            <img :src="$baseUrlVue('img/iconos/aportar-alt-1.png')" alt="avatar" width="35" height="35"/>
                            <br>
                            Colaborar
                        </a>
                    </li>

                     <li class="nav-item text-center ml-2 noti" @click.prevent="redireccionarMenu('notificaciones','/user/notificaciones')">
                        <a  href="#" class="nav-link position-relative">
                        <img v-if="validarMenuActivo('notificaciones')" :src="$baseUrlVue('img/iconos/notificacion.png')" alt="avatar" width="35" height="35"/>
                        <img v-else :src="$baseUrlVue('img/iconos/notificacion-alt.png')" alt="avatar" width="35" height="35"/>
                            <div class="contador-flotante" v-if="getUserName('notificaciones') > 0">
                               {{getUserName('notificaciones')}}
                            </div>
                            <br>
                        </a>
                    </li>
                    <li class="nav-item text-center ml-2 noti" @click.prevent="redireccionarMenu('mensajes','/user/bandeja')">
                        <a  href="#" class="nav-link position-relative">
                        <img v-if="validarMenuActivo('mensajes')" :src="$baseUrlVue('img/iconos/bandeja.png')" alt="avatar" width="35" height="35"/>
                        <img v-else :src="$baseUrlVue('img/iconos/bandeja-alt-1.png')" alt="avatar" width="35" height="35"/>
                            <div class="contador-flotante" v-if="getUserName('mensajes') > 0">
                               {{getUserName('mensajes')}}
                            </div>
                        </a>
                    </li>
                   <!--  <li class="nav-item text-center"  @click.prevent="redireccionar('/pages/bandeja')">
                        <a href="#" class="nav-link">
                        <i class="fas fa-envelope fa-lg"></i><br>
                            Bandeja
                        </a>
                    </li> -->
                </ul>

               <ul v-if="!this.$store.getters.getInvitado" class="navbar-nav ml-2 noti-1" style="z-index:99">
                    <li class="nav-item text-center" @click.prevent="redireccionarMenu('notificaciones','/user/notificaciones')">
                        <a  href="#" class="nav-link position-relative">
                        <img v-if="validarMenuActivo('notificaciones')" :src="$baseUrlVue('img/iconos/notificacion.png')" alt="avatar" width="35" height="35"/>
                        <img v-else :src="$baseUrlVue('img/iconos/notificacion-alt.png')" alt="avatar" width="35" height="35"/>
                            <div class="contador-flotante" v-if="getUserName('notificaciones') > 0">
                               {{getUserName('notificaciones')}}
                            </div>
                            <br>
                        </a>
                    </li>
                    <li class="nav-item text-center" @click.prevent="redireccionarMenu('mensajes','/user/bandeja')">
                        <a  href="#" class="nav-link position-relative">
                        <img v-if="validarMenuActivo('mensajes')" :src="$baseUrlVue('img/iconos/bandeja.png')" alt="avatar" width="35" height="35"/>
                        <img v-else :src="$baseUrlVue('img/iconos/bandeja-alt-1.png')" alt="avatar" width="35" height="35"/>
                            <div class="contador-flotante" v-if="getUserName('mensajes') > 0">
                               {{getUserName('mensajes')}}
                            </div>
                        </a>
                    </li> 
                </ul>

                <div class="row perf">
                    <span class="row col-6 col-sm-6 col-md-6 col-lg-9 text-right">
                        <strong class="col-12"  v-text="getUserName('nombres')"></strong>
                        <small class="col-12" v-text="getOcupacion()"></small>

                    </span>
                    <div class="btn-group ml-0 col-6 col-sm-6 col-md-6 col-lg-3 text-center">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="avatar avatar-online">
                            <img :src="$baseUrlVue(getAvatar())" class="rounded-circle" alt="avatar" width="40" height="40"/>
                        </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" v-if="!this.$store.getters.getInvitado">
                            <button class="dropdown-item" type="button" @click.prevent="redireccionarMiPerfil">Perfil</button>
                            <button class="dropdown-item" data-toggle="modal" data-target="#editar-mis-preferencias" type="button">Preferencias</button>
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item" type="button" @click="cerrarSesion">Cerrar sesión</button>
                        </div>
                        <div class="dropdown-menu dropdown-menu-right" v-else>
                            <button class="dropdown-item" type="button" @click.prevent="cerrarSesion">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

    
        <!-- Modal Preferencias-->
        <div class="modal fade" id="editar-mis-preferencias" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar preferencias</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <preferencias :esEdicion="false"></preferencias>
                </div>
                </div>
            </div>
        </div>

        <!-- Modal Redacta tu pregunta-->
        <div class="modal fade" id="redactar-pregunta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Redactar <span v-text="redactar.descripcion" ></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form" data-vv-scope="form_pregunta">
                            <div class="form-group">
                                <p class="m-0">
                                    <strong>Temas</strong>
                                </p>
                                <multiselect
                                    v-model="etiquetasSel"
                                    :options="comboEtiquetas"
                                    :max="5"
                                    data-vv-as="Etiquetas"
                                    name="etiqueta"
                                    placeholder="-- Seleccione --"
                                    label="nombre"
                                    track-by="id"
                                    :close-on-select="true"
                                    :searchable="true"
                                    :show-labels="false"
                                    :multiple="true"
                                    @input="buscarSugerencias"
                                >
                                </multiselect>
                                <span class="text-danger">{{errors.first("form_pregunta.etiqueta")}}</span>
                            </div>
                            <!-- <div class="form-group mt-2">
                                <button type="button" class="btn btn-info col" @click.prevent="buscarSugerencias"> Buscar </button>
                            </div> -->
                    </form>
                    <hr>
                    <h4>Sugerencias</h4>
                        <ul>
                            <li v-if="listaSugerencias == 0"> ¡No se encontraron resultados! </li>
                            <li class="mouse-pointer-click" v-for="item in listaSugerencias" :key="item.id" >
                                <a href="#" @click.prevent="redireccionarDetallePregunta(redactar.tipo, item.pregunta_id)"><span v-text="item.titulo" ></span></a>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="crearPregunta">Nuevo</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>

import helper from "../../services/helper";
import Crypt from "../../services/Crypt";

import Preferencias from "../templates/Preferencias";
import Multiselect from "vue-multiselect";


export default {
    name: 'Header',
    components:{
        Preferencias, Multiselect
    },
    data(){
        return {
            redactar:{
                tipo: 1,
                descripcion: 'pregunta',
                texto: '',
                etiquetas:'',
                mostrarSugerencias: false,
            },
            filtroBusqueda:{
                order: 'asc',
                orderBy: 'id',
                tipo: 1, //1es pregunta
                texto: '',
                etiquetas: null,
            },
            menuActivo:{
                inicio: false,
                explorar: false,
                aportar: false,
            },
            sugerencias:[],
            modal:{
                activo: false,
            },
            listaSugerencias:[],
            comboEtiquetas: [],
            etiquetasSel: [],


        }
    },
    mounted(){
        this.getEtiquetas();
    },
    methods:{
        getUserName(name){
            return this.$store.getters.getAuthUser(name)
        },
        getOcupacion(){
            console.log();
            if(!this.$store.getters.getInvitado == true)
                return this.$store.getters.getAuthUser('ocupacion').nombre;
        },
        getEtiquetas(){
            axios.get("api/etiqueta/llenar_combo")
            .then((response) => {
                this.comboEtiquetas = response.data;
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
        keyUpBuscarSugerencias(){
            this.redactar.mostrarSugerencias = true;
            this.buscarSugerencias();
        },
        buscarSugerencias(){
            if(this.redactar.texto.length > 2){
                if(this.etiquetasSel.length > 0) {
                    const etiquetas = this.etiquetasSel.reduce((list, item) => {list.push(item.id); return list;}, []);
                    this.filtroBusqueda.etiquetas = etiquetas;
                }
                else{
                    this.filtroBusqueda.etiquetas = null;
                }
                
                this.filtroBusqueda.tipo = this.redactar.tipo;
                this.filtroBusqueda.texto = this.redactar.texto;
                axios.get("api/pregunta/listar_sugerencias"+ helper.getFilterURL(this.filtroBusqueda))
                .then((response) => {
                    this.listaSugerencias = response.data;
                    this.sugerencias = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$toastr.e(error.response.data.message);
                });
            }
            else
                this.redactar.mostrarSugerencias = false;
        },
        ocultarSugerencias(){
            setTimeout(() => {
                this.redactar.mostrarSugerencias = false;
            }, 1000);
        },
        redireccionar(url){
            this.$router.push(url);
        },
        redireccionarDetallePregunta(tipo, pregunta){
            this.redactar.texto = '';
            $('#redactar-pregunta').modal('hide');
            helper.redireccionarDetallePregunta(this,(tipo==1 ? 'pregunta':'publicacion'), Crypt.encrypt(pregunta));
        },
        redireccionarMenu(tipo, url){
            this.$store.dispatch('setActiveMenu',tipo); 
            this.redireccionar(url);
        },
        redireccionarMiPerfil(){
            helper.redireccionarUsuario(this, this.$store.getters.getAuthUser('identificador'));
        },
        validarMenuActivo(menu){
          
            return this.$store.getters.getActiveMenu(menu);
        },
        cambiarRedactar(tipo){
            if(tipo==1){
                this.redactar.tipo = 1;
                this.redactar.descripcion = 'pregunta';
            }
            else{
                this.redactar.tipo = 2;
                this.redactar.descripcion = 'publicacion';
            }
        },
        redactarPregunta(){
            this.etiquetasSel = [];
            this.listaSugerencias = [];
            if(this.redactar.tipo == 2){
                this.crearPregunta();
                return true;
            }
            $('#redactar-pregunta').modal('show');
            this.buscarSugerencias();
            this.ocultarSugerencias();
        },
        crearPregunta(){
            if(helper.mostrarModalInvitado(this)){
                return true
            }
            const obj = {
                texto: this.redactar.texto,
                tipo: this.redactar.tipo,
                descripcion: this.redactar.descripcion,
                etiquetas: this.etiquetasSel,
            };
            this.$store.dispatch('setTemporal', obj); 

            helper.redireccionarRedactarPregunta(this,'nueva',this.redactar.descripcion, helper.generaCaracteresAleatorios(10));
            $('#redactar-pregunta').modal('hide');
            this.redactar.texto = '';
        },
        cerrarSesion(){
            axios.post('api/auth/logout').then(response => {
                localStorage.removeItem('token_linkedin');
                localStorage.removeItem('token_laravel');
                axios.defaults.headers.common['Authorization'] = null;
                this.$store.dispatch('resetAuthUserDetail');
                this.$toastr.s("Cierre de sesión correcto");
                this.$router.push('/auth/login');
            }).catch(error => {
                console.log(error);
            });
        },
    }

}
</script>

<style>

</style>