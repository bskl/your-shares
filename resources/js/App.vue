<script type="text/ecmascript-6">

import MainLayout from './components/layout/MainLayout.vue';
import TransitionPage from './components/partials/TransitionPage.vue';
import Snackbar from './components/partials/Snackbar.vue';

export default {
    /*
     * The component's name.
     */
    name: 'App',

    components: {
        MainLayout, TransitionPage, Snackbar,
    },
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
