<template>
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <a href="#" @click.prevent="redireccionarExplorarEtiqueta(tema)" class="text-decoration-none h6 text-dark">
                    <strong v-text="tema.nombre" > </strong>
                </a>
                <div class="float-right">
                    <span class="text-muted" v-text="tema.subtemas + ' subtemas'"> </span>
                </div>
                <p class="card-text" v-text="tema.descripcion" >

                </p>
                <div class="mb-0">
                    <span v-text="tema.actividad_hoy+' hoy'"></span>
                    <span class="float-right" v-text="tema.actividad_sem+' esta semana'"></span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import Helper from '../../services/Helper'
import Crypt from "../../services/Crypt";

export default {
    name: 'Tema',
    props:{
        tema:{
            type: Object,
        }
    },
    methods:{
        redireccionarExplorarEtiqueta(tema){
            if(tema.subtemas > 0)
                Helper.redireccionarExplorar(this, Crypt.encrypt(tema.id));
            else
               Helper.redireccionarEtiqueta(this, 'pregunta', tema.url);

        },
    }
}
</script>
