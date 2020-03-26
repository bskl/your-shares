import Vue from "vue";
import VueI18n from "vue-i18n";
import messages from "./map";
import { languages } from "./map";

Vue.use(VueI18n);

const fallbackLocale = "tr";
const hasDocument = typeof document !== 'undefined';

const getNavigatorLocale = function() {
  if (hasDocument) {
    let locale = document.documentElement.lang;

    if (locale.includes("-") || locale.includes("_")) {
      return locale.substring(0, 2);
    }
  }

  return false;
}

const userLocale = function() {
  const locale = JSON.parse(localStorage.getItem('locale'));

  if (locale !== 'undefined') {
    return locale;
  }

  return false;
}

const getLocale = function() {
  const lang = userLocale() || getNavigatorLocale();

  const { locale } = languages.find(l => lang === l.alternate || lang === l.locale) || { locale: fallbackLocale };

  if (hasDocument) {
    document.querySelector('html').setAttribute('lang', locale)
  }

  return locale;
}

// Create VueI18n instance with options
export default new VueI18n({
  locale: getLocale(),
  fallbackLocale: fallbackLocale,
  messages,
});
