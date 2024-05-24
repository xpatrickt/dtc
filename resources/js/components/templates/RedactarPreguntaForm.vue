<template>
    <div>
        <div class="card" >
            <div class="card-body">
                <h2 v-if="es_pregunta">
                    Haz una pregunta
                </h2>
                <h2 v-else>
                    Crea un post
                </h2>
                <form class="form" data-vv-scope="form_pregunta">
                    <div class="form-group">
                        <p class="m-0">
                            <strong>Título</strong>
                        </p>
                        <small>
                            Sea específico.
                        </small>
                        <input 
                        type="text"
                        class="form-control"
                        id="titulo"
                        name="titulo"
                        data-vv-as="Título"
                        v-model="pregunta.titulo"
                        v-validate="'required'">
                        <span class="text-danger">{{errors.first("form_pregunta.titulo")}}</span>
                    </div>
                    <div class="form-group position-relative">
                        <p class="m-0">
                            <strong>Cuerpo</strong>
                        </p>
                        <small>
                            Incluya la información necesaria.
                        </small>
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
                            v-model="cuerpoPregunta"
                            :config="config"
                            class="form-control"
                            data-vv-as="Cuerpo"
                            name='cuerpo'
                            v-validate="'required'"
                            ></trumbowyg>
                        </div>
                        <span class="text-danger">{{errors.first("form_pregunta.cuerpo")}}</span>
                    </div>
                    <div class="form-group">
                        <p class="m-0">
                            <strong>Temas</strong>
                        </p>
                        <small>
                            Las etiquetas que agregue ayudarán a ubicar a los expertos correctos.
                            <a href="#" @click.prevent="modalAgregarEtiqueta" class="float-right"> Agregar Temas</a>
                        </small>
                        <multiselect
                            v-model="etiquetasSel"
                            :options="comboEtiquetas"
                            :max="5"
                            data-vv-as="Etiquetas"
                            name="etiqueta"
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
                        <span class="text-danger">{{errors.first("form_pregunta.etiqueta")}}</span>
                    </div>
                    <div class="form-group mt-2">
                        <button type="button" class="btn btn-primary col" @click.prevent="registrarPregunta"> Guardar </button>
                    </div>
                </form>
            </div>
        </div>

        <template>
            <div class="modal fade" id="modal-agregar-etiquetas" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Agregar etiqueta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form" data-vv-scope="form_registro">
                                <div class="form-group">
                                    <p class="m-0">
                                        <strong>Tipo (Nivel)</strong>
                                    </p>
                                    <select name="nivel" v-model="modal.etiqueta.nivel" class="form-control" v-validate="'required'">
                                        <option value="">--Seleccione--</option>
                                        <option value="2">Tema</option>
                                        <option value="3">Subtema</option>
                                    </select>
                                    <span class="text-danger">{{errors.first("form_registro.nivel")}}</span>
                                </div>  
                                <hr>
                                <div class="form-group" v-if="modal.etiqueta.nivel == 2 || modal.etiqueta.nivel == 3">
                                    <p class="m-0">
                                        <strong>Rubro</strong>
                                    </p>
                                    <select name="rubro" v-model="modal.etiqueta.rubro" @change="obtenerTemas" class="form-control" v-validate="'required'"  >
                                        <option value="">--Seleccione--</option>
                                        <option v-for="row in listaRubros" :key="row.id" :value="row.id" v-text="row.nombre"> </option>
                                    </select>
                                    <span class="text-danger">{{errors.first("form_registro.rubro")}}</span>
                                </div>
                                <div class="form-group" v-if="modal.etiqueta.nivel == 3">
                                    <p class="m-0">
                                        <strong>Tema</strong>
                                    </p>
                                    <select name="tema" v-model="modal.etiqueta.tema" class="form-control" v-validate="'required'">
                                        <option value="">--Seleccione--</option>
                                        <option v-for="row in listaTemas" :key="row.id" :value="row.id" v-text="row.nombre"> </option>
                                    </select>
                                    <span class="text-danger">{{errors.first("form_registro.tema")}}</span>
                                </div>                         
                                <div class="form-group">
                                    <p class="m-0">
                                        <strong>Nombre</strong>
                                    </p>
                                    <input type="text"
                                    v-model="modal.etiqueta.nombre"
                                    class="form-control"
                                    data-vv-as="Nombre"
                                    placeholder="Nombre"
                                    name="nombre"
                                    v-validate="'required'">
                                    <span class="text-danger">{{errors.first("form_registro.nombre")}}</span>
                                </div>
                                <div class="form-group" v-show="false">
                                    <p class="m-0">
                                        <strong>Nombre clave</strong>
                                    </p>
                                    <input type="text" v-if="!modal.deshabilitarUrl"
                                    class="form-control"
                                    :value="obtenerNombreClave()"
                                    disabled
                                    >
                                    <input type="text" v-else
                                    v-model="modal.etiqueta.url"
                                    class="form-control"
                                    data-vv-as="Nombre clave"
                                    placeholder="Nombre clave"
                                    name="url"
                                    v-validate="'required'">
                                    <span class="text-danger">{{errors.first("form_registro.url")}}</span>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="guardar">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>


