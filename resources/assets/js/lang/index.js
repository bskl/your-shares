import Vue from 'vue';
import VueI18n from 'vue-i18n';
import { ls } from '../services/ls.js';
import { userStore } from '../stores/userStore';

import en from './en/';
import tr from './tr/';

Vue.use(VueI18n);

const messages = {
    en,
    tr,
}

const numberFormats = {
    'en': {
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

const fallbackLocale = 'tr';

const getNavigatorLocale = function() {
    let locale = window.navigator.userLanguage || window.navigator.language;

    if (locale.includes('-') || locale.includes('_')) {
        locale = locale.substring(0, 2);
    }

    return locale;
}

const getLocale = function() {
    let locale = getNavigatorLocale();

    if (ls.get('locale')) {
        locale = ls.get('locale');
    }
    if (userStore.isAuthenticated()) {
        locale = userStore.state.user.locale;
    }

    if (!locale) {
        locale = fallbackLocale;
    }

    ls.set('locale', locale);

    return locale;
}

// Create VueI18n instance with options
export default new VueI18n({
    locale: getLocale(),
    fallbackLocale: fallbackLocale,
    messages,
    numberFormats,
});
