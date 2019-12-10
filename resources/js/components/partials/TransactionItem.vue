<script>

import { TRANSACTION_TYPES } from '../../store/constants.js';

export default {
   props: {
    items: {
      type: Array,
      required: true,
    },
  },

  /**
   * The component's name.
   */
  name: 'TransactionItem',

  /**
   * The component's data.
   */
  data() {
    return {
      transactionTypes: TRANSACTION_TYPES,
    }
  },
};
</script>

<template>
  <v-data-table item-key="id" disable-sort
    :mobile-breakpoint="0"
    :items="items"
    :headers="[
      { text: $t('Transaction Date'), value: 'date_at', align: 'left' },
      { text: $t('Transaction'), value: 'type', align: 'center' },
      { text: $t('Lots'), value: 'lot', align: 'center' },
      { text: $t('Transaction Price'), value: 'price', align: 'center' },
      { text: $t('Transaction Amount'), value: 'amount', align: 'center' },
      { text: $t('Commission Price'), value: 'commission_price', align: 'center' },
      { text: $t('Gain/Loss'), value: 'gain_loss', align: 'center' },
    ]"
    :no-data-text="$t('You have not any transaction.')"
  >
    <template v-slot:item.date_at="{ item }">
      <v-row class="absolute">
        {{ item.date_at }}
      </v-row>
    </template>
    <template v-slot:item.type="{ item }">
      {{ $t(transactionTypes[item.type]) }}
    </template>
    <template v-slot:item.gain_loss="{ item }">
      <div class="text--darken-1 text-right"
        :class="{
          'red--text': item.sale_gain_trend == -1,
          'green--text': item.sale_gain_trend == 1
        }"
        v-if="item.type == 0 || item.type == 1"
      >
        {{ item.sale_gain }}
      </div>
      <div class="text--darken-1 text-right"
        v-if="item.type == 2"
      >
        {{ item.dividend_gain }}
      </div>
      <div class="text--darken-1 text-right"
        v-if="item.type == 3"
      >
        {{ item.bonus }}
      </div>
      <div class="text--darken-1 text-right"
        v-if="item.type == 4"
      >
        {{ item.rights }}
      </div>
    </template>
  </v-data-table>
</template>
