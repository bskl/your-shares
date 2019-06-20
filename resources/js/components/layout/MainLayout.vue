<script>

import MainHeader from './MainHeader.vue';

export default {
  /**
   * The component's name.
   */
  name: 'MainLayout',

  components: {
    MainHeader,
  },
}
</script>

<template>
  <v-app light>
    <main-header />

    <main>
      <v-content>
        <v-container fluid grid-list-md>
          <slot />
        </v-container>
      </v-content>
    </main>
  </v-app>
</template>
