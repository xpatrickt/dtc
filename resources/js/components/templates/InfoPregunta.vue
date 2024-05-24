<template>
    <div class="contenedor-info-ayuda"> 
        <template v-if="es_pregunta">
            <div class="text-center">
                <img :src="$baseUrlVue('img/otros/sugerencia_pregunta.png')" alt="">
            </div>
            <h2>
                Redacta tu pregunta
            </h2>
            
            <p>
            La comunidad le ayudará a resolver su pregunta.
            </p>
            <p>
            Evite hacer preguntas basadas en opiniones.
            </p>
            <ol class="list-unstyled">
                <li>
                    <strong>1.</strong> Resume la consulta.
                </li>
                <li>
                     <strong>2.</strong> Describe lo que has intentado para solucionar.
                </li>
                <li>
                    <strong>3.</strong> Agrega imágenes de ser necesario.
                </li>
            </ol>
        </template>
        <template v-else>
            <div class="text-center pt-4">
                <img :src="$baseUrlVue('img/otros/sugerencia_publicacion.png')" alt="">
            </div>
            <h2>
                Redacta tu publicación
            </h2>
            <p>
                Comparte tu experiencia con la comunidad.
            </p>
            <ol class="list-unstyled">
                <li>
                    <strong>1.</strong> Utiliza texto claro y comprensible.
                </li>
                <li>
                     <strong>2.</strong> Describe tu experiencia o conocimiento.
                </li>
                <li>
                    <strong>3.</strong> Agrega imágenes de ser necesario.
                </li>
            </ol>
            <!-- <nav class="bd-links-v3 collapse" id="bd-docs-nav">
                <div class="bd-toc-item">
                    <h4>
                        Más Populares:
                    </h4>
                    <hr>
                    <div class="mas-populares">
                        <div v-for="row in preguntas" :key="row.id">
                            <h6>
                                <strong v-text="row.titulo"></strong>
                            </h6>
                            <small class="text-muted" v-text="formatSoloFecha(row.created_at)"></small>
                            <p v-html="row.cuerpo">

                            </p>
                        </div>
                    </div>
                
                </div>            
            </nav> -->
        </template>
    </div>
</template>
<script>

import Helper from "../../services/Helper";

export default {
    props:{
        es_pregunta:{
            type: Boolean,
            require: true,
        }
    },
    data(){
        return{
            preguntas:[],
        }
    },
    mounted(){
        
    },
    methods:{
        getPopulares(){
            if(!this.es_pregunta){
                let busqueda = {
                    es_pregunta: this.es_pregunta,
                };
                axios.get("api/pregunta/listar_basico"+ Helper.getFilterURL(busqueda))
                .then((response) => {
                    this.preguntas = response.data;
                })
                .catch((error) => {
                    console.log(error);
                    this.$toastr.e(error.response.data.message);
                }); 
            }
        },
        formatSoloFecha(fecha){
            return Helper.formatearFecha(fecha);
        }
    }
}
</script>
