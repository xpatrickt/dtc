<template>
    <div>
        <div class="card-body" >
            <div class="card-title">
                <a href="#" class="text-decoration-none h6 text-dark" @click.prevent="redireccionarUsuario(pregunta.usuario.id)">
                    <strong v-text="pregunta.usuario.apellidos +' '+pregunta.usuario.nombres" > </strong>
                    <span class="text-muted" v-text="pregunta.usuario.ocupacion.nombre"> </span>
                </a>
                <div class="float-right" v-if="esCreador(pregunta)" >
                    <a href="#" @click.prevent="denunciar(pregunta)" title="Moderar">
                        <span v-html="obtenerIconoLike(pregunta.calificaciones_usuario, 3, 'moderar', 20)"> </span>
                    </a>
                </div>
            </div>
            <h6 class="card-subtitle mb-2 row">
                <div class="col-12 col-sm-10 " >
                    <h3 class="mouse-pointer-click" v-text="pregunta.titulo" @click.prevent="redireccionarDetallePregunta(pregunta.tipo, pregunta.id)"></h3>
                </div>
                <div class="col-12 col-sm-2 text-muted text-right">
                    <small v-text="getTiempoTranscurrido(pregunta.created_at)"></small>
                </div>
            </h6>
            
            <p :class="'card-text ' + (recortar? 'mouse-pointer-click recortar':'')" v-html="pregunta.cuerpo" @click="redireccionarDetallePregunta(pregunta.tipo, pregunta.id)">
            </p>
            <div class="row mb-1">
                <div class="col-md-6 text-left">
                    <img v-if="pregunta.tipo==1" :src="$baseUrlVue('img/iconos/tag-pregunta.png')" alt="avatar" height="30"/>
                    <img v-else :src="$baseUrlVue('img/iconos/tag-publicacion.png')" alt="avatar" height="30"/>
                </div>
                <div class="col-md-6 text-right respuesta">
                    <span v-text="pregunta.c_respuestas"></span> 
                    <span v-text="pregunta.tipo == 1? 'respuestas' : 'comentarios'">
                    </span> 
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 text-info text-decoration-none">
                    <a v-for="etiqueta in pregunta.etiquetas" :key="etiqueta.id" href="#" @click.prevent="redireccionarEtiqueta(pregunta.tipo, etiqueta)" class="mr-2 ">
                        <span v-text="'#'+etiqueta.nombre"></span>
                    </a>
                </div>
                <div class="col-12 col-sm-4 text-right">
                    <template v-if="recortar">
                        <button class="btn btn-outline-secondary mr-3" style="padding-top:2px; padding-bottom:2px;" @click.prevent="redireccionarDetallePregunta(pregunta.tipo, pregunta.id)"> 
                            <span v-text="pregunta.tipo == 1? 'Responder' : 'Comentar'">
                            </span> 
                        </button>
                    </template>
                    <a href="#" @click.prevent="meGusta(pregunta)" title="Pregunta útil, clara y valiosa.">
                        <span  v-html="obtenerIconoLike(pregunta.calificaciones_usuario, 1, 'like')"> </span>
                        <span v-text="pregunta.me_gusta"></span>
                    </a>
                    <a href="#" class="ml-2" @click.prevent="noMeGusta(pregunta)" title="Pregunta inútil, confusa o sin valor.">
                        <span v-html="obtenerIconoLike(pregunta.calificaciones_usuario, 2, 'dislike')"> </span>
                        <span v-text="pregunta.no_gusta"></span>
                    </a>
                    <div class="btn-group dropup mouse-pointer-click ml-1" v-if="!recortar">
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Compartir">
                           <img :src="$baseUrlVue('img/iconos/compartir.png')" alt="avatar" width="30" height="30"/>
                        </a>
                        <div class="dropdown-menu p-1" style="right:0px left:-120px">
                            <ul class="form group list-unstyled m-0">
                                <li>
                                    <a href="#" @click="compartirRed('facebook', pregunta)"><i class="fab fa-facebook"></i> Facebook</a>
                                </li>
                                <li>
                                    <a href="#" @click="compartirRed('whatsapp', pregunta)"><i class="fab fa-whatsapp"></i> Whatsapp</a>
                                </li>
                                <li>
                                    <a href="#" @click="compartirRed('twitter', pregunta)"><i class="fab fa-twitter"></i> Twitter</a>
                                </li>
                                <li>
                                    <a href="#" @click="compartirRed('portapapeles', pregunta)"><i class="fas fa-clone"></i> Copiar URL</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

import Helper from "../../services/Helper";
import Crypt from "../../services/Crypt";
import { helpers } from 'chart.js';

