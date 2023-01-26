<script>

export default {
  /**
   * The component's name.
   */
  name: 'Modal',

  props: {
    dialog: {
      type: Boolean,
      default: false,
      required: true,
    },
    width: {
      type: [String, Number],
      required: true,
    },
  },

  /**
   * The component's data.
   */
  data() {
    return {
      dialogWidth: `${this.width}px`,
    }
  },

  computed: {
    dialogState() {
      return this.dialog;
    }
  }
}
</script>

<template>
  <v-dialog
    v-model="dialogState"
    persistent
    :max-width="dialogWidth"
  >
    <v-card>
      <slot />
    </v-card>
  </v-dialog>
</template>
