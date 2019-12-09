<script>

import MainHeader from './MainHeader.vue';
import MainNavigation from './MainNavigation.vue';
import MainFooter from './MainFooter.vue';

export default {
  /**
   * The component's name.
   */
  name: 'MainLayout',

  components: {
    MainHeader, MainNavigation, MainFooter
  },
}
</script>

<template>
  <v-app>
    <main-header />
    <main-navigation />
    <v-content>
      <v-responsive class="mx-auto overflow-visible">
        <v-container class="fill-height" fluid>
          <v-responsive
            class="overflow-visible"
            min-height="90vh"
          >
            <slot />
          </v-responsive>
        </v-container>
      </v-responsive>
    </v-content>
    <main-footer />
  </v-app>
</template>
