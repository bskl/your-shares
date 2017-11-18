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
                lang: ls.get('language'),
                languages: [
                    { value: "tr", label: "Türkçe" },
                    { value: "en", label: "English" },
                ],
            }
        },

        mounted() {
            if (userStore.isAuthenticated()) {
                this.lang = userStore.state.user.locale;
            }
        },

        methods: {
            /**
             * Change the language.
             */
            setLanguage() {
                setTimeout(() => {
                    this.$i18n.locale = this.lang;
                    ls.set('language', this.lang);

                    if (userStore.isAuthenticated()) {
                        return new Promise((resolve, reject) => {
                            http.get('/locale/' + this.lang, response => {
                                resolve(response);
                            }, error => {
                                reject(error.response.data);
                            });
                        });
                    }
                }, 500)
            },
        }
    }
</script>

<template>
    <v-footer app class="pa-3">
        <v-flex xs1>
            <v-select class="pt-0"
                :items="languages"
                item-text="label"
                item-value="value"
                v-model="lang"
                :label="$t('Language')"
                single-line
                auto
                hide-details
                @change="setLanguage()"
            ></v-select>
        </v-flex>
        <v-spacer></v-spacer>
        <div>© {{ new Date().getFullYear() }}</div>
    </v-footer>
</template>
