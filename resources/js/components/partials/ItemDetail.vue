<script>

export default {
  /**
   * The component's name.
   */
  name: 'ItemDetail',

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

  computed: {
    itemLink() {
      return this.item.link ? `/${this.baseLink}/${this.item.link}` : undefined;
    },

    disabled() {
      return (typeof this.itemLink === 'undefined') ? true : false;
    }
  },
};
</script>

<template>
  <v-list-item
    :key="item.key"
    dense
    :disabled="disabled"
    :to="itemLink"
  >
    <v-list-item-content>
      <v-list-item-title v-text="$t(`${item.key}.title`)" />
      <v-list-item-subtitle v-text="$t(`${item.key}.description`)" />
    </v-list-item-content>
    <v-list-item-action
      class="body-2 font-weight-medium"
      v-text="value"
    />
  </v-list-item>
</template>
