<script>

import { TRANSACTION_TYPES_MAP } from '../../store/constants.js';

export default {

  /**
   * The component's name.
   */
  name: 'TransactionItem',
   props: {
    items: {
      type: Array,
      required: true,
    },
    sessionTime: {
      type: String,
      required: true,
    },
  },

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
  <v-data-table
    item-key="id"
    disable-sort
    :mobile-breakpoint="0"
    :items="items"
    :hide-default-footer="!items.length"
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
    :page="pageCount"
    @page-count="pageCount = $event"
  >
    <template v-slot:[`item.type`]="{ item }">
      {{ $t(transactionTypes[item.type]) }}
    </template>
    <template v-slot:[`item.lot`]="{ item }">
      {{ $n(item.lot, 'decimal') }}
    </template>
    <template v-slot:[`item.gain_loss`]="{ item }">
      <div
        class="d-flex align-center justify-end"
        :class="{
          'red--text': item.sale_gain_trend == -1,
          'green--text': item.sale_gain_trend == 1
        }"
      >
        <template v-if="item.type == 0 || item.type == 1 || item.type == 7">
          {{ item.sale_gain }}
        </template>
        <template v-if="item.type == 2">
          <v-col
            cols="auto"
            class="pr-0 float-right"
          >
            {{ item.dividend_gain }}
          </v-col>
          <v-col
            cols="auto"
            class="pr-0 float-right"
          >
            ({{ item.dividend }})
          </v-col>
        </template>
        <template v-if="item.type == 3">
          <v-col
            cols="auto"
            class="pr-0 float-right"
          >
            {{ item.bonus }}
          </v-col>
          <v-col
            cols="auto"
            class="pr-0 float-right"
          >
            ({{ item.sale_gain }})
          </v-col>
        </template>
        <template v-if="item.type == 4">
          <v-col
            cols="auto"
            class="pr-0 float-right"
          >
            {{ item.rights }}
          </v-col>
          <v-col
            cols="auto"
            class="pr-0 float-right"
          >
            ({{ item.sale_gain }})
          </v-col>
        </template>
        <template v-if="item.type == 5">
          <v-col
            cols="auto"
            class="pr-0 float-right"
          >
            {{ item.symbol_code }} / {{ $n(item.exchange_ratio, { style: 'decimal', maximumFractionDigits: 5 }) }}
          </v-col>
        </template>
        <template v-if="item.type == 6">
          <v-col
            cols="auto"
            class="pr-0 float-right"
          >
            {{ item.symbol_code }} x {{ $n(item.exchange_ratio, { style: 'decimal', maximumFractionDigits: 3 }) }}
          </v-col>
          <v-col
            cols="auto"
            class="pr-0 float-right"
          >
            ({{ item.sale_gain }})
          </v-col>
        </template>
      </div>
    </template>
    <template
      v-if="items.length"
      #footer
    >
      <div
        class="pl-4 py-5 d-flex align-center text-caption"
        :style="!items.length ? 'border-top: thin solid hsla(0,0%,100%,.12);' : 'position: absolute;'"
      >
        <v-icon
          x-small
          dense
        >
          access_time
        </v-icon>
        <span class="mx-1 text-caption">SG: {{ sessionTime }}</span>
      </div>
    </template>
  </v-data-table>
</template>
