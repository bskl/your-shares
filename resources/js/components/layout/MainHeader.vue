<script>

import { mapGetters, mapActions } from "vuex";

export default {
  /**
   * The component's name.
   */
  name: "MainHeader",

  computed: {
    ...mapGetters([
      'isLoggedIn',
    ])
  },

  methods: {
    ...mapActions([
      'toggleNavDrawer',
    ]),
  }
};
</script>

<template>
  <v-app-bar app clipped-right elevate-on-scroll class="seperator-line">
    <v-toolbar-title>Your Shares</v-toolbar-title>
    <v-spacer />
    <v-app-bar-nav-icon 
      @click.stop="toggleNavDrawer()" 
      v-if="isLoggedIn" 
    />
    <v-btn class="btn-custom" to="/register" v-else>{{ $t("Register") }}</v-btn>
  </v-app-bar>
</template>
