/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
import Vue from "vue";
import Vuetify from "vuetify";
import { sync } from 'vuex-router-sync'
import router from "./router";
import store from "./store";
import i18n from "./lang/";
import Form from "./utilities/Form.js";
import NProgress from "nprogress";
import App from "./App.vue";

sync(store, router)
Vue.use(Vuetify);

window.Form = Form;
window.NProgress = NProgress;

NProgress.configure({
  showSpinner: false
});

new Vue({
  el: "#app",
  router,
  store,
  i18n,

  render: h => h(App)
});
