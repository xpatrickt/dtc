<template>
    <div>
        <form class="bd-search d-flex align-items-center pl-0 pr-0 pt-4">
            <div class="col-md-12">
                <buscador-avanzado></buscador-avanzado>
            </div>
            <button class="btn btn-link bd-search-docs-toggle d-md-none p-0 ml-3 collapsed" type="button" data-toggle="collapse" data-target="#bd-docs-nav" aria-controls="bd-docs-nav" aria-expanded="false" aria-label="Toggle docs navigation"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30" focusable="false"><title>Menu</title><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"></path></svg>
            </button>
        </form>

        <nav class="bd-links collapse" id="bd-docs-nav">
            <div class="bd-toc-item">
                
                <a class="bd-toc-link h6" href="#" @click.prevent="redireccionarEtiqueta(comboEtiquetas.url)" >
                    <span v-text="'#'+comboEtiquetas.nombre"></span>
                </a>
                
                <ul class="nav bd-sidenav">
                    <li v-for="row in comboEtiquetas.hijos" :key="row.id" >
                        <a href="#" 
                            class="font-weight-bold"
                            v-text="row.nombre">
                        </a>
                        <ul class="nav bd-sidenav">
                            <li v-for="row2 in row.hijos" :key="row2.id" >
                                <a href="#" 
                                    v-text="row2.nombre">
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>

<script>
import Helper from "../../services/Helper";
import BuscadorAvanzado from "./BuscadorAvanzado";


export default {
    name:'SidebarExplorar',
    components:{
        BuscadorAvanzado,
    },
    props:{
        comboEtiquetas:{
            type: Object,
            required: true,
        }
    },
    methods:{
        redireccionarEtiqueta(etiqueta){
            return false;
            Helper.redireccionarEtiqueta(this, 'pregunta', etiqueta);
        },
    }
}
</script>