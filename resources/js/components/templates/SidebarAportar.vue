<template>
    <div class="p-4">
        <h3>
            Colaborar
        </h3>
        <nav class="bd-links-v2 collapse"  id="bd-docs-nav">
            
            <div class="bd-toc-item pl-3 pr-3">
           
                <h5>Ayuda calificando</h5>
                <ul class="list-unstyled">
                    <li class="mouse-pointer-click text-primary" v-for="row in preguntasCalificar" :key="row.id" @click.prevent="redireccionarDetallePregunta(row.tipo, row.id)" >
                        <span v-text="row.titulo"></span>
                    </li>
                </ul>
                <h5>Moderar</h5>
                <ul class="list-unstyled">
                    <li class="mouse-pointer-click text-primary" v-for="row in preguntasModerar" :key="row.id" @click.prevent="redireccionarDetallePregunta(row.tipo, row.id)" >
                        <span v-text="row.titulo"></span>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>
<script>

import Helper from "../../services/Helper";
import Crypt from "../../services/Crypt";

export default {
    name:'SidebarAportar',
    data(){
        return {
            preguntasCalificar:[],
            preguntasModerar:[],
        }
    },
    mounted(){
        this.getPreguntasCalificar();
        this.getPreguntasModerar();
    },
    methods:{
        getPreguntasCalificar(){
            axios.get("api/pregunta/listar_calificar_top")
            .then((response) => {
                this.preguntasCalificar=response.data;
            })
            .catch((error) => {
                this.$toastr.e(error.response.data.message);
            }); 
        },
        getPreguntasModerar(){
            axios.get("api/pregunta/listar_moderar_top")
            .then((response) => {
                this.preguntasModerar=response.data;
            })
            .catch((error) => {
                this.$toastr.e(error.response.data.message);
            }); 
        },
        redireccionarDetallePregunta(tipo, pregunta){
            Helper.redireccionarDetallePregunta(this,(tipo==1 ? 'pregunta':'publicacion'), Crypt.encrypt(pregunta));
        },

    },    
}
</script>

<style>

</style>