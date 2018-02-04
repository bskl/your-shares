<script type="text/ecmascript-6">
    import { ls } from '../../services/ls.js';
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
            return {
                locale: this.$i18n.locale,
                locales: [
                    { value: "tr", label: "Türkçe" },
                    { value: "en", label: "English" },
                ],
            }
        },

        mounted() {
            Bus.$on('userLoggedIn', event => {
                setTimeout(() => {
                    if (userStore.state.user.locale) {
                        this.$i18n.locale = userStore.state.user.locale;
                        this.locale = userStore.state.user.locale;
                        ls.set('locale', this.locale);
                    }
                }, 1000)
            });
        },

        methods: {
            /**
             * Change the language.
             */
            setLocale() {
                setTimeout(() => {
                    ls.set('locale', this.locale);
                    this.$i18n.locale = this.locale;

                    if (userStore.isAuthenticated()) {
                        return new Promise((resolve, reject) => {
                            http.get('/locale/' + this.locale, response => {
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
    <v-footer app class="pa-3">
        <v-flex xs1>
            <v-select
                :items="locales"
                item-text="label"
                item-value="value"
                v-model="locale"
                :label="$t('Language')"
                single-line
                auto
                hide-details
                @change="setLocale()"
            ></v-select>
        </v-flex>
        <v-spacer></v-spacer>
        <div>© {{ new Date().getFullYear() }}</div>
    </v-footer>
</template>
