<template>
    <div >
        <div class="card" >
            <div class="card-body">
                <form class="form" data-vv-scope="form_respuesta" v-if="!this.$store.getters.getInvitado">
                    <h4 v-if="pregunta.tipo == 1">Tu respuesta</h4>
                    <h4 v-else >Tu comentario</h4>
                    <div class="form-group">
                        <div id="muestra-div-usuario" class="div-flotante">
                            <div>
                                <span>Usuarios</span>
                                <hr style="margin-top:0.25rem">
                                <ul>
                                    <li v-if="comboUsuarios.length == 0 "> ¡No se encontraron resultados!</li>
                                    <li class="mouse-pointer-click" v-for="row in comboUsuarios" :key="row.id" @click="agregarUsuario(row.id, row.nombres, row.apellidos )"> <span v-text="row.apellidos + ' ' + row.nombres + ' - ' + row.puesto_actual"></span></li>
                                </ul>
                            </div> 
                        </div>
                        <div id="contenedor-trumbowyg">
                            <trumbowyg
                            id="cuerpoPreuba"
                            v-model="cuerpoRespuesta"
                            :config="config"
                            class="form-control"
                            data-vv-as="Cuerpo"
                            name='cuerpo'
                            v-validate="'required'"
                            ></trumbowyg>
                        </div>
                        <span class="text-danger">{{errors.first("form_respuesta.cuerpo")}}</span>
                    </div>
                </form>
                <textarea class="form-control" rows="10" disabled v-else></textarea>
                <div class="form-group mt-2">
                    <button type="button" class="btn btn-primary col" @click.prevent="registrarRespuesta"> Guardar </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Helper from "../../services/Helper";
