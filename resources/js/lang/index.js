import Vue from "vue";
import VueI18n from "vue-i18n";
import messages from "./map";

Vue.use(VueI18n);

const fallbackLocale = "tr";

const getNavigatorLocale = function() {
  let locale = window.navigator.userLanguage || window.navigator.language;

  if (locale.includes("-") || locale.includes("_")) {
    locale = locale.substring(0, 2);
  }

  return locale;
};

const getLocale = function() {
  return JSON.parse(localStorage.getItem('locale')) || getNavigatorLocale();
};

// Create VueI18n instance with options
export default new VueI18n({
  locale: getLocale(),
  fallbackLocale: fallbackLocale,
  messages,
});
