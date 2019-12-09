<script>

import isEmpty from "lodash/isEmpty";

export default {
  props: {
    errors: {
      type: Object
    }
  },

  /**
   * The component's name.
   */
  name: "FormErrors",

  data() {
    return {
      any: null
    };
  },

  watch: {
    errors: function (newErrors, oldErrors) {
      this.any = !isEmpty(newErrors);
    }
  },
};
</script>

<template>
  <v-alert text dismissible type="error" border="left" :value="any">
    <dl v-for="errorItem in errors" :key="errorItem.id">
      <dd v-for="error in errorItem" :key="error.id">{{ error }}</dd>
    </dl>
  </v-alert>
</template>
