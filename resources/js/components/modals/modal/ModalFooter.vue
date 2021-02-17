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
  <v-card-actions class="pb-4 pr-4">
    <v-spacer></v-spacer>
    <v-progress-circular v-show="isLoading" indeterminate />
    <slot />
  </v-card-actions>
</template>
