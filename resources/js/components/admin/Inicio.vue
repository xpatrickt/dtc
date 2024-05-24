<template>
    <div class="p-3 pt-5">
        <h4 class="text-color-2 mb-3">ADMNISTRACIÓN</h4>
        <hr>
        <div class="row justify-content-md-center">
            <img :src="$baseUrlVue('img/auth/gestion.png')" alt="" class="mt-3 opacity-10"/>
           <!-- <div class="col-md-4">
                <div class="form-group text-center">
                    <label for="">Usuarios</label>
                    <br>
                    <h1 style="font-weight: 900; font-size: 50px;" v-text="cantidadUsuarios"></h1>
                </div>
            </div>  
            <div class="col-md-4">
                <div class="form-group text-center">
                    <label for="">Contenido</label>
                    <br>
                    <h1 style="font-weight: 900; font-size: 50px;" v-text="cantidadContenidos"></h1>
                </div>
            </div> -->
        </div>
        <!-- <hr> -->
        <!-- <div class="row justify-content-md-center">
            <div class="col-md-4">
                <div class="form-group text-center">
                    <chart v-if="cargaCompleta"
                        :key="1"
                        :dataSet="doughnutChart1"
                        :keyChart="1"
                    >
                    </chart>
                    Total preguntas: <span v-text="cantidadPreguntas"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group text-center">
                    <chart v-if="cargaCompleta"
                        :key="2"
                        :dataSet="doughnutChart2"
                        :keyChart="2"
                    >
                    </chart>
                    Total contenido: <span v-text="cantidadContenidos"></span>
                </div>
            </div> 
            <div class="col-md-4">
                <div class="form-group text-center">
                    <chart v-if="cargaCompleta"
                        :key="3"
                        :dataSet="doughnutChart3"
                        :keyChart="3"
                    >
                    </chart>
                    Total usuarios: <span v-text="cantidadUsuarios"></span>
                </div>
            </div> 
        </div> -->
    </div>
</template>

<script>

import Chart from '../charts/Chart.vue'

    export default {
        name:'Inicio',
        components:{
            Chart,
        },
        data(){
            return{
                cargaCompleta: false,
                cantidadUsuarios: 0,
                cantidadContenidos: 0,
                cantidadPreguntas: 0,
                doughnutChart1:{
                    type: 'doughnut',
                    data: {
                        labels: ['Respondidas', 'No respondidas'],
                        datasets: [
                            {
                            label: 'Dataset 1',
                            data: [0,0],
                            backgroundColor: ['rgba(0, 250, 0, 0.3)', 'rgba(0, 0, 0, 0.3)'],
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                        legend: {
                            position: 'top',
                            width: '322px',
                            height: '200px',
                        },
                        title: {
                            display: true,
                            text: 'Chart.js Doughnut Chart'
                        }
                        }
                    },
                
                },
                doughnutChart2:{
                    type: 'doughnut',
                    data: {
                        labels: ['Preguntas', 'Publicaciones'],
                        datasets: [
                            {
                            label: 'Dataset 1',
                            data: [0,0],
                            backgroundColor: ['rgba(0, 250, 0, 0.3)', 'rgba(0, 0, 0, 0.3)'],
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                        legend: {
                            position: 'top',
                            width: '322px',
                            height: '200px',
                        },
                        title: {
                            display: true,
                            text: 'Chart.js Doughnut Chart'
                        }
                        }
                    },
                
                },
                doughnutChart3:{
                    type: 'doughnut',
                    data: {
                        labels: ['Contenido', 'Sin Contenido'],
                        datasets: [
                            {
                            label: 'Dataset 1',
                            data: [0,0],
                            backgroundColor: ['rgba(0, 250, 0, 0.3)', 'rgba(0, 0, 0, 0.3)'],
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                        legend: {
                            position: 'top',
                            width: '322px',
                            height: '200px',
                        },
                        title: {
                            display: true,
                            text: 'Chart.js Doughnut Chart'
                        }
                        }
                    },
                
                },

            }
        },
        created(){
        //    this.iniciarDashboard();
        },
        mounted() {
           
        },
        methods:{
            iniciarDashboard(){
                axios.get("api/usuario/dashboard")
                .then((response) => {
                    let data = response.data;
                    this.cantidadUsuarios = data.totalUsuarios;
                    this.cantidadContenidos = data.totalContenido;
                    this.cantidadPreguntas = data.totalPreguntas;
                    this.doughnutChart1.data.datasets[0].data[0] = data.totalPreguntasRespondidas;
                    this.doughnutChart1.data.datasets[0].data[1] = data.totalPreguntasNoRespondidas;
                    this.doughnutChart2.data.datasets[0].data[0] = data.totalContenidoPreguntas;
                    this.doughnutChart2.data.datasets[0].data[1] = data.totalContenidoPublicaciones;
                    this.doughnutChart3.data.datasets[0].data[0] = data.totalUsuariosContenido;
                    this.doughnutChart3.data.datasets[0].data[1] = data.totalUsuariosNoContenido;
                    this.cargaCompleta = true;
                })
                .catch((error) => {
                    console.log(error);
                    this.$toastr.e(error.response.data.message);
                }); 
            },
            
        },
    }
</script>
<style >
  
</style>