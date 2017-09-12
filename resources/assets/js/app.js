
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue';
import router from './router/';
import i18n from './lang/';
import Form from './utilities/Form.js';
import { http } from './services/http.js';
import NProgress from 'nprogress';
import App from './components/App.vue';

window.Vue = Vue;
window.http = http;
window.Form = Form;
window.NProgress = NProgress;

window.Bus = new Vue({name: 'Bus'});

const app = new Vue({
    el: '#app',

    router,
    i18n,

    created() {
        http.init();
    },

    render: h => h(App),
});
