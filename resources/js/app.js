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
