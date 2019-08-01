<script>

export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
    value: {
      type: [String, Number],
      required: false,
      default: 0,
    },
    baseLink: {
      type: String,
      required: false,
      default: null,
    },
  },

  /**
   * The component's name.
   */
  name: 'ItemDetail',

  computed: {
    classObject() {
      return (this.item.change_color) ? (this.value < 0) ? 'red--text' : 'green--text' : '';
    },

    itemLink() {
      return this.item.link ? `/${this.baseLink}/${this.item.link}` : undefined;
    }
  },
};
</script>

<template>
  <v-list-tile
    :to="itemLink"
  >
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
      <strong>{{ value }}</strong>
    </v-list-tile-action>
  </v-list-tile>
</template>