<script>


    // Import js
import Helper from "../../services/Helper";
import Crypt from "../../services/Crypt";

import Trumbowyg from 'vue-trumbowyg';
import Multiselect from "vue-multiselect";

    export default {
        name: 'RedactarPreguntaForm',
        components: {
            Trumbowyg, Multiselect,
        },
        props:{
            parametros:{
                type: Object,
                required: true,
            },
        },
        data(){
            return {
                temporal: null,
                config: {
                // Get options from
                // https://alex-d.github.io/Trumbowyg/documentation
                    svgPath: "https://unpkg.com/trumbowyg@2.9.4/dist/ui/icons.svg",
                    autogrow: true,
                    //removeformatPasted: true,
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

                    ],
                },
                es_pregunta: true,
                preguntaID: null,
                pregunta:{
                    titulo: null,
                    cuerpo: null,
                    tipo: 1,
                },
                preguntaInicial:{
                    titulo: null,
                    cuerpo: null,
                    tipo: 1,
                    etiquetas: [],
                    usuarios: [],
                },
                unatexto: null,
                user:null,
                etiquetasSel:[],
                usuariosSel:[],
                comboEtiquetas:[],
                comboUsuarios:[],
               
                usuario: {
                    activo: false,
                    nombre: '',
                },
                cuerpoPregunta:'',
                modal:{
                    titulo:  '',
                    etiquetaID: null,
                    deshabilitarUrl: false,
                    etiqueta:{
                        rubro: '',
                        tema: '',
                        nombre: '',
                        url: '',
                        nivel: '',
                    },
                },
                listaRubros:[],
                listaTemas:[],
                listaTemasAll:[],
            }
        },
        watch:{
            cuerpoPregunta: function(newValue, oldValue) {
               this.iniciarBusquedaUsuario(newValue);
            }
        },
        created(){
            this.init();
            this.getEtiquetas();
        },
        methods:{
            init(){
                if(this.parametros.accion == 'nueva'){
                    this.temporal = this.$store.getters.getTemporal;
                    
                    if(!this.temporal){
                        if(this.parametros.opcion == 'pregunta'){
                            this.pregunta.tipo = 1;
                            this.es_pregunta = true;
                            this.temporal = {
                                descripcion: 'pregunta',
                                texto: '',
                                etiquetas: null
                            }
                        }
                        else{
                            this.pregunta.tipo = 2;
                            this.es_pregunta = false;
                                this.temporal = {
                                descripcion: 'pregunta',
                                texto: '',
                                etiquetas: null
                            }
                        }
                        
                    }
                    else if(this.temporal.descripcion=='pregunta'){
                        this.pregunta.tipo = 1;
                        this.es_pregunta = true;

                    }
                    else{
                        this.pregunta.tipo = 2;
                        this.es_pregunta = false;

                    }
                    this.pregunta.titulo = this.temporal.texto;
                    this.etiquetasSel = this.temporal.etiquetas;
                    this.$store.dispatch('resetTemporal');//limpiamos el temporal

                }
                else{
                    axios.get("api/pregunta/ver_simple/" + Crypt.decrypt(this.parametros.id))
                    .then((response) => {
                        this.preguntaID = response.data.id;
                        this.pregunta.titulo = response.data.titulo;
                        this.pregunta.cuerpo =  response.data.cuerpo;
                        this.cuerpoPregunta =  response.data.cuerpo;
                        this.pregunta.tipo=  response.data.tipo;
                        this.etiquetasSel = response.data.etiquetas;
                        this.usuariosSel = response.data.usuarios;
                        this.preguntaInicial.etiquetas = this.etiquetasSel.map(item =>{return item.id});
                        this.preguntaInicial.usuarios = this.usuariosSel.map(item =>{return item.id});

                        this.es_pregunta = response.data.tipo? true : false;
                    })
                    .catch((error) => {
                        console.log(error);
                        this.$toastr.e(error.response.data.message);
                    });
                }
            },
            getEtiquetas(){
                axios.get("api/etiqueta/llenar_combo")
                .then((response) => {
                    this.comboEtiquetas = response.data;
                    this.listaRubros = response.data.filter(item => item.nivel == 1);
                    this.listaTemasAll = response.data.filter(item => item.nivel == 2);
                })
                .catch((error) => {
                    console.log(error);
                    this.$toastr.e(error.response.data.message);
                });
            },
            registrarPregunta(){
               this.$validator.validateAll('form_pregunta').then(result => {
                    if (result) {
                        let cuerpo = this.cuerpoPregunta.trim();
                        this.pregunta.titulo = this.pregunta.titulo.trim();
                        if(cuerpo.length < 12) {
                            this.$toastr.e("Debe de registrar un cuerpo válido");
                            return false;
                        }
                        if(this.pregunta.titulo.length < 5) {
                            this.$toastr.e("Debe de registrar un titulo válido");
                            return false;
                        }

                        let etiquetasSel = this.etiquetasSel.map((elem) => elem.id);
                        let usuariosSel = [];
                        
                        cuerpo = cuerpo.match(/\[([\w\s]*)\]/g);
                        if (cuerpo){
                            cuerpo.forEach((item) => {
                                usuariosSel.push(Number(item.replace(/[\[\]]/g, '')));
                            });
                        }
                       
                        if(this.preguntaID == null){
                            this.pregunta.etiquetas = etiquetasSel.join(',');
                            this.pregunta.usuarios = usuariosSel.join(',');
                            this.pregunta.cuerpo = this.cuerpoPregunta;

                            axios.post("api/pregunta/crear_pregunta", this.pregunta)
                            .then((response) => {
                                this.$toastr.s(response.data.message);
                                this.$router.push('/pages/inicio');
                            })
                            .catch((error) => {
                                console.log(error);
                                this.$toastr.e(error.response.data.message);
                            });
                        }
                        else{
                            let etiquetasAgregadas = etiquetasSel.filter(item => !this.preguntaInicial.etiquetas.includes(item));
                            let etiquetasEliminadas = this.preguntaInicial.etiquetas.filter(item => !etiquetasSel.includes(item));
                            let usuariosAgregados = usuariosSel.filter(item => !this.preguntaInicial.usuarios.includes(item));
                            let usuariosEliminados = this.preguntaInicial.usuarios.filter(item => !usuariosSel.includes(item));
                            
                            this.pregunta.etiquetasAgregadas = etiquetasAgregadas.join(',');
                            this.pregunta.etiquetasEliminadas = etiquetasEliminadas.join(',');
                            this.pregunta.usuariosAgregados = usuariosAgregados.join(',');
                            this.pregunta.usuariosEliminados = usuariosEliminados.join(',');
                            this.pregunta.cuerpo = this.cuerpoPregunta;

                            axios.put("api/pregunta/editar_pregunta/"+this.preguntaID, this.pregunta)
                            .then((response) => {
                                this.$toastr.s(response.data.message);
                            })
                            .catch((error) => {
                                this.$toastr.e(error.response.data.message);
                            });
                        }
                      
                    }
                });
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
            presionar(evt){
                let codigo = evt.which || evt.keyCode;
                console.log(codigo);
                if(codigo == 8){
                    console.log('presiono backspace');

                }
                if(codigo == 64){
                    this.usuario.activo = true;
                }
                if(this.usuario.activo == true){
                    this.usuario.nombre += evt.key;
                }

                console.log(this.usuario.nombre);

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
            modalAgregarEtiqueta(){
                $("#modal-agregar-etiquetas").modal('show');
                this.limpiarFormulario();
            },
            obtenerNombreClave(){
               return Helper.limpiarCaracteres(this.modal.etiqueta.nombre);
            },
            obtenerTemas(){
                this.listaTemas = this.listaTemasAll.filter(item => item.padre_id == this.modal.etiqueta.rubro); 
            },
            limpiarFormulario(){
                this.modal = {
                    titulo:  '',
                    etiquetaID: null,
                    deshabilitarUrl: false,
                    etiqueta:{
                        rubro: '',
                        tema: '',
                        nombre: '',
                        url: '',
                        nivel: '',
                    }
                }
                this.$validator.reset();
            },
            guardar(){
                this.$validator.validateAll('form_registro').then(result => {
                    if (result) {  

                        if(this.modal.etiqueta.nivel == 2) this.modal.etiqueta.padre_id = this.modal.etiqueta.rubro;
                        else if(this.modal.etiqueta.nivel == 3) this.modal.etiqueta.padre_id = this.modal.etiqueta.tema;
                        else this.modal.etiqueta.padre_id = null;

                        let url = Helper.limpiarCaracteres(this.modal.etiqueta.nombre);

                        this.modal.etiqueta.url = url;

                        axios.post("api/etiqueta/crear", this.modal.etiqueta)
                        .then((response) => {
                            this.$toastr.s(response.data.message);
                            $("#modal-agregar-etiquetas").modal('hide');
                            this.comboEtiquetas.push({
                                id: response.data.identificador,
                                nivel: this.modal.etiqueta.nivel,
                                nombre: this.modal.etiqueta.nombre,
                                padre_id: this.modal.etiqueta.padre_id,
                            });
                        })
                        .catch((error) => {
                            if(error.response.data.codigo == 'E001'){
                                this.modal.deshabilitarUrl = true;
                            }
                            this.$toastr.e(error.response.data.message);
                        });
                    }
                }); 
            },
        },//fin methods
    }
</script>