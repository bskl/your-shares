import Vue from "vue";
import vuetify from "./plugins/vuetify";
import { sync } from 'vuex-router-sync';
import router from "./router";
import store from "./store";
import i18n from "./lang/";
import App from "./App.vue";

sync(store, router);

Vue.config.productionTip = false;

new Vue({
  el: "#app",
  vuetify,
  router,
  store,
  i18n,

  render: h => h(App)
});
