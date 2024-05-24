<template>
    <div>

        <form class="bd-search d-flex align-items-center pl-0 pr-0 pt-4">
          <!-- <input type="search" id="search-input" placeholder="Buscar..." aria-label="Search for..." autocomplete="off" spellcheck="false" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-owns="algolia-autocomplete-listbox-0" dir="auto" class="form-control ds-input" style="position: relative; vertical-align: top;"> -->
            <div class="col-md-12">
                <buscador-avanzado></buscador-avanzado>
            </div>
            <button class="btn btn-link bd-search-docs-toggle d-md-none p-0 ml-3 collapsed" type="button" data-toggle="collapse" data-target="#bd-docs-nav" aria-controls="bd-docs-nav" aria-expanded="false" aria-label="Toggle docs navigation"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30" focusable="false"><title>Menu</title><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"></path></svg>
            </button>
            
        </form>

        <nav class="list-unstyled ps-0 bd-links-v3 collapse" id="bd-docs-nav">
            <div class="bd-toc-item">

                <a v-for="row in comboEtiquetas" :key="row.id" class="bd-toc-link" href="#" @click.prevent="redireccionarEtiqueta(row)" >
                    <div class="progress barra-rubro">
                        <span v-text="row.nombre"></span>
                        <div class="progress-bar bg-color-2" role="progressbar" :style="'width: '+row.porcentaje+'%;'" :aria-valuenow="row.sum" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </a>
            </div>            
        </nav>

        <nav class="bd-links-v2 collapse footer-sidebar">
            <div class="text">
                <h4>Tendencias</h4>
                <ul class="list-unstyled">
                    <li v-for="row in tendencias" :key="row.id" @click.prevent="redireccionarEtiqueta(row)">
                        <a class="" href="#"  v-text="'#'+row.nombre"  ></a>
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
    name:'SidebarInicio',
    components:{
        BuscadorAvanzado
    },
    props:{
    },
    data(){
        return {
            filtroBusqueda:{
                texto: '',
            },
            mostrarSugerencias: false,
            sugerencias:[],
            comboEtiquetas:[],
            listaEtiquetas:[],
            tendencias:[],
        }
    },
    mounted(){
        this.getEtiquetas();
        this.getTendenciaRubros();
    },
    methods:{
        redireccionarEtiqueta(etiqueta){
            Helper.redireccionarEtiqueta(this, 'pregunta', etiqueta.url);
        },
        getEtiquetas(){
            axios.get("api/etiqueta/rubros_criterio")
            .then((response) => {
                this.listaEtiquetas = response.data;
                let array = Helper.sumarRubros(response.data);
                let newA = [];
                array.forEach(item => {
                    if(item.nivel == 1)
                        newA.push(item);
                })
                let total = newA.reduce(function (total, currentValue) {
                    return total + Number(currentValue.sum);
                }, 0);
                newA.forEach(row => {
                    row.porcentaje = row.sum * 100 / total 
                })
              
                newA.sort(this.GetSortOrderInverso('url'));
                newA.sort(this.GetSortOrder('sum'));
                this.comboEtiquetas = newA;
            })
            .catch((error) => {
                console.log(error);
                this.$toastr.e(error.response.data.message);
            }); 
        },
        GetSortOrder(prop) {    
            return function(a, b) {    
                if (a[prop] < b[prop]) {    
                    return 1;    
                } else if (a[prop] > b[prop]) {    
                    return -1;    
                }    
                return 0;    
            }    
        },
        GetSortOrderInverso(prop) {    
            return function(a, b) {    
                if (a[prop] > b[prop]) {    
                    return 1;    
                } else if (a[prop] < b[prop]) {    
                    return -1;    
                }    
                return 0;    
            }    
        },
        getTendenciaRubros(){
            axios.get("api/etiqueta/listar_tendencia_rubros")
            .then((response) => {
                this.tendencias = response.data;
            })
            .catch((error) => {
                console.log(error);
                this.$toastr.e(error.response.data.message);
            }); 
        }
        
    }
}
</script>
<style>


</style>
