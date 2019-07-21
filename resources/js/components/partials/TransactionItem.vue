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
  <v-data-table item-key="id"
    :items="items"
    :headers="[
      { text: $t('Transaction Date'), value: 'transaction_date', align: 'left', sortable: false },
      { text: $t('Transaction'), value: 'transaction', align: 'center', sortable: false },
      { text: $t('Lots'), value: 'lots', align: 'center', sortable: false },
      { text: $t('Transaction Price'), value: 'transaction_price', align: 'center', sortable: false },
      { text: $t('Transaction Amount'), value: 'transaction_amount', align: 'center', sortable: false },
      { text: $t('Commission Price'), value: 'commission_price', align: 'center', sortable: false },
      { text: $t('Gain/Loss'), value: 'gain_loss', align: 'center', sortable: false },
    ]"
    :no-data-text="$t('You have not any transaction.')"
    :rows-per-page-text="$t('Rows per page:')"
    :rows-per-page-items="[5, 10, 25, { text: $t('All'), value: -1 }]"
  >
    <template slot="items" slot-scope="props">
      <td class="text-xs-left">
        {{ $d(new Date(props.item.date_at), "short") }}
      </td>
      <td class="text-xs-right">
        {{ $t(transactionTypes[props.item.type]) }}
      </td>
      <td class="text-xs-right">
        {{ $n(props.item.lot, "decimal") }}
      </td>
      <td class="text-xs-right">
        {{ props.item.price }}
      </td>
      <td class="text-xs-right">
        {{ props.item.amount }}
      </td>
      <td class="text-xs-right">
        {{ props.item.commission_price }}
      </td>
      <td class="text-xs-right darken-1"
        :class="[props.item.sale_gain < 0 ? 'red--text' : 'green--text']"
        v-if="props.item.type == 0 || props.item.type == 1"
      >
        {{ props.item.sale_gain }}
      </td>
      <td class="text-xs-right green--text darken-1"
        v-if="props.item.type == 2"
      >
        {{ props.item.dividend_gain }}
      </td>
      <td class="text-xs-right green--text darken-1"
        v-if="props.item.type == 3"
      >
        {{ $n(props.item.bonus, "percent") }}
      </td>
    </template>
    <template slot="pageText" slot-scope="props">
      {{ $t("page_text", { itemsLength: props.itemsLength, pageStart: props.pageStart, pageStop: props.pageStop }) }}
    </template>
  </v-data-table>
</template>
