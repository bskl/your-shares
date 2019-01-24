<script type="text/ecmascript-6">
import { ls } from '../../services/ls.js';
import { languages as list } from '../../lang/map';
import { userStore } from '../../stores/userStore.js';

export default {
    /*
     * The component's name.
     */
    name: 'MainFooter',

    /*
     * The component's data.
     */
    data() {
        return { }
    },

    computed: {
        languages() {
            return list;
        },

        currentLanguage() {
            return this.languages.find(l => l.locale === this.$i18n.locale);
        },
    },

    methods: {
        /**
         * Change the language.
         */
        setLocale(locale) {
            setTimeout(() => {
                ls.set('locale', locale);
                this.$i18n.locale = locale;

                if (userStore.isAuthenticated()) {
                    return new Promise((resolve, reject) => {
                        http.get('/locale/' + locale, response => {
                            resolve(response);
                        }, error => {
                            reject(error);
                        });
                    });
                }
            }, 500);
        },
    }
}
</script>

<template>
  <v-footer app height="auto" class="pr-3">
    <v-layout row wrap>
      <v-menu open-on-hover top offset-y>
        <v-btn flat slot="activator" style="min-width: 64px">
          <img
            :src="`./img/flags/${currentLanguage.country}-32.png`"
            width="32px"
          />
        </v-btn>
        <v-list dense light>
          <v-list-tile
            avatar
            v-for="language in languages"
            :key="language.locale"
            @click="setLocale(language.locale)"
          >
            <v-list-tile-avatar class="avatar--tile" size="24px">
              <img
                :src="`./img/flags/${language.country}-r32.png`"
                width="24px"
              />
            </v-list-tile-avatar>
            <v-list-tile-title v-text="language.title"></v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-menu>
    </v-layout>

    <v-spacer></v-spacer>
    <div>&copy; {{ new Date().getFullYear() }} — yourshares</div>
  </v-footer>
</template>
