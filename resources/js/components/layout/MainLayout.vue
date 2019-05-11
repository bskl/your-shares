<script type="text/ecmascript-6">
import MainHeader from './MainHeader.vue';

export default {
    props: ['loading'],
    /*
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
        <v-container fluid grid-list-md v-if="!loading">
          <slot />
        </v-container>
      </v-content>
    </main>
  </v-app>
</template>
