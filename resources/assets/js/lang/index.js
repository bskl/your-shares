import Vue from 'vue';
import VueI18n from 'vue-i18n';
import { ls } from '../services/ls.js';

import en from './en/';
import tr from './tr/';

Vue.use(VueI18n);

const messages = {
    en,
    tr,
}

const numberFormats = {
    'en-US': {
        currency: {
            style: 'currency', currency: 'USD', currencyDisplay: 'symbol', minimumFractionDigits: 2,
        }
    },
    'tr': {
        currency: {
            style: 'currency', currency: 'TRY', currencyDisplay: 'symbol', minimumFractionDigits: 2,
        }
    }
}

// Create VueI18n instance with options
export default new VueI18n({
    locale: ls.get('language') || window.navigator.userLanguage || window.navigator.language,
    fallbackLocale: 'tr',
    messages,
    numberFormats,
});
