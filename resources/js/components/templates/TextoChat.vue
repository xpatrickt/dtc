<template>
    <div>
        <div class="row" v-if="(!admin && usuario.id == chat.usuario_1) || (admin && usuario.id == chat.usuario_2)"> 
            <div class="col-11 card rounded-5">
                <div class="card-body">
                    
                    <p :class="'card-text ' + (recortar? 'recortar':'')" v-html="chat.mensaje">
                    </p>
                    <div class="col-12 col-sm-12 text-muted">
                        <small v-text="formatearFecha(chat.created_at)"></small>
                    </div>
                </div>
            </div>
            <div class="col-1">
                <span class="avatar avatar-online">
                    <img :src="$baseUrlVue(getAvatar(usuario.avatar))" class="rounded-circle" alt="avatar" width="60" height="60"/>
                </span>
            </div>
        </div>

        <div class="row" v-else>
            <div class="col-1">
                <span class="avatar avatar-online rounded-circle">
                    <img v-if="admin" :src="$baseUrlVue(getAvatar(usuario.avatar))" alt="avatar" width="60" height="60"/>
                    <img v-else :src="$baseUrlVue('img/auth/user-alt.png')" alt="avatar" width="60" height="60"/>
                </span>
            </div>
            <div class="col-11 card">
                <div class="card-body">
                    <p :class="'card-text ' + (recortar? 'recortar':'')" v-html="chat.mensaje">
                    </p>
                    <div class="col-12 col-sm-12 text-muted">
                        <p v-if="!admin">
                            ------------------------------------------------<br>
                            Gestor de contenidos<br>
                            ------------------------------------------------<br>
                        </p>
                        <small v-text="formatearFecha(chat.created_at)"></small>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
</template>

<script>

import helper from "../../services/helper";
import Crypt from "../../services/Crypt";


export default {
    name:'Pregunta',
    props:{
        chat:{
            type: Object,
            required: true,
        },
        recortar:{
            type: Boolean,
            default: true,
        },
        usuarioId:{
            type: Number,
            required: false,
        },
        admin:{
            type: Boolean,
            default: false,
        },

    },
    data() {
        return {
            usuario: {
                id: null,
                avatar: null,
            },
        }
    },
    created(){
        this.init();
    },
    methods:{
        init(){
            if(this.admin){
                this.usuario.id = this.usuarioId;
            }
            else{
                this.usuario.id = Crypt.decrypt(this.$store.getters.getAuthUser('identificador'));
                this.usuario.avatar = this.$store.getters.getAuthUser('avatar');
            }
        },
        getTiempoTranscurrido(time){
            return helper.getTiempoTranscurrido(time);
        },
        redireccionarEtiqueta(tipo, etiqueta){
            helper.redireccionarEtiqueta(this,(tipo==1 ? 'chat':'publicacion'), etiqueta.url);
        },
        redireccionarUsuario(usuario){
            helper.redireccionarUsuario(this, Crypt.encrypt(usuario));
        },
        getAvatar(avatar){
            if(avatar)
                return 'user/users/' + avatar;
            return 'img/auth/user-alt.png';
        },
        formatearFecha(fecha){
            return helper.formatearFecha(fecha);
        },
        
    }

}
</script>