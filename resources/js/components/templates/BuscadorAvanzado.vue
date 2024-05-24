<template>
    <div class="input-group col-12 buscador-header">
        <div class="input-group-prepend">
            <span class="buscador-text" ><i class="fas fa-search"></i></span>
        </div>
        <input
        type="text"
        class="buscador-input "
        placeholder="Palabra clave"
        v-model="filtroBusqueda.texto"
        @keyup="getSugerencias()"
        @change="ocultarSugerencias()"
        >
        
        <div class="buscador-sugerencias" v-show="mostrarSugerencias">
            <div class="text">
                <span class="dropdown-item text-info" > Etiquetas </span>

                <template v-for="row in sugerencias.etiquetas">
                    <a class="dropdown-item" href="#" @click.prevent="redireccionarEtiqueta(row)" :key="row.id" >
                        <span v-text="row.titulo"></span>
                    </a>
                </template>
                <a class="dropdown-item" v-if="sugerencias.etiquetas.length == 0" >
                    <span>¡No se encontraron resultados!</span>
                </a>
                <div class="dropdown-divider"></div>
                <span class="dropdown-item text-info" > Preguntas </span>

                <template v-for="row in sugerencias.preguntas">
                    <a class="dropdown-item" href="#" @click.prevent="redireccionarDetallePregunta(1,row.id)" :key="row.id" >
                        <span v-text="row.titulo"></span>
                    </a>
                </template>
                <a class="dropdown-item" v-if="sugerencias.preguntas.length == 0" >
                    <span>¡No se encontraron resultados!</span>
                </a>
                <div class="dropdown-divider"></div>
                <span class="dropdown-item text-info" > Publicaciones </span>

                <template v-for="row in sugerencias.publicaciones">
                    <a class="dropdown-item" href="#" @click.prevent="redireccionarDetallePregunta(2,row.id)" :key="row.id" >
                        <span v-text="row.titulo"></span>
                    </a>
                </template>
                <a class="dropdown-item" v-if="sugerencias.publicaciones.length == 0" >
                    <span>¡No se encontraron resultados!</span>
                </a>
                <div class="dropdown-divider"></div>
                <span class="dropdown-item text-info" > Usuarios </span>

                <template v-for="row in sugerencias.usuarios">
                    <a class="dropdown-item" href="#" @click.prevent="redireccionarUsuario(row.id)" :key="row.id" >
                        <span v-text="row.nombres +' '+row.apellidos"></span>
                    </a>
                </template>
                <a class="dropdown-item" v-if="sugerencias.usuarios.length == 0" >
                    <span>¡No se encontraron resultados!</span>
                </a>
            </div>
        </div>

    </div>
</template>
<script>

import Helper from "../../services/Helper";
import Crypt from "../../services/Crypt";

export default {
    name: 'BuscadorAvanzado',
    data(){
        return{
            filtroBusqueda:{
                texto: '',
            },
            mostrarSugerencias: false,
            sugerencias:{
                etiquetas: [],
                preguntas: [],
                publicaciones: [],
                usuarios: [],

            }
        }
    },
    methods:{
        getSugerencias(){
            if(this.filtroBusqueda.texto.length > 2){
                this.mostrarSugerencias = true;
                axios.get("api/pregunta/listar_busqueda_avanzada"+ Helper.getFilterURL(this.filtroBusqueda))
                .then((response) => {
                    this.sugerencias = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$toastr.e(error.response.data.message);
                });
            }
            else
                this.mostrarSugerencias = false;

        },
        redireccionarDetallePregunta(tipo, pregunta){
            Helper.redireccionarDetallePregunta(this,(tipo==1 ? 'pregunta':'publicacion'), Crypt.encrypt(pregunta));
        },
        ocultarSugerencias(){
              setTimeout(() => {
                this.mostrarSugerencias = false;
            }, 1000);
        },
        redireccionarUsuario(usuario){
            Helper.redireccionarUsuario(this, Crypt.encrypt(usuario));
        },
        redireccionarEtiqueta(etiqueta){
            Helper.redireccionarEtiqueta(this, 'pregunta', etiqueta.url);
        },
    }
}
</script>
