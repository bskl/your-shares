<script>

import { mapState, mapActions } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'Snackbar',

  computed: {
    ...mapState([
      'snackbar', 'showSnackbar'
    ]),

    snack: {
      get() {
        return this.showSnackbar;
      },
      set(val) {
        this.setShowSnackbar(val);
      },
    },
  },

  watch: {
    snackbar: function() {
      this.snack = true;
    },
  },

  methods: {
    ...mapActions([
      'setShowSnackbar'
    ])
  },
}
</script>

<template>
  <v-snackbar
    v-model="snack"
    :timeout="snackbar.timeout"
    :top="snackbar.position_y === 'top'"
    :bottom="snackbar.position_y === 'bottom'"
    :right="snackbar.position_x === 'right'"
    :left="snackbar.position_x === 'left'"
    :multi-line="snackbar.mode === 'multi-line'"
    :vertical="snackbar.mode === 'vertical'"
    :color="snackbar.color"
    elevation="24"
  >
    {{ snackbar.msg }}
    <v-spacer />
    <template #action="{ attrs }">
      <v-btn
        class="ml-4"
        icon
        :aria-label="$t('Close')"
        :ripple="false"
        v-bind="attrs"
        @click.native="snack = false"
      >
        <v-icon>cancel</v-icon>
      </v-btn>
    </template>
  </v-snackbar>
</template>
