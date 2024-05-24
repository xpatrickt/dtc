<template>
    <div class="modal fade" id="modal-invitado" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">USUARIO INVITADO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Debes iniciar sesión para poder aportar con respuestas, preguntas o calificar.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click.prevent="redireccionarVistaInvitado" data-dismiss="modal">Iniciar Sesión</button>
                  </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name:'ModalInvitadoLogin',
    methods:{
        redireccionarVistaInvitado(){
            axios.post('api/auth/logout').then(response => {
                $('.modal').modal('hide');
                localStorage.removeItem('token_linkedin');
                localStorage.removeItem('token_laravel');
                axios.defaults.headers.common['Authorization'] = null;
                this.$store.dispatch('resetAuthUserDetail');
                this.$toastr.s("Cierre de sesión correcto");
                this.$router.push('/auth/login');
            }).catch(error => {
                console.log(error);
            });
        },
    }

}
</script>
