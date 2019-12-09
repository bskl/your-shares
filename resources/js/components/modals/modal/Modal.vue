<script>

export default {
  props: ['dialog', 'width'],

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
