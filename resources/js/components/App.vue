<script type="text/ecmascript-6">

import MainLayout from './layout/MainLayout.vue';
import TransitionPage from './partials/TransitionPage.vue';
import Snackbar from './partials/Snackbar.vue';

export default {
    /*
     * The component's name.
     */
    name: 'App',

    components: {
        MainLayout, TransitionPage, Snackbar,
    },

    mounted() {
        document.addEventListener("keydown", (e) => {
            if (((e.ctrlKey || e.keyCode == 91) && e.keyCode == 82) || e.keyCode == 116) {
                this.$store.dispatch('fetchData');
            }
        })
    }
}
</script>

<template>
  <main-layout>
    <snackbar></snackbar>
    <transition-page>
        <router-view></router-view>
    </transition-page>
  </main-layout>
</template>
