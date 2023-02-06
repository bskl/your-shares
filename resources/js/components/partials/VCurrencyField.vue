<script>

import { useCurrencyInput } from 'vue-currency-input';
import validationHandler from '../../mixins/validationHandler.js';

export default {
  /**
   * The component's name.
   */
  name: 'VCurrencyField',

  mixins: [
    validationHandler,
  ],

  props: {
    name: {
      type: String,
      required: true,
    },
    value: {
      type: Number,
      default: 0
    },
    label: {
      type: String,
      required: true,
    },
    options: {
      type: Object,
      default() {
        return {
          currency: 'TRY',
          currencyDisplay: 'narrowSymbol',
          precision: 2,
          locale: 'tr-TR',
          hideCurrencySymbolOnFocus: false,
          hideGroupingSeparatorOnFocus: false,
          hideNegligibleDecimalDigitsOnFocus: false,
          autoDecimalDigits: true,
          useGrouping: true,
          accountingSign: false
        }
      }
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
  },

  setup (props) {
    const { inputRef } = useCurrencyInput(props.options)

    return { inputRef }
  }
}
</script>

<template>
  <v-text-field
    :id="name"
    ref="inputRef"
    :name="name"
    filled
    clearable
    prepend-inner-icon="money"
    :disabled="isLoading"
    :label="label"
    :rules="[rules.required]"
    :error-messages="getError(name)"
  />
</template>
