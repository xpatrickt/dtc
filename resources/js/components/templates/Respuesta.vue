<template>
    <div class="row">
        <div class="col-lg-1 col-md-2 col-sm-2">
            <span class="avatar avatar-online">
                <img :src="$baseUrlVue(getAvatar(respuesta.usuario.avatar))" class="rounded-circle" alt="avatar" width="60" height="60"/>
            </span>
        </div>
        <div class="col-lg-11 col-md-10 col-sm-10 card">
            <div class="card-body">
                <div class="card-title">
                    <a href="#" class="text-decoration-none h6 text-dark" @click.prevent="redireccionarUsuario(respuesta.usuario.id)"> 
                        <strong v-text="respuesta.usuario.apellidos +' '+respuesta.usuario.nombres" > </strong> 
                        <span class="text-muted" v-text="respuesta.usuario.ocupacion.nombre"> </span>
                    </a>
                    <div class="float-right" v-if="respuesta.denuncia">
                        <i class="fas fa-exclamation-circle text-danger"></i>
                    </div>
                    <div class="float-right" >
                        <a href="#"  @click.prevent="registrarMejorRespuesta" > Mejor respuesta</a>
                    </div>
                </div>
                <h6 class="card-subtitle mb-2 row">
                    <div class="col-12 col-sm-10">
                        <h3 v-text="respuesta.titulo"></h3>
                    </div>
                    <div class="col-12 col-sm-2 text-muted text-right">
                        <small v-text="getTiempoTranscurrido(respuesta.created_at)"></small>
                    </div>
                </h6>
                <p :class="'card-text ' + (recortar? 'recortar':'')" v-html="respuesta.cuerpo">
                </p>
                <div class="row">
                    <div class="col-12 col-sm-10 text-info text-decoration-none">
                        <a v-for="etiqueta in respuesta.etiquetas" :key="etiqueta.id" href="#" @click.prevent="redireccionarEtiqueta(etiqueta)" class="mr-2 ">
                            <span v-text="'#'+etiqueta.nombre"></span>
                        </a>
                    </div>
                    <div class="col-12 col-sm-2 text-right">
                        <a href="#" @click.prevent="meGusta(respuesta)" title="Respuesta valiosa">
                            <span  v-html="obtenerIconoLike(respuesta.calificaciones_usuario, 1, 'like')"> </span>
                            <span v-text="respuesta.me_gusta"></span>
                        </a>
                        <a href="#" class="ml-2" @click.prevent="noMeGusta(respuesta)" title="Respuesta poco valiosa">
                            <span  v-html="obtenerIconoLike(respuesta.calificaciones_usuario, 2, 'dislike')"> </span>
                            <span v-text="respuesta.no_gusta"></span>
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>

import Helper from "../../services/Helper";
import Crypt from "../../services/Crypt";

export default {
    name:'Pregunta',
    props:{
        respuesta:{
            type: Object,
            required: true,
        },
        recortar:{
            type: Boolean,
            default: true,
        },
        opcionMejorRespuesta:{
            type: Boolean,
            default: false,
        }
    },
    methods:{
        getTiempoTranscurrido(time){
            return Helper.getTiempoTranscurrido(time);
        },
        redireccionarEtiqueta(tipo, etiqueta){
            Helper.redireccionarEtiqueta(this,(tipo==1 ? 'respuesta':'publicacion'), etiqueta.url);
        },
        redireccionarUsuario(usuario){
            Helper.redireccionarUsuario(this, Crypt.encrypt(usuario));
        },
        getAvatar(avatar){
            if(avatar)
                return 'user/users/' + avatar;
            return 'img/auth/user-alt.png';
        },
        meGusta(respuesta){
            if(Helper.mostrarModalInvitado(this)){
                return true
            }
            axios.post("api/calificacion/me_gusta",{
                pregunta_id: respuesta.id,
                preguntaCreadorId: respuesta.user_id,
            })
            .then((response) => {
                if(response.data.accion == 'sumar')
                    respuesta.me_gusta +=1;
                else if(response.data.accion == 'restar')
                    respuesta.me_gusta -=1;
                else if(response.data.accion == 'cambiar'){
                    respuesta.me_gusta +=1;
                    respuesta.no_gusta -=1;
                }
            })
            .catch((error) => {
                this.$toastr.e(error.response.data.message);
            });
        },
        noMeGusta(respuesta){
            if(Helper.mostrarModalInvitado(this)){
                return true
            }
            axios.post("api/calificacion/no_gusta",{
                pregunta_id: respuesta.id,
                preguntaCreadorId: respuesta.user_id,
            })
            .then((response) => {
                if(response.data.accion == 'sumar')
                    respuesta.no_gusta +=1;
                else if(response.data.accion == 'restar')
                    respuesta.no_gusta -=1;
                else if(response.data.accion == 'cambiar'){
                    respuesta.no_gusta +=1;
                    respuesta.me_gusta -=1;
                }
            })
            .catch((error) => {
                this.$toastr.e(error.response.data.message);
            });
        },
        registrarMejorRespuesta(respuesta){
           
        },
        obtenerIconoLike(calificacion, tipo, desc, tam = 20){
            for(let i = 0; i < calificacion.length; i++ ){
                if(calificacion[i].tipo == tipo){
                    return '<img src="'+this.$baseUrlVue('img/iconos/'+desc+'.png') +'" alt="avatar" width="'+tam+'" height="'+tam+'"/>';
                }
            }
            return '<img src="'+this.$baseUrlVue('img/iconos/'+desc+'-alt.png') +'" alt="avatar" width="'+tam+'" height="'+tam+'"/>';
        },
    }
}
</script>