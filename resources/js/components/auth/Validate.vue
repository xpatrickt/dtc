<template>
    <div>
       <!--  Espere un momento por favor... te estamos redireccionando. -->
    </div>
</template>


<script>
export default {
    name: 'Validate',
    data(){
        return {
            urlReceived: null,
            loader: null,
        }
    },
    created(){
        this.loader = this.$loading.show({
            // Optional parameters
            container: this.fullPage ? null : this.$refs.formContainer,
            canCancel: true,
            onCancel: this.onCancel,
        });
        /* // simulate AJAX
        setTimeout(() => {
            loader.hide()
        },5000)  */
    },
    mounted(){
        this.cargaInicial();
    },
    destroyed(){
        this.loader.hide()
    },
    methods:{
        cargaInicial(){//falta usar el state para mas seguridad
            if(this.getQueryVariable(window.location.search, 'error')){//Si el login es incorrecto
                this.$toastr.e('Error al iniciar la sesión');
                this.$router.push('/admin/login');
            }
            console.log('entro en validate');
            let code = this.getQueryVariable(window.location.search, 'code');
            if(code){//Si el login es correcto
                let state = this.getQueryVariable(window.location.search, 'state');

                let data = {
                    code: code,
                    state: state,
                    recuerdame: localStorage.getItem("recuerdame"),
                }
                axios
                .post("api/auth/login_linkendin", data)
                .then((response) => {
                    localStorage.setItem("token_linkedin", response.data.token_linkendin);
                    localStorage.setItem("token_laravel", response.data.token_laravel);
                    axios.defaults.headers.common["Authorization"] = "Bearer " + localStorage.getItem("token_laravel");
                    if(response.data.nuevo)
                        this.$router.push('/auth/register');
                    else
                        this.$router.push('/auth/inicio');
                })
                .catch((error) => {
                    console.log(error);
                    this.$toastr.e(error.response.data.message);
                });
            }
            setTimeout(() => {
                this.$router.push('/admin/login');
            }, 5000);
        },
        getQueryVariable(url, variable) {
            let params = new URLSearchParams(url);
            var valor = params.get(variable);
            if(!valor){
                return false;
            }
            return valor;
        },
    }//fin methods
}
</script>
