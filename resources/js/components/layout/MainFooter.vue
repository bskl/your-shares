<script>

import { mapActions, mapGetters } from "vuex";
import { languages as list } from "../../lang/map";

export default {
  /**
   * The component's name.
   */
  name: "MainFooter",

  /**
   * The component's data.
   */
  data() {
    return {};
  },

  computed: {
    ...mapGetters(["isLoggedIn"]),

    languages() {
      return list;
    },

    currentLanguage() {
      return this.languages.find(l => l.locale === this.$i18n.locale);
    }
  },

  methods: {
    ...mapActions(["setLocale"]),
    /**
     * Change the language.
     */
    translateI18n(locale) {
      setTimeout(() => {
        this.$i18n.locale = locale;
        this.$vuetify.lang.current = locale;

        if (this.isLoggedIn) {
          this.setLocale(locale);
        }
      }, 1000);
    }
  }
};
</script>

<template>
  <v-footer app fixed padless class="pl-4 caption font-weight-light">
    <v-menu offset-y right top max-height="calc(100% - 16px)" transition="slide-y-reverse-transition">
      <template v-slot:activator="{ attrs, on }">
        <v-btn icon small class="text--secondary text-capitalize mr-3"
          v-bind="attrs" v-on="on"
        >
          <v-img max-width="18px"
            :src="`./img/flags/${currentLanguage.country}-32.png`"
          />
        </v-btn>
      </template>

      <v-list dense nav>
        <v-list-item
          v-for="language in languages"
          :key="language.locale"
          @click="translateI18n(language.locale)"
        >
          <v-list-item-avatar tile size="20px">
            <v-img width="20px"
              :src="`./img/flags/${language.country}-r32.png`"
            />
          </v-list-item-avatar>
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
