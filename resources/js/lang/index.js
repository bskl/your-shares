import Vue from "vue";
import VueI18n from "vue-i18n";
import messages from "./map";
import { languages } from "./map";

Vue.use(VueI18n);

const fallbackLocale = "tr";

const getNavigatorLocale = function() {
  if (typeof document !== 'undefined') {
    let locale = document.documentElement.lang;

    if (locale.includes("-") || locale.includes("_")) {
      return locale.substring(0, 2);
    }
  }

  return false;
}

const userLocale = function() {
  let locale = JSON.parse(localStorage.getItem('locale'));

  if (locale !== 'undefined') {
    return locale;
  }

  return false;
}

const getLocale = function() {
  let locale = userLocale() || getNavigatorLocale();
  let { lang } = languages.find(l => locale === l.alternate || locale === l.locale) || {}
  
  if (lang) {
    return locale;
  }

  return fallbackLocale;
}

// Create VueI18n instance with options
export default new VueI18n({
  locale: getLocale(),
  fallbackLocale: fallbackLocale,
  messages,
});
