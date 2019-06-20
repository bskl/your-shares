<script>

import { mapState } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'Snackbar',

  computed: {
    ...mapState([
      'snackbar',
    ]),
  },
}
</script>

<template>
  <v-card>
    <v-snackbar
      :timeout="snackbar.timeout"
      :top="snackbar.position_y === 'top'"
      :bottom="snackbar.position_y === 'bottom'"
      :right="snackbar.position_x === 'right'"
      :left="snackbar.position_x === 'left'"
      :multi-line="snackbar.mode === 'multi-line'"
      :vertical="snackbar.mode === 'vertical'"
      :color="snackbar.color"
      v-model="snackbar.show"
    >
      {{ snackbar.text }}
      <v-btn dark flat @click.native="snackbar.show = false">{{ $t("Close") }}</v-btn>
    </v-snackbar>
  </v-card>
</template>
