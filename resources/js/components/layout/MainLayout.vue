<script>

import MainHeader from './MainHeader.vue';
import MainFooter from './MainFooter.vue';

export default {
  /**
   * The component's name.
   */
  name: 'MainLayout',

  components: {
    MainHeader, MainFooter,
  },
}
</script>

<template>
  <v-app>
    <main-header />
    <v-main>
      <v-responsive class="mx-auto overflow-visible">
        <v-container
          class="fill-height"
          container--fluid
        >
          <v-responsive
            class="overflow-visible"
            min-height="90vh"
          >
            <slot />
          </v-responsive>
        </v-container>
      </v-responsive>
    </v-main>
    <main-footer />
  </v-app>
</template>
