<script>

import { watch } from 'vue';
import { useCurrencyInput } from 'vue-currency-input';
import { watchDebounced } from '@vueuse/core';
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
      default: null
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
          locale: 'tr-TR',
          hideCurrencySymbolOnFocus: false,
          hideGroupingSeparatorOnFocus: false,
          hideNegligibleDecimalDigitsOnFocus: false,
          autoDecimalDigits: true,
        }
      }
    },
    isLoading: {
      type: Boolean,
      default: false,
    },
  },

  setup (props, { emit }) {
    const { inputRef, numberValue, setValue, setOptions } = useCurrencyInput(props.options, false)

    watchDebounced(numberValue, (value) => emit('input', value), { debounce: 1000 })

    watch(
      () => props.modelValue, // Vue 2: props.value
      (value) => {
        setValue(value)
      }
    )

    watch(
      () => props.options,
      (options) => {
        setOptions(options)
      }
    )

    return { inputRef }
  }
}
</script>

<template>
  <v-text-field
    :id="name"
    ref="inputRef"
    type="text"
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
