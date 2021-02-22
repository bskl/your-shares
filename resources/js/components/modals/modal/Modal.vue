<script>

export default {
  props: {
    dialog: {
      type: Boolean,
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
}
</script>

<template>
  <v-dialog v-model="dialog" persistent :max-width="dialogWidth">
    <v-card>
      <slot />
    </v-card>
  </v-dialog>
</template>
