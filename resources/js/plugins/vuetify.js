import Vue from 'vue';
import Vuetify from 'vuetify';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import tr from 'vuetify/es5/locale/tr';
import en from 'vuetify/es5/locale/en';

Vue.use(Vuetify)

const opts = {
  lang: {
    locales: { tr, en },
    current: 'tr',
  },
  theme: {
    dark: true,
  },
  icons: {
    iconfont: 'md',
  },
}

export default new Vuetify(opts)