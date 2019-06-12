<script type="text/ecmascript-6">

import { mapActions, mapMutations } from 'vuex';
import ItemDetail from '../partials/ItemDetail.vue';
import { ITEM_DETAIL_MAP } from '../../store/constants.js';

export default {
  /*
    * The component's name.
    */
  name: 'Share',

  components: {
    ItemDetail,
  },

  /*
    * The component's data.
    */
  data() {
    return {
      isLoading: false,
      share: null,
    }
  },

  computed: {
    indexedTransactions () {
      return this.share.transactions.map((item, index) => ({
        index: index,
        ...item
      }))
    },

    count() {
      return this.share.transactions.length;
    },

    itemDetails() {
      return ITEM_DETAIL_MAP;
    }
  },

  methods: {
    ...mapActions([
      'fetchTransactionsByShare', 'destroyTransaction',
    ]),

    ...mapMutations([
      'SET_SNACKBAR',
    ]),

    deleteTransaction(id) {
      this.destroyTransaction(id)
        .then((res) => {
          this.$router.push({ name: 'Home' });
        })
        .catch((error) => {
          if (error.response.status == 404) {
            this.$router.push({ name: 'NotFound' });
          } else {
            this.SET_SNACKBAR({ color: 'error', text: error.response.data });
          }
        });
    }
  },

  created() {
    this.isLoading = true;

    this.fetchTransactionsByShare(this.$route.params.shareId)
        .then((res) => {
          this.share = res;
          this.isLoading = false;
        })
        .catch((error) => {
          if (error.response.status == 404) {
            this.$router.push({ name: 'NotFound' });
          }
        });
  },
}
</script>

<template>
  <v-layout row wrap v-if="!isLoading">
    <v-flex xs12 sm12 md10 offset-md1>
      <v-layout row wrap>
        <v-flex xs12 pb-4>
          <v-card>
            <v-card-title class="pt-0 pb-0 elevation-3 no-wrap">
              <v-toolbar color="white" flat>
                <v-btn icon light class="ml-0" to="/" exact>
                  <v-icon color="grey darken-2">arrow_back</v-icon>
                </v-btn>
                <v-toolbar-title class="grey--text text--darken-4 ml-1">
                    {{ share.symbol.code }}
                </v-toolbar-title>
                <v-subheader
                  class="px-1"
                  :class="{
                    'red--text darken-1': share.symbol.trend == -1,
                    'green--text darken-1': share.symbol.trend == 1
                  }"
                >
                  <i class="material-icons" v-show="share.symbol.trend == -1"
                    >trending_down</i
                  >
                  <i class="material-icons" v-show="share.symbol.trend == 0"
                    >trending_flat</i
                  >
                  <i class="material-icons" v-show="share.symbol.trend == 1"
                    >trending_up</i
                  >
                  <span class="px-2">{{
                    $n(share.symbol.last_price, "currency")
                  }}</span>
                  <span>{{ $n(share.symbol.rate_of_change, "percent") }}</span>
                </v-subheader>
                <v-subheader class="pl-1 mx-0">
                  {{ share.symbol.session_time }}
                </v-subheader>
              </v-toolbar>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
              <v-data-table
                :items="indexedTransactions"
                :headers="[
                  {
                    text: $t('Transaction Date'),
                    value: 'transaction_date',
                    align: 'left',
                    sortable: false
                  },
                  {
                    text: $t('Transaction'),
                    value: 'transaction',
                    align: 'center',
                    sortable: false
                  },
                  {
                    text: $t('Lots'),
                    value: 'lots',
                    align: 'center',
                    sortable: false
                  },
                  {
                    text: $t('Transaction Price'),
                    value: 'transaction_price',
                    align: 'center',
                    sortable: false
                  },
                  {
                    text: $t('Transaction Amount'),
                    value: 'transaction_amount',
                    align: 'center',
                    sortable: false
                  },
                  {
                    text: $t('Commission Price'),
                    value: 'commission_price',
                    align: 'center',
                    sortable: false
                  },
                  {
                    text: $t('Gain/Loss'),
                    value: 'gain_loss',
                    align: 'center',
                    sortable: false
                  },
                  {
                    text: $t('Actions'),
                    value: 'actions',
                    align: 'center',
                    sortable: false
                  }
                ]"
                item-key="id"
                :no-data-text="$t('You have not any transaction.')"
                :rows-per-page-text="$t('Rows per page:')"
                :rows-per-page-items="[
                  5,
                  10,
                  25,
                  { text: $t('All'), value: -1 }
                ]"
              >
                <template slot="items" slot-scope="props">
                  <td class="text-xs-left">
                    {{ $d(new Date(props.item.date_at), "short") }}
                  </td>
                  <td class="text-xs-right">
                    {{ $t(`transactions[${props.item.type}]`) }}
                  </td>
                  <td class="text-xs-right">
                    {{ $n(props.item.lot, "decimal") }}
                  </td>
                  <td class="text-xs-right">
                    {{ $n(props.item.price, "currency") }}
                  </td>
                  <td class="text-xs-right">
                    {{ $n(props.item.amount, "currency") }}
                  </td>
                  <td class="text-xs-right">
                    {{ $n(props.item.commission_price, "currency") }}
                  </td>
                  <td
                    class="text-xs-right"
                    :class="{
                      'red--text darken-1': props.item.sale_gain < 0,
                      'green--text darken-1': props.item.sale_gain > 0
                    }"
                    v-if="props.item.type == 0 || props.item.type == 1"
                  >
                    {{ $n(props.item.sale_gain, "currency") }}
                  </td>
                  <td
                    class="text-xs-right green--text darken-1"
                    v-if="props.item.type == 2"
                  >
                    {{ $n(props.item.dividend_gain, "currency") }}
                  </td>
                  <td
                    class="text-xs-right green--text darken-1"
                    v-if="props.item.type == 3"
                  >
                    {{ $n(props.item.bonus, "percent") }}
                  </td>
                  <td class="justify-center layout px-0">
                    <v-btn
                      v-if="props.item.index == count - 1 && props.item.type == 0"
                      icon
                      small
                      class="mx-1"
                      @click="deleteTransaction(props.item.id)"
                    >
                      <v-icon color="red darken-2">delete</v-icon>
                    </v-btn>
                  </td>
                </template>
                <template slot="pageText" slot-scope="props">
                  {{
                    $t("page_text", {
                      itemsLength: props.itemsLength,
                      pageStart: props.pageStart,
                      pageStop: props.pageStop
                    })
                  }}
                </template>
              </v-data-table>
            </v-card-text>
            <v-card-actions>
              <v-flex xs12>
                <v-list dense>
                  <item-detail 
                    v-for="(item, index) in itemDetails" :key="index"
                    :item="item"
                    :value="share[item.key]"
                  />
                </v-list>
              </v-flex>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>
