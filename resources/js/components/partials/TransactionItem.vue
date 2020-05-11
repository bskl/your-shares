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
      pageCount: 1,
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
    @page-count="pageCount = $event"
    :page="pageCount"
  >
    <template v-slot:item.date_at="{ item }">
      <v-row class="absolute">
        <v-col>
          {{ item.date_at }}
        </v-col>
      </v-row>
    </template>
    <template v-slot:item.type="{ item }">
      {{ $t(transactionTypes[item.type]) }}
    </template>
    <template v-slot:item.gain_loss="{ item }">
      <div class="text-right"
        :class="{
          'red--text': item.sale_gain_trend == -1,
          'green--text': item.sale_gain_trend == 1
        }"
        v-if="item.type == 0 || item.type == 1"
      >
        {{ item.sale_gain }}
      </div>
      <div class="d-flex align-center justify-end"
        v-if="item.type == 2"
      >
        <v-col cols="auto" class="pr-0 text-right">
          {{ item.dividend_gain }}
        </v-col>
        <v-col cols="auto" class="pr-0 text-right">
          ({{ item.dividend }})
        </v-col>
      </div>
      <div class="d-flex align-center justify-end"
        v-if="item.type == 3"
      >
        <v-col cols="auto" class="pr-0 text-right">
          {{ item.bonus }}
        </v-col>
        <v-col cols="auto" class="pr-0 text-right">
          ({{ item.sale_gain }})
        </v-col>
      </div>
      <div class="d-flex align-center justify-end"
        v-if="item.type == 4"
      >
        <v-col cols="auto" class="pr-0 text-right">
          {{ item.rights }}
        </v-col>
        <v-col cols="auto" class="pr-0 text-right">
          ({{ item.sale_gain }})
        </v-col>
      </div>
    </template>
  </v-data-table>
</template>
