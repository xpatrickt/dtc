<template>
    <!-- Modal -->
    <div class="modal fade" id="modal-ayuda" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" v-text="titulo"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="carousel-ayuda" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li v-for="(row, index) in contenido" data-target="#carousel-ayuda" :data-slide-to="index" :key="index" :class="row.itemActivo"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div v-for="(row, index) in contenido" :key="index" :class="'carousel-item '+ row.itemActivo">
                                <div class="card col-12 p-4" style="border:none;" >
                                    <div class="row justify-content-md-center">
                                        <img class="col-md-5" :src="$baseUrlVue('user/ayuda/'+row.imagen)" alt="slide">
                                    </div>
                                    <div class="card-body text-center">
                                        <h2 class="card-title" v-text="row.titulo"></h2>
                                        <p class="card-text" v-text="row.descripcion"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carousel-ayuda" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                        </a>
                        <a class="carousel-control-next" href="#carousel-ayuda" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Siguiente</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name:'ModalAyuda',
    props:{
        titulo:{
            type: String,
            default: ''
        },
        contenido: {
            type: Array,

        }
    }

}
</script>


<style>
.carousel-indicators li
{
    background-color: black;
}
.carousel-control-prev-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%3fff' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E");
}

.carousel-control-next-icon {
    background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%3fff' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E");
}
.card-image{
    align-items: center;
    width: 50%;
    height: auto;
}

</style>
