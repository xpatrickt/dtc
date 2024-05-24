import Vue from 'vue/dist/vue'
import Vuex from 'vuex'
Vue.use(Vuex);
import createPersistedState from 'vuex-persistedstate'
import * as Cookies from 'js-cookie'

const store = new Vuex.Store({
    state: {
        auth: {
            usuario: null,
            nombres: null,
            apellidos: null,
            email: null,
            avatar: null,
            rol: null,
        },
        temporal: null,
        menuActivo:{
            inicio: true,
            explorar: false,
            aportar: false,
            notificaciones: false,
            mensajes: false,
        },
        ayuda: {
            inicio: [],
            pregunta: [],
            publicacion: [],
        },
        invitado: false,
    },
    mutations: {
        setAuthUserDetail(state, auth) {
            for (let key of Object.keys(auth)) {
                state.auth[key] = auth[key];
            }
            if ('avatar' in auth)
                state.auth.avatar = auth.avatar !== null ? auth.avatar : null; // antes 'avatar.png'; 
        },
        setTemporal(state, tmp) {
            state.temporal = tmp;
        },
        setAyuda(state, tmp) {
            state.ayuda = tmp;
        },
        resetAuthUserDetail(state) {
            for (let key of Object.keys(state.auth)) {
                state.auth[key] = '';
            }
        },
        resetTemporal(state) {
            state.temporal = null;
        },
        setActiveMenu(state, menu) {
            for (let key of Object.keys(state.menuActivo)) {
                state.menuActivo[key] = false;
            }
            state.menuActivo[menu] = true;
        },
        setInvitado(state, valor) {
            state.invitado = valor;
        },
    },
    actions: {
        setAuthUserDetail({ commit }, auth) {
            commit('setAuthUserDetail', auth);
        },
        setTemporal({ commit }, tmp) {
            commit('setTemporal', tmp);
        },
        setAyuda({ commit }, ayuda) {
            commit('setAyuda', ayuda);
        },
        resetAuthUserDetail({ commit }) {
            commit('resetAuthUserDetail');
        },
        resetTemporal({ commit }) {
            commit('resetTemporal');
        },
        setActiveMenu({ commit }, menu) {
            commit('setActiveMenu', menu);
        },
        setInvitado({ commit }, valor) {
            commit('setInvitado', valor);
        },
    },
    getters: {
        getAuthUser: (state) => (name) => {
            return state.auth[name];
        },
        getAuthUserNameComplete: (state) => () => {
            return state.auth.nombres + ' ' + state.auth.apellidos;
        },
        getAuthUserName: (state) => {
            return state.auth.nombre;
        },
        getRol: (state) => {
            return state.auth.rol;
        },
        getAreas: (state) => {
            return state.auth.areas;
        },
        getPermisos: (state) => {
            return state.auth.permisos;
        },
        getTemporal: (state) => {
            return state.temporal;
        },
        getActiveMenu: (state) => (name) => {
            return state.menuActivo[name];
        },
        getAyuda: (state) => (name) => {
            return state.ayuda[name];
        },
        getInvitado: (state) => {
            return state.invitado;
        },
        getNivelApp: () => {
            return 1;
        },
    },
    plugins: [
        createPersistedState({ storage: window.sessionStorage })
    ]
});

export default store;