<script>

export default {
  props: {
    errors: {
      type: Object,
    },
  },

  /**
   * The component's name.
   */
  name: 'FormErrors',
}
</script>

<template>
  <v-alert class="mb-4" color="error" dismissible :value="errors.any()">
    <dl class="mb-0">
      <template v-for="errorItem in errors.errors">
        <dd v-for="error in errorItem" :key="error.id">{{ error }}</dd>
      </template>
    </dl>
  </v-alert>
</template>