export default {
    name:'Pregunta',
    props:{
        pregunta:{
            type: Object,
            required: true,
        },
        recortar:{
            type: Boolean,
            default: true,
        },
        redireccionarPregunta:{
            type: Boolean,
            default: true,
        },
    },
    data(){
        return {
            identificadorUserActual: null,
        };
    },
    mounted(){
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()

        })
       //this.init();
       this.identificadorUserActual = Crypt.decrypt(this.$store.getters.getAuthUser('identificador')) ;
    },
    methods:{
        mostrarCompartir(){
         return false;
        },
        getTiempoTranscurrido(time){
            return Helper.getTiempoTranscurrido(time);
        },
        redireccionarEtiqueta(tipo, etiqueta){
            console.log(tipo);
            console.log(etiqueta);
            Helper.redireccionarEtiqueta(this,(tipo==1 ? 'pregunta':'publicacion'), etiqueta.url);
        },
        redireccionarUsuario(usuario){
            Helper.redireccionarUsuario(this, Crypt.encrypt(usuario));
        },
        redireccionarDetallePregunta(tipo, pregunta){
            if(this.redireccionarPregunta)
                Helper.redireccionarDetallePregunta(this,(tipo==1 ? 'pregunta':'publicacion'), Crypt.encrypt(pregunta));
        },
        compartirRed(red, pregunta){
            let url = window.location;
            let titulo = encodeURI(pregunta.titulo);
            if(red == 'facebook'){
                //https://desarrolloweb.com/articulos/boton-compartir-facebook.html
                window.open('http://www.facebook.com/sharer.php?u='+url+'&t='+titulo,'ventanacompartir', '_blank');
            }
            else if(red == 'whatsapp'){
                titulo += ' en ' + url;
                window.open('https://api.whatsapp.com/send?text='+titulo, '_blank');
            }
            else if(red == 'twitter'){
                //https://parzibyte.me/blog/2018/08/02/enlace-para-compartir-en-twitter-con-html/
                url = encodeURIComponent(url);
                window.open('https://twitter.com/intent/tweet?text='+titulo+'&url='+url+'&via=expertip&hashtags=expertip', '_blank');
            }
            else{
                var textarea = document.createElement('textarea');
                textarea.textContent = url;
                document.body.appendChild(textarea);

                var selection = document.getSelection();
                var range = document.createRange();
                //  range.selectNodeContents(textarea);
                range.selectNode(textarea);
                selection.removeAllRanges();
                selection.addRange(range);

                document.execCommand('copy');
                
                selection.removeAllRanges();

                document.body.removeChild(textarea);
                this.$toastr.s('Se copió al portapapeles');
            }
        },
        meGusta(pregunta){
            if(Helper.mostrarModalInvitado(this)){
                return true
            }
            axios.post("api/calificacion/me_gusta",{
                pregunta_id: pregunta.id,
                preguntaCreadorId: pregunta.user_id,
            })
            .then((response) => {
                if(response.data.accion == 'sumar'){
                    pregunta.me_gusta +=1;
                    pregunta['calificaciones_usuario'].push({tipo: 1});
                }
                else if(response.data.accion == 'restar'){
                    pregunta.me_gusta -=1;
                    let removeIndex = pregunta['calificaciones_usuario'].map(item => item.tipo).indexOf(1); // me gusta es 1
                    pregunta['calificaciones_usuario'].splice(removeIndex,1);
                }
                else if(response.data.accion == 'cambiar'){
                    pregunta.me_gusta +=1;
                    pregunta.no_gusta -=1;
                    pregunta['calificaciones_usuario'].push({tipo: 1});
                    let removeIndex = pregunta['calificaciones_usuario'].map(item => item.tipo).indexOf(2); // me gusta es 1
                    pregunta['calificaciones_usuario'].splice(removeIndex,1);

                }
            })
            .catch((error) => {
                this.$toastr.e(error.response.data.message);
            });
        },
        noMeGusta(pregunta){
            if(Helper.mostrarModalInvitado(this)){
                return true
            }
            axios.post("api/calificacion/no_gusta",{
                pregunta_id: pregunta.id,
                preguntaCreadorId: pregunta.user_id,
            })
            .then((response) => {
                if(response.data.accion == 'sumar'){
                    pregunta.no_gusta +=1;
                    pregunta['calificaciones_usuario'].push({tipo: 2});
                }
                else if(response.data.accion == 'restar'){
                    pregunta.no_gusta -=1;
                    let removeIndex = pregunta['calificaciones_usuario'].map(item => item.tipo).indexOf(2); // no me gusta es 2
                    pregunta['calificaciones_usuario'].splice(removeIndex,1);
                }
                else if(response.data.accion == 'cambiar'){
                    pregunta.no_gusta +=1;
                    pregunta.me_gusta -=1;
                    pregunta['calificaciones_usuario'].push({tipo: 2});
                    let removeIndex = pregunta['calificaciones_usuario'].map(item => item.tipo).indexOf(1); // no me gusta es 2
                    pregunta['calificaciones_usuario'].splice(removeIndex,1);

                }
            })
            .catch((error) => {
                this.$toastr.e(error.response.data.message);
            });
        },
        denunciar(pregunta){
            if(Helper.mostrarModalInvitado(this)){
                return true
            }
            axios.post("api/calificacion/denunciado",{
                pregunta_id: pregunta.id,
                preguntaCreadorId: pregunta.user_id,
            })
            .then((response) => {
                if(response.data.accion == 'sumar'){
                    pregunta.denunciado += 1;
                    pregunta['calificaciones_usuario'].push({tipo: 3});
                }
                else{
                    pregunta.denunciado -= 1;
                    let removeIndex = pregunta['calificaciones_usuario'].map(item => item.tipo).indexOf(3); // no me gusta es 2
                    pregunta['calificaciones_usuario'].splice(removeIndex,1);

                }
            })
            .catch((error) => {
                this.$toastr.e(error.response.data.message);
            });
        },
        opcionesCambio(pregunta, tipo, variable ){
            
        },
        obtenerIconoLike(calificacion, tipo, desc, tam = 20){
            for(let i = 0; i < calificacion.length; i++ ){
                if(calificacion[i].tipo == tipo){
                    return '<img src="'+this.$baseUrlVue('img/iconos/'+desc+'.png') + '" alt="avatar" width="'+tam+'" height="'+tam+'"/>';
                }
            }
            return '<img src="'+this.$baseUrlVue('img/iconos/'+desc+'-alt.png') +'" alt="avatar" width="'+tam+'" height="'+tam+'"/>';
        },
        esCreador(pregunta){
            if(pregunta.user_id == this.identificadorUserActual){
                return false;
            }
            return true;
        },
    }

}
</script>


