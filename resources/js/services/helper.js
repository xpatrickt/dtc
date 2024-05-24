//variables



export default {
    async getOcupaciones(actualizar = false) {
        axios.get("api/ocupacion/llenar_combo")
            .then((response) => {
                localStorage.setItem("auth_token", response.data.token);
                axios.defaults.headers.common["Authorization"] = "Bearer " + localStorage.getItem("auth_token");
                toastr["success"](response.data.message);
                _this.$router.push("/inicio");
                return true;
            })
            .catch((error) => {
                toastr["error"](error.response.data.message);
                return false;
            });
    },
    //Inicio return htmls
    getValorClaveLista(lista, valor) {
        let elemento = lista.find(elem => elem.id == valor);
        return elemento.nombre;
    },
    getEtiquetaLista(lista, valor) {
        const estado = lista.find(elem => elem.id == valor);
        if (estado)
        //return '<span class="badge badge-' + estado.class + '">' + estado.nombre + '</span>';
            return '<span class="' + (estado.class ? ('badge badge-' + estado.class) : '') + '">' + estado.nombre + '</span>';
        else
            return '<span class="">No encontrado</span>'
    },
    //Fin return htmls

    getTiempoTranscurrido(time) {
        let fecha = new Date(time);
        let hoy = new Date();
        let milisegundos = (hoy - fecha);
        let segundos = milisegundos / 1000;
        if (segundos < 60)
            return 'Hace un instante.';
        let minutos = segundos / 60;
        if (minutos < 60)
            return 'Hace ' + Math.trunc(minutos) + ' minutos.';
        let horas = minutos / 60;
        if (horas < 24)
            return 'Hace ' + Math.trunc(horas) + ' horas.';
        let dias = horas / 24;
        if (dias < 30.417)
            return 'Hace ' + Math.trunc(dias) + ' dias.';
        let meses = dias / 30.417;
        if (meses < 12)
            if (meses == 1)
                return 'Hace ' + Math.trunc(meses) + ' mes.';
            else
                return 'Hace ' + Math.trunc(meses) + ' meses.';
        let anios = meses / 12;
        if (anios < 31)
            if (anios == 1)
                return 'Hace ' + Math.trunc(anios) + ' año.';
            else
                return 'Hace ' + Math.trunc(anios) + ' años.';
        return 'HACE MUCHO TIEMPO';
    },
    //Roles y permisos
    getFechaHoraActual() {
        return moment(new Date()).format('YYYY-MM-DD hh:mm:ss');
    },
    formatearFecha(fecha) {
        if (fecha) {
            let fec = new Date(fecha);
            return fec.toLocaleDateString() + ' ' + fec.toLocaleTimeString();
        }
    },
    formatearSoloFecha(fecha) {
        if (fecha) {
            let fec = new Date(fecha);
            return fec.toLocaleDateString();
        }
    },
    formatearSoloFechaGenerico(fecha){
        if(fecha){
            let anio = fecha.getFullYear()
            let mes = (fecha.getMonth() + 1).toString().padStart(2, 0)
            let dia = (fecha.getDate()).toString().padStart(2, 0)
            return anio + '-' + mes + '-' + dia;
        }
    },
    formatearFechaZH(fecha) {
        if (fecha) {
            //falta
            return fecha.replace(' ', 'T');
            console.log(fecha);
            return moment(String(fecha)).format('yyyy-MM-ddThh:mm')
        }
    },
    formatearHoraHM(hora) {
        if (hora) {
            return hora.substring(5, 0)
        }
    },
    formatFechaPersonalizada(){
        //ejm. [2021, 4, 26, 7, 0, 0, 0]
        return array[0] + '-' + this.getMonthInt(array[1], 'num') + '-' + array[2].toString().padStart(2, 0) + 'T' + array[3].toString().padStart(2, 0) + ':' + array[4].toString().padStart(2, 0) + ':' + array[5].toString().padStart(2, 0)
    },
    sumarHoraMinutos(tiempo, duracion, es_resta = false) {
        let splitHora = tiempo.split(':');
        let horaSegundos = splitHora[0] * 3600;
        let minutosSegundos = splitHora[1] * 60;
        let duracionSegundos = Number(duracion) * 60;
        let tiempoSegundos = 0;
        if (es_resta)
            tiempoSegundos = horaSegundos + minutosSegundos - duracionSegundos;
        else
            tiempoSegundos = horaSegundos + minutosSegundos + duracionSegundos;

        let hora = parseInt(tiempoSegundos / 3600) % 24;
        let minutos = parseInt(tiempoSegundos / 60) % 60;
        let segundos = tiempoSegundos % 60;

        //let resultado = (hora < 10 ? "0" + hora : hora) + ":" + (minutos < 10 ? "0" + minutos : minutos) + ":" + (segundos  < 10 ? "0" + segundos : segundos);
        //estrae solo hora y minutos
        let resultado = (hora < 10 ? "0" + hora : hora) + ":" + (minutos < 10 ? "0" + minutos : minutos);

        return resultado;

    },
    getFilterURL(data) {
        let url = '';
        $.each(data, function(key, value) {
            url += (value) ? '&' + key + '=' + encodeURI(value) : '';
        });
        if (url == '')
            return '';
        return '?' + url;
    },
    deshabilitarBoton(_this) {

        _this.desabilitado = true;
    },
    habilitarBoton(_this, segundos = 1) {
        setTimeout(function() { _this.desabilitado = false; }, (segundos * 1000));
    },
    esFechaMenor(ini, fin) {
        if (Date.parse(ini) < Date.parse(fin))
            return true;
        return false;
    },
    esHoraMenor(ini, fin) {
        let splitIni = ini.split(':');
        let horaSegundosIni = splitIni[0] * 3600;
        let minutosSegundosIni = splitIni[1] * 60;

        let splitFin = fin.split(':');
        let horaSegundosFin = splitFin[0] * 3600;
        let minutosSegundosFin = splitFin[1] * 60;

        if ((horaSegundosIni + minutosSegundosIni) < (horaSegundosFin + minutosSegundosFin))
            return true;
        return false;
    },
    obtenerCodigo(lista, prefijo, campo, ceros) {
        if (lista.length == 0)
            return prefijo + ('1'.padStart(ceros, 0));
        let ultimoValor = Number((lista[lista.length - 1][campo]).replace(prefijo, ''))
        return prefijo + ((ultimoValor + 1).toString().padStart(ceros, 0));
    },
    validarPermisos(permisos, valor) {
        let permiso = permisos.find(elem => elem.clave == valor);
        if (!permiso)
            return false;
        return permiso.valor;
    },
    validarPermisoArea(areasUsuario, area) {
        let permiso = areasUsuario.find(elem => elem.id == area);
        if (!permiso)
            return false;
        return true;
    },

    //personales
    redireccionarEtiqueta(_this, opcion, etiqueta) {
        _this.$router.push({
            name: "ExplorarPregunta",
            params: {
                opcion: opcion,
                etiqueta: etiqueta,
                id: this.generaCaracteresAleatorios(10),
            }
        });
    },
    redireccionarUsuario(_this, id) {
        _this.$router.push({
            name: "PerfilPublico",
            params: {
                id: id,
            }
        })
    },
    redireccionarDetallePregunta(_this, opcion, id) {
        _this.$router.push({
            name: "DetallePregunta",
            params: {
                id: id,
                opcion: opcion,
            }
        });
    },
    redireccionarRedactarPregunta(_this, accion, opcion, id) {
        _this.$router.push({
            name: "RedactarPregunta",
            params: {
                id: id,
                accion: accion,
                opcion: opcion,
            }
        });
    },
    redireccionarExplorar(_this, tema) {
        _this.$router.push({
            name: "Explorar",
            params: {
                id: tema,
            }
        });
    },

    //obtiene array mejorado
    sumarRubros(array, nivel_2 = false, column = 'sum') {
        let newa = [];

        array.forEach(element => {
            if (element.nivel == 3) {
                let prop = (element.nivel - 1) + '_' + element.padre_id;
                newa[prop] = newa[prop] || 0;
                newa[prop] += Number(element[column]);
            }
        });

        //sumar a nivel 2

        array.forEach(element => {
            if (element.nivel == 2) {
                element[column] += newa[element.nivel + '_' + element.id] || 0;
            }

        });

        if (nivel_2) return array; // solo el nivel 2
        //sumar a nivel 3
        let newb = [];
        array.forEach(element => {
            if (element.nivel == 2) {
                let prop = (element.nivel - 1) + '_' + element.padre_id;
                newb[prop] = newb[prop] || 0;
                newb[prop] += Number(element[column]);
            }
        });

        //sumar a nivel 1
        array.forEach(element => {
            if (element.nivel == 1) {
                element[column] += newb[element.nivel + '_' + element.id] || 0;
            }
        });
        return array;
    },
    mostrarModalAyuda(_this, titulo, contenido) {
        let info = _this.infoAyuda;
        if(contenido.length == 0 ) return false;
        info.titulo = titulo;
        info.contenido = contenido;
        info.mostrar = true;
        $('#modal-ayuda').modal('show');
    },
    mostrarModalInvitado(_this) {
        if (_this.$store.getters.getInvitado == true){
            $('#modal-invitado').modal('show');
            return true;
        }
        return false;
    },

    actualizarEstadoAyuda(_this, campo, obj, titulo, contenido) {
        if (_this.$store.getters.getInvitado == true)
            return true;
        if (_this.$store.getters.getAuthUser(campo) == false) {
            _this.infoAyuda.mostrar = true;
            setTimeout(() => {
                this.mostrarModalAyuda(_this, titulo, contenido)
                axios.post('api/usuario/actualizar_ayuda', obj)
                    .then(response => {
                        _this.$store.dispatch('setAuthUserDetail', obj);
                        //_this.$toastr.s(response.data.message);
                    }).catch(error => {
                        console.log(error);
                        _this.$toastr.e(error.response.data.message);
                    });
            }, 2000);
        }
    },

    generaCaracteresAleatorios(maxCaracteres) {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result1 = '';
        const charactersLength = characters.length;
        for (let i = 0; i < maxCaracteres; i++) {
            result1 += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result1;
    },

    limpiarCaracteres(txt){
        txt = this.quitarCaracteres(txt);
        txt = encodeURI(txt.toLowerCase()); 
        return txt;
    },
    quitarCaracteres(cadena){
        const acentos = {'á':'a','é':'e','í':'i','ó':'o','ú':'u','Á':'A','É':'E','Í':'I','Ó':'O','Ú':'U','#':'','/':'',' ':''};
        return cadena.split('').map( letra => acentos[letra] || letra).join('').toString();	
    }
    

}