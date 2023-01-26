<script>

import { mapGetters, mapActions } from "vuex";
import { languages as list } from "../../lang/map";
import { parseErrorMessage } from '../../utilities/helpers.js';

export default {
  /**
   * The component's name.
   */
  name: 'MainFooter',

  /**
   * The component's data.
   */
  data() {
    return {};
  },

  computed: {
    ...mapGetters([
      'isLoggedIn',
    ]),

    languages() {
      return list;
    },

    currentLanguage() {
      return this.languages.find(l => l.locale === this.$i18n.locale);
    }
  },

  methods: {
    ...mapActions([
      'setLocale',
    ]),

    setI18nLanguage(locale) {
      this.$i18n.locale = locale;
      this.$vuetify.lang.current = locale;

      if (typeof document !== 'undefined') {
        document.querySelector('html').setAttribute('lang', locale)
      }
    },

    /**
     * Change the language.
     */
    translateI18n(locale) {
      setTimeout(() => {
        this.setI18nLanguage(locale);

        if (this.isLoggedIn) {
          this.setLocale(locale)
            .then()
            .catch((error) => {
              parseErrorMessage(error);
            });
        }
      }, 1000);
    }
  }
};
</script>

<template>
  <v-footer
    app
    absolute
    inset
    padless
    class="pl-4 text-caption font-weight-light"
  >
    <v-menu
      bottom
      left
      offset-y
      max-height="calc(100% - 16px)"
      transition="slide-y-reverse-transition"
    >
      <template #activator="{ attrs, on }">
        <v-btn
          class="text-capitalize text--secondary"
          small
          text
          v-bind="attrs"
          v-on="on"
        >
          <v-icon
            small
            left
            class="mr-1"
          >
            translate
          </v-icon>
          <span
            class="body-2 text-capitalize font-weight-light hidden-sm-and-down"
            v-text="currentLanguage.title"
          />
          <v-icon
            small
            class="ml-1 hidden-sm-and-down"
            right
          >
            arrow_drop_up
          </v-icon>
        </v-btn>
      </template>
      <v-list
        dense
        nav
      >
        <v-list-item
          v-for="language in languages"
          :key="language.locale"
          @click="translateI18n(language.locale)"
        >
          <v-list-item-title
            v-text="language.title"
          />
        </v-list-item>
      </v-list>
    </v-menu>
    <v-col class="text-right">
      &copy; {{ new Date().getFullYear() }} —
      <strong>yourshares</strong>
    </v-col>
  </v-footer>
</template>
