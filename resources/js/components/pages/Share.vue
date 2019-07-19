<script>

import { mapActions, mapGetters } from 'vuex';
import ItemDetail from '../partials/ItemDetail.vue';
import DeleteTransactionModal from '../modals/DeleteTransactionModal.vue';
import { ITEM_DETAILS, TRANSACTION_TYPES } from '../../store/constants.js';

export default {
  /**
   * The component's name.
   */
  name: 'Share',

  components: {
    ItemDetail, DeleteTransactionModal,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      share: null,
      itemDetails: ITEM_DETAILS,
      transactionTypes: TRANSACTION_TYPES,
    }
  },

  computed: {
    ...mapGetters([
      'getPortfolioById',
    ]),

    indexedTransactions() {
      return this.share.transactions.map((item, index) => ({
        index: index,
        ...item
      }))
    },

    count() {
      return this.share.transactions.length;
    },

    lastTransaction() {
      return this.share.transactions[this.count - 1];
    },

    portfolio() {
      return this.getPortfolioById(this.share.portfolio_id);
    }
  },

  methods: {
    ...mapActions([
      'fetchTransactionsByShare', 'destroyShare', 'setSnackbar',
    ]),

    deleteShare() {
      this.destroyShare({ 'id': this.share.id, 'portfolio_id': this.share.portfolio_id })
        .then((res) => {
          this.$router.push({ name: 'Home' });
        })
        .catch((error) => {
          this.setSnackbar({ color: 'error', text: error.response.data });
        });
    },

    /**
     * Open the modal for deleting transaction.
     */
    showDeleteTransactionModal(id) {
      this.$refs.deleteTransactionModal.open(id);
    },
  },

  created() {
    this.isLoading = true;

    this.fetchTransactionsByShare(this.$route.params.id)
      .then((res) => {
        this.share = res;
        this.isLoading = false;
      })
      .catch();
  },
}
</script>

<template>
  <v-layout row wrap v-if="!isLoading">
    <v-flex xs12 sm12 md10 offset-md1>
      <v-card>
        <v-card-title class="pt-0 pb-0 elevation-3 no-wrap">
          <v-toolbar color="white" flat>
            <v-btn icon light class="ml-0" to="/" exact>
              <v-icon color="grey darken-2">arrow_back</v-icon>
            </v-btn>
            <v-toolbar-title class="grey--text text--darken-4 ml-1">{{ share.symbol.code }}</v-toolbar-title>
            <v-subheader class="px-1"
              :class="{
                'red--text darken-1': share.symbol.trend == -1,
                'green--text darken-1': share.symbol.trend == 1
              }"
            >
              <i class="material-icons" v-if="share.symbol.trend == -1">trending_down</i>
              <i class="material-icons" v-else-if="share.symbol.trend == 0">trending_flat</i>
              <i class="material-icons" v-else>trending_up</i>
              <span class="px-2">{{ $n(share.symbol.last_price, "currency") }}</span>
              <span>{{ $n(share.symbol.rate_of_change, "percent") }}</span>
            </v-subheader>
            <v-subheader class="pl-1 mx-0">{{ share.symbol.session_time }}</v-subheader>
            <v-spacer></v-spacer>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn icon small class="mx-1"
                  v-if="share.total_purchase_amount == 0"
                  @click="deleteShare()"
                >
                  <v-icon color="red darken-2" v-on="on">delete</v-icon>
                </v-btn>
              </template>
              <span>{{ $t("delete_share", { portfolioName: portfolio.name }) }}</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn icon small class="mx-1"
                  v-if="count > 0 && lastTransaction.type == 0"
                  @click="showDeleteTransactionModal(lastTransaction.id)"
                >
                  <v-icon color="red darken-2" v-on="on">delete_sweep</v-icon>
                </v-btn>
              </template>
              <span>{{ $t("Delete last item of transactions.") }}</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn icon small class="mx-1"
                  :to="{ name: 'AddTransaction', params: { id: share.id, code: share.symbol.code, commission: portfolio.commission }}"
                >
                  <v-icon color="green darken-2" v-on="on">add</v-icon>
                </v-btn>
              </template>
              <span>{{ $t("Add Transaction") }}</span>
            </v-tooltip>
          </v-toolbar>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-data-table item-key="id"
            :items="indexedTransactions"
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
                {{ $n(props.item.price, "currency") }}
              </td>
              <td class="text-xs-right">
                {{ $n(props.item.amount, "currency") }}
              </td>
              <td class="text-xs-right">
                {{ $n(props.item.commission_price, "currency") }}
              </td>
              <td class="text-xs-right darken-1"
                :class="[props.item.sale_gain < 0 ? 'red--text' : 'green--text']"
                v-if="props.item.type == 0 || props.item.type == 1"
              >
                {{ $n(props.item.sale_gain, "currency") }}
              </td>
              <td class="text-xs-right green--text darken-1"
                v-if="props.item.type == 2"
              >
                {{ $n(props.item.dividend_gain, "currency") }}
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
        </v-card-text>
        <v-card-actions>
          <v-flex xs12>
            <v-list dense>
              <item-detail v-for="(item, index) in itemDetails" :key="index"
                :item="item"
                :value="share[item.key]"
                :baseLink="`share/${share.id}`"
              />
            </v-list>
          </v-flex>
        </v-card-actions>
      </v-card>
    </v-flex>
    <delete-transaction-modal ref="deleteTransactionModal" />
  </v-layout>
</template>
