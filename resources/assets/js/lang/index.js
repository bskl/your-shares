import Vue from 'vue';
import VueI18n from 'vue-i18n';

import en from './en/';
import tr from './tr/';

Vue.use(VueI18n);

let messages = {
    en,
    tr,
}

// Create VueI18n instance with options
export default new VueI18n({
    locale: window.navigator.userLanguage || window.navigator.language,
    fallbackLocale: 'en',
    messages,
});
