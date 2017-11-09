<script type="text/ecmascript-6">
    import { ls } from '../../services/ls.js';

    export default {
        /*
         * The component's name.
         */
        name: 'MainFooter',

        /*
         * The component's data.
         */
        data() {
            return {}
        },

        methods: {
            /**
             * Change the language.
             */
            setLanguage(lang) {
                this.$i18n.locale = lang;
                ls.set('language', lang);
            },
        }
    }
</script>

<template>
    <v-footer app class="pa-3">
        <v-btn flat small @click="setLanguage('tr')">Türkçe</v-btn>
        <v-btn flat small @click="setLanguage('en')">English</v-btn>
        <v-spacer></v-spacer>
        <div>© {{ new Date().getFullYear() }}</div>
    </v-footer>
</template>
