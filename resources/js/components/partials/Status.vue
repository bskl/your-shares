<script>

export default {
  props: {
    status: {
      type: String,
    },
    color: {
      type: String,
    },
  },

  /**
   * The component's name.
   */
  name: 'Status',
}
</script>

<template>
  <v-alert :color="color" icon="check_circle" :value="status">
    {{ status }}
  </v-alert>
</template>
