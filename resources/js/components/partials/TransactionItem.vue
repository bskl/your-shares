<script>

import { TRANSACTION_TYPES_MAP } from '../../store/constants.js';

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
      transactionTypes: TRANSACTION_TYPES_MAP,
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
      { text: $t('Transaction Date'), value: 'date_at', align: 'start' },
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
    <template v-slot:item.lot="{ item }">
      {{ $n(item.lot, 'decimal') }}
    </template>
    <template v-slot:item.gain_loss="{ item }">
      <div class="d-flex align-center justify-end"
        :class="{
          'red--text': item.sale_gain_trend == -1,
          'green--text': item.sale_gain_trend == 1
        }"
      >
        <template v-if="item.type == 0 || item.type == 1">
          {{ item.sale_gain }}
        </template>
        <template v-if="item.type == 2">
          <v-col cols="auto" class="pr-0 float-right">
            {{ item.dividend_gain }}
          </v-col>
          <v-col cols="auto" class="pr-0 float-right">
            ({{ item.dividend }})
          </v-col>
        </template>
        <template v-if="item.type == 3">
          <v-col cols="auto" class="pr-0 float-right">
            {{ item.bonus }}
          </v-col>
          <v-col cols="auto" class="pr-0 float-right">
            ({{ item.sale_gain }})
          </v-col>
        </template>
        <template v-if="item.type == 4">
          <v-col cols="auto" class="pr-0 float-right">
            {{ item.rights }}
          </v-col>
          <v-col cols="auto" class="pr-0 float-right">
            ({{ item.sale_gain }})
          </v-col>
        </template>
        <template v-if="item.type == 5 || item.type == 6">
          <v-col cols="auto" class="pr-0 float-right">
            {{ $n(item.exchange_ratio, { style: 'decimal', maximumFractionDigits: 5 }) }}
          </v-col>
          <v-col cols="auto" class="pr-0 float-right">
            ({{ item.symbol_code }})
          </v-col>
        </template>
      </div>
    </template>
  </v-data-table>
</template>
