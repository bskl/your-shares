<script>

import validationHandler from '../../mixins/validationHandler.js';

export default {
  /**
   * The component's name.
   */
  name: 'VCurrencyField',

  props: {
    name: {
      type: String,
      required: true,
    },
    value: {
      type: Number,
      default: null
    },
    label: {
      type: String,
      required: true,
    },
    options: {
      type: Object,
      default: {}
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
  },

  mixins: [
    validationHandler,
  ],

  data() {
    return {
      formattedValue: null,
    };
  },

  watch: {
    value(value) {
      this.setValue(value);
    }
  },

  mounted() {
    this.setValue(this.value);
  },

  methods: {
    setValue(value) {
      this.$ci.setValue(this.$refs[this.name], value);
    },

    onInput(value) {
      this.$emit('input', this.$ci.getValue(this.$refs[this.name]));
      this.formattedValue = value;
    },

    onChange(value) {
      this.$emit('change', this.$ci.getValue(this.$refs[this.name]));
      this.formattedValue = value;
    }
  },
}
</script>

<template>
  <v-text-field type="text" :name="name" :ref="name" :id="name" filled clearable
    prepend-inner-icon="money"
    v-currency="options"
    :value="formattedValue"
    :disabled="isLoading"
    :label="label"
    :rules="[rules.required]"
    :error-messages="getError(name)"
    @change="onChange"
    @input="onInput"
  ></v-text-field>
</template>
