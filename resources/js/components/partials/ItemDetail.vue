<script>

export default {
  props: {
    item: {
      type: [Object],
      required: true,
    },
    value: {
      type: [String, Number],
      required: false,
      default: 0,
    },
  },

  /**
   * The component's name.
   */
  name: 'ItemDetail',

  computed: {
    classObject() {
      return (this.item.change_color) ? (this.value < 0) ? 'red--text' : 'green--text' : '';
    }
  },
};
</script>

<template>
  <v-list-tile>
    <v-list-tile-content>
      <v-list-tile-title>
        {{ $t(`${item.key}.title`) }}
        <span class="grey--text text--lighten-1">
          - <i>{{ $t(`${item.key}.description`) }}</i>
        </span>
      </v-list-tile-title>
    </v-list-tile-content>
    <v-list-tile-action class="darken-1"
      :class="classObject"
    >
      <strong>{{ $n(value, item.type) }}</strong>
    </v-list-tile-action>
  </v-list-tile>
</template>
