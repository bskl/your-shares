<script>

export default {
  props: {
    isLoading: {
      type: Boolean,
      required: true,
    }
  }
}
</script>

<template>
  <v-card-actions class="pb-6 pr-6">
    <v-spacer></v-spacer>
    <v-progress-circular v-show="isLoading" indeterminate />
    <slot />
  </v-card-actions>
</template>
