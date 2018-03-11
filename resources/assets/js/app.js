
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue';
import Vuetify from 'vuetify';
import router from './router/';
import i18n from './lang/';
import Form from './utilities/Form.js';
import { http } from './services/http.js';
import NProgress from 'nprogress';
import App from './components/App.vue';

window.Vue = Vue; 
Vue.use(Vuetify)

window.http = http;
window.Form = Form;
window.NProgress = NProgress;

NProgress.configure({
    showSpinner: false
});

window.Bus = new Vue({
    name: 'Bus'
});

const app = new Vue({
    el: '#app',

    router,
    i18n,

    /**
     * The component's data.
     */
    data(){
        return {
            snackbar: {
                show: false,
                position_y: 'top',
                color: 'success',
            },
        }
    },

    created() {
        http.init();
    },

    render: h => h(App),
});
