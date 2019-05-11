import Vue from "vue";
import VueI18n from "vue-i18n";
import ls from "local-storage";
import messages from "./map";

Vue.use(VueI18n);

const numberFormats = {
  en: {
    currency: {
      style: "currency",
      currency: "USD",
      currencyDisplay: "symbol",
      minimumFractionDigits: 2
    },
    percent: {
      style: "percent",
      minimumFractionDigits: 2
    },
    decimal: {
      style: "decimal",
      minimumFractionDigits: 3,
      maximumFractionDigits: 3
    }
  },
  tr: {
    currency: {
      style: "currency",
      currency: "TRY",
      currencyDisplay: "symbol",
      minimumFractionDigits: 2
    },
    percent: {
      style: "percent",
      minimumFractionDigits: 2
    },
    decimal: {
      style: "decimal",
      minimumFractionDigits: 3,
      maximumFractionDigits: 3
    }
  }
};

const dateTimeFormats = {
  en: {
    short: {
      year: "numeric",
      month: "short",
      day: "2-digit"
    },
    long: {
      year: "numeric",
      month: "short",
      day: "numeric",
      weekday: "short",
      hour: "numeric",
      minute: "numeric"
    }
  },
  tr: {
    short: {
      day: "numeric",
      month: "short",
      year: "2-digit"
    },
    long: {
      year: "numeric",
      month: "short",
      day: "numeric",
      weekday: "short",
      hour: "numeric",
      minute: "numeric",
      hour12: true
    }
  }
};

const fallbackLocale = "tr";

const getNavigatorLocale = function() {
  let locale = window.navigator.userLanguage || window.navigator.language;

  if (locale.includes("-") || locale.includes("_")) {
    locale = locale.substring(0, 2);
  }

  return locale;
};

const getLocale = function() {
  let locale = getNavigatorLocale();

  if (ls.get("locale")) {
    locale = ls.get("locale");
  }

  if (!locale) {
    locale = fallbackLocale;
  }

  ls.set("locale", locale);

  return locale;
};

// Create VueI18n instance with options
export default new VueI18n({
  locale: getLocale(),
  fallbackLocale: fallbackLocale,
  messages,
  numberFormats,
  dateTimeFormats
});
