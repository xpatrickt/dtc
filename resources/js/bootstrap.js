window._ = require('lodash');

import Vue from 'vue/dist/vue'
import VueAxios from 'vue-axios';
import axios from 'axios';
import VueToastr from "vue-toastr";
import VueRouter from 'vue-router'
import VeeValidate, { Validator } from 'vee-validate';
import VueValidationEs from 'vee-validate/dist/locale/es.js';
import Loading from 'vue-loading-overlay';

import 'element-ui/lib/theme-chalk/index.css';
import lang from 'element-ui/lib/locale/lang/es';
import locale from 'element-ui/lib/locale';

// import DataTables and DataTablesServer together
import VueDataTables from 'vue-data-tables';
Vue.use(VueDataTables)
import ElementUI from 'element-ui';
Vue.use(ElementUI);
locale.use(lang);

import "vue-easytable/libs/theme-default/index.css"; // import style
import VueEasytable, { VePagination } from "vue-easytable"; // import library
Vue.use(VueEasytable);
Vue.use(VePagination);

import VueGoodTablePlugin from 'vue-good-table';

// import the styles
import 'vue-good-table/dist/vue-good-table.css'

Vue.use(VueGoodTablePlugin);
// Import css

import '@fortawesome/fontawesome-free/js/all.js';
import 'trumbowyg/dist/plugins/mention/trumbowyg.mention.min.js';
import 'trumbowyg/plugins/ruby/trumbowyg.ruby.js';

//import 'trumbowyg/plugins/etiquetar/trumbowyg.etiquetar.js'

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */
Vue.use(VueAxios, axios);

Vue.use(VueRouter);
Vue.use(VueToastr, {
    'defaultTimeout': 3000,
});

Vue.use(Loading, {
    color: '#3490dc',
    height: 100,
    width: 100,
    loader: 'bars'
});



Vue.use(VeeValidate, {
    // locale: 'es',
    dictionary: {
        es: VueValidationEs // si no hay valores o muestra el valore deecto invalid agregar aqui
    },
    classes: true,
    classNames: {
        valid: 'is-valid',
        invalid: 'is-invalid'
    },
    aria: true,
    errorBagName: 'errors', // change if property conflicts
    events: 'input|blur',
    fieldsBagName: 'fields',
    i18n: null, // the vue-i18n plugin instance
    i18nRootKey: 'validations', // the nested key under which the validation messages will be located
    inject: true,
    locale: 'es',
    validity: false,
    useConstraintAttrs: true
});

//variables globales
Vue.prototype.$infoAyuda = { mostrar: false, titulo: '..', contenido: [] };
Vue.prototype.$baseUrlVue = function (valor) {
    return  process.env.MIX_APP_URL + '/' + valor;
}



try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.baseURL = process.env.MIX_APP_URL;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token_laravel');
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });