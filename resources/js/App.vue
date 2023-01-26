<script>

import MainLayout from './components/layout/MainLayout.vue';
import Snackbar from './components/partials/Snackbar.vue';

export default {
  /**
   * The component's name.
   */
  name: 'App',

  components: {
    MainLayout, Snackbar,
  },

  watch: {
    '$route.path' () {
      typeof window !== 'undefined' && window.scrollTo(0, 0)
    },
  },
}
</script>

<template>
  <main-layout>
    <snackbar />
    <v-scroll-x-transition mode="out-in">
      <router-view :key="$route.fullPath" />
    </v-scroll-x-transition>
  </main-layout>
</template>
