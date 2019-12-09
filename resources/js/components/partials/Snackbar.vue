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
  <v-snackbar
    v-model="snackbar.show"
    :timeout="snackbar.timeout"
    :top="snackbar.position_y === 'top'"
    :bottom="snackbar.position_y === 'bottom'"
    :right="snackbar.position_x === 'right'"
    :left="snackbar.position_x === 'left'"
    :multi-line="snackbar.mode === 'multi-line'"
    :vertical="snackbar.mode === 'vertical'"
    :color="snackbar.color"
  >
    {{ snackbar.text }}
    <v-btn class="btn-custom" @click.native="snackbar.show = false">{{ $t("Close") }}</v-btn>
  </v-snackbar>
</template>
