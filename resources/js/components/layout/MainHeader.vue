<script>

import UserBadge from '../partials/UserBadge.vue';

export default {
  /**
   * The component's name.
   */
  name: 'MainHeader',

  components: {
    UserBadge,
  },
}
</script>

<template>
  <v-toolbar app fixed dark class="elevation-0" style="background-color: #247BA0;">
    <v-toolbar-title style="letter-spacing: 0em;">
      <b>Your Shares</b>
    </v-toolbar-title>
    <v-spacer></v-spacer>

    <user-badge />
  </v-toolbar>
</template>
