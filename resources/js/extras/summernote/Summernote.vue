<template>
    <div class="summernote">
     xd
    </div>

</template>
<script>

export default {
    components: {
        
    },
    props:{
     
    },
    
    data() {
        return {
            ms:'',
            editor: '',
            textoEditorIDLocal: '',
        }
    },
    mounted(){
      // this.init();
    },
    methods:{
        init(){
            console.log(this.infoEditorTexto);
            this.editor = CKEDITOR.replace( 'ckeditor-textarea' );
            this.editor.config.readOnly = this.readOnly;
            this.textoEditorIDLocal = this.textoEditorID;
            setTimeout(() => {
                if(this.infoEditorTexto.texto){
                    this.editor.setData(this.infoEditorTexto.texto.descripcion);
                    this.textoEditorIDLocal = this.infoEditorTexto.texto.id;
                }
            }, 1000);
        },
        guardarTexto(){
            console.log("meustra");
            console.log(this.infoEditorTexto);
            
            console.log(this.editor.getData());

            let texto ={
                orden_id: this.infoEditorTexto.orden_id,
                tabla_ref: this.infoEditorTexto.tabla_ref,
                adicional_ref: this.infoEditorTexto.adicional_ref,
                referencia_id: this.infoEditorTexto.referencia_id,
                descripcion: this.editor.getData(),
            }
           
            axios.post('api/archivo/registrar_texto', texto)
            .then(response => {
                toastr['success'](response.data.message);
                this.textoEditorIDLocal = response.data.identificador;
            }).catch(error => {
                console.log(error);
                toastr['error'](error.response.data.message);
            });
        },
        actualizarTexto(){
            let texto ={
                orden_id: this.infoEditorTexto.orden_id,
                tabla_ref: this.infoEditorTexto.tabla_ref,
                adicional_ref: this.infoEditorTexto.adicional_ref,
                referencia_id: this.infoEditorTexto.referencia_id,
                descripcion: this.editor.getData(),
            }
            axios.put('api/archivo/modificar/' + this.textoEditorIDLocal , texto)
            .then(response => {
                toastr['success'](response.data.message);
            }).catch(error => {
                console.log(error);
                toastr['error'](error.response.data.message);
            });
        },
        
    },
}
</script>