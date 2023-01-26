<script>

import { mapActions } from 'vuex';
import validationHandler from '../../mixins/validationHandler.js';
import loadingHandler from '../../mixins/loadingHandler.js';

export default {
  /**
   * The component's name.
   */
  name: 'SearchSymbolField',

  mixins: [
    validationHandler,
    loadingHandler
  ],

  data() {
    return {
      symbols: [],
      searching: false,
      search: null,
      symbolId: 0,
    };
  },

  watch: {
    search (val) {
      if (val == null || this.symbols.length > 0 || this.isLoading) return

      this.searching = true;

      this.fetchSymbols()
        .then((res) => {
          this.symbols = res.data;
          this.searching = false;
        })
        .catch((error) => {
          this.syncErrors(error);
        });
    }
  },

  methods: {
    ...mapActions([
      'fetchSymbols',
    ]),
  },
};
</script>

<template>
  <v-autocomplete
    id="symbol_id"
    ref="symbol_id"
    v-model="symbolId"
    name="symbol_id"
    filled
    clearable
    prepend-inner-icon="layers"
    :items="symbols"
    :loading="searching"
    :search-input.sync="search"
    :rules="[rules.required]"
    :label="$t('Search Symbol')"
    :no-data-text="$t('No data available')"
    :error-messages="getError('symbol_id')"
    :disabled="isLoading"
    item-text="code"
    item-value="id"
    @input="$emit('update:symbolId', symbolId)"
  />
</template>