import Crypt from "../../services/Crypt";
import Trumbowyg from 'vue-trumbowyg';


    export default {
        name:'RedactarRespuestaForm',
        components:{
            Trumbowyg,
        },
        props:{
            pregunta:{
                type: Object,
                required: true,
            },
            parametros:{
                type: Object,
                required: true,
            },
            parametrosRespuesta:{
                type: Object,
                required: true,
            }
        },
        data(){
            return{
                cargaCompleta: false,
                config: {
                    svgPath: "https://unpkg.com/trumbowyg@2.9.4/dist/ui/icons.svg",
                    autogrow: true,
                    removeformatPasted: true,
                    // Adding color plugin button
                    btnsAdd: ['foreColor', 'backColor'],
                    // Limit toolbar buttons
                    btns: [
                        ['formatting'], 
                        ['strong', 'em', 'del', 'u'],
                        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                        ['foreColor'], ['backColor'],
                        ['link'],
                        ['insertImage'],
                    ]
                },
                cuerpoRespuesta:'',
                respuestaID: null,
                respuesta:{
                    pregunta_id: null,
                    cuerpo: '',
                    tipo: 3,
                },
                usuario: {
                    activo: false,
                    nombre: '',
                },
                usuariosSel:[],
                comboUsuarios:[],
                preguntaInicial:{
                    cuerpo: null,
                    tipo: 1,
                    usuarios: [],
                },

            }
        },
        watch:{
            cuerpoRespuesta: function(newValue, oldValue) {
               this.iniciarBusquedaUsuario(newValue);
            }
        },
        created(){
            this.init();
        },
        methods:{
            init(){
                if(this.parametrosRespuesta.accion != 'nueva'){
                    let identificador = this.parametrosRespuesta.id;
                    let tipo = (this.parametros.opcion == 'pregunta' ? 3 : 4);

                    axios.get("api/pregunta/ver_simple/"+Crypt.decrypt(identificador))
                    .then((response) => {
                        this.respuestaID = response.data.id;
                        this.respuesta.cuerpo = response.data.cuerpo;
                        this.cuerpoRespuesta =  response.data.cuerpo;
                        this.usuariosSel = response.data.usuarios;
                        this.preguntaInicial.usuarios = this.usuariosSel.map(item =>{return item.id});
                        this.cargaCompleta = true;
                    })
                    .catch((error) => {
                        console.log(error);
                        this.$toastr.e(error.response.data.message);
                    });
                }
            },
            iniciarBusquedaUsuario(str){
                let activo = false;
                let index = 0;
                let palabra = '';
                for (let i = 0; i < str.length; i++) {
                    if(str.charAt(i) == '@'){
                        activo = true;
                        index = i;
                    }
                }
                if(activo == true){
                   for (let i = index; i < str.length; i++) {
                        palabra+= str.charAt(i);
                        if(str.charAt(i) == '<'){
                            break;
                        }

                    } 
                }
                else{
                    return true;
                }
                if(palabra.length<5){
                    document.getElementById("muestra-div-usuario").style.display = "none";
                    return true;
                }
                $('#cuerpoPreuba').trumbowyg('saveRange');
                palabra = palabra.slice(1, palabra.length - 1);
                this.usuario.nombre = palabra;
                this.mostrarModalUsuarios();
            },
            mostrarModalUsuarios() {
                this.buscarUsuarios(this.usuario.nombre);
                var range = $('#cuerpoPreuba').trumbowyg('getRange');

                document.getElementById("muestra-div-usuario").style.display = "block";
                //document.getElementById("muestra-div-usuario").style.color = "red";
                //document.getElementById("muestra-div-usuario").style.position = "relative";
                document.getElementById("muestra-div-usuario").style.top = (110+range.startContainer.parentElement.offsetTop)+"px";
                if(range.startContainer.parentElement.nodeName == 'P'){
                    document.getElementById("muestra-div-usuario").style.left = ((range.startContainer.parentElement.outerText.length*7))+"px";
                }
                else{
                    document.getElementById("muestra-div-usuario").style.left = (20+range.startContainer.parentElement.offsetLeft + (range.startContainer.parentElement.offsetWidth/2))+"px";
                }
            },
            buscarUsuarios(descripcion){
                this.isLoading = true;
                if(descripcion.length<3){
                    this.comboUsuarios=[];
                    this.isLoading = false;
                    return;
                }
                let busqueda = {filtro: descripcion.trim()}
                axios.get('api/usuario/llenar_combo' + Helper.getFilterURL(busqueda)).then(response => {
                    this.comboUsuarios = response.data;
                    this.isLoading = false;
                }).catch(error => {
                    toastr['error'](error.response.data.message);
                });
            },
            agregarUsuario(id, nombre, apellidos){
                let url = '/pages/perfil-publico/' + encodeURI(Crypt.encrypt(id)) ;
                document.getElementById("muestra-div-usuario").style.display = "none";

                var range = $('#cuerpoPreuba').trumbowyg('getRange');
                let tag = '<a href="'+url+'" target="_blank" data-id="['+id+']">'+nombre+ ' '+ apellidos +'</a>';
                document.getElementById("muestra-div-usuario").style.display = "none";
                let html = $('#cuerpoPreuba').trumbowyg('html');
                html = html.replace('@'+this.usuario.nombre, tag );
                $('#cuerpoPreuba').trumbowyg('html', html); 
            },
            registrarRespuesta(){
                if(Helper.mostrarModalInvitado(this)){
                    return true
                }
                this.$validator.validateAll('form_respuesta').then(result => {
                    if (result) { 
                        let cuerpo = this.cuerpoRespuesta.trim();
                        if(cuerpo.length < 12) {
                            this.$toastr.e("Debe de registrar una respuesta válida");
                            return false;
                        }
                        let usuariosSel = [];
                        cuerpo = cuerpo.match(/\[([\w\s]*)\]/g);
                        if (cuerpo){
                            cuerpo.forEach((item) => {
                                usuariosSel.push(Number(item.replace(/[\[\]]/g, '')));
                            });
                        }
                        if(this.respuestaID == null){
                            this.respuesta.tipo = (this.parametros.opcion == 'pregunta' ? 3 : 4);
                            this.respuesta.pregunta_id = this.pregunta.id; 
                            this.respuesta.preguntaCreadorId = this.pregunta.user_id; 
                            this.respuesta.fCreacionPregunta = this.pregunta.created_at;
                            this.respuesta.usuarios = usuariosSel.join(',');
                            this.respuesta.cuerpo = this.cuerpoRespuesta;

                            axios.post("api/pregunta/crear_respuesta", this.respuesta)
                            .then((response) => {
                                this.$toastr.s(response.data.message);
                                let respuesta = response.data.respuesta;
                                respuesta['calificaciones_usuario'] = [];
                                this.pregunta.respuestas.push(respuesta);
                                this.respuesta.cuerpo = '';
                                this.cuerpoRespuesta = '';
                            })
                            .catch((error) => {
                                console.log(error);
                                this.$toastr.e(error.response.data.message);
                            });
                        }
                        else{
                            let usuariosAgregados = usuariosSel.filter(item => !this.preguntaInicial.usuarios.includes(item));
                            let usuariosEliminados = this.preguntaInicial.usuarios.filter(item => !usuariosSel.includes(item));
                           
                            this.respuesta.usuariosAgregados = usuariosAgregados.join(',');
                            this.respuesta.usuariosEliminados = usuariosEliminados.join(',');
                            this.respuesta.cuerpo = this.cuerpoRespuesta;

                            axios.put("api/pregunta/editar_pregunta/"+this.respuestaID, this.respuesta)
                            .then((response) => {
                                this.$toastr.s(response.data.message);
                            })
                            .catch((error) => {
                                this.$toastr.e(error.response.data.message);
                            });
                        }
                        
                    }
                });  
            }
            
            
        },
    }
</script>
