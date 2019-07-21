<script>

import { mapActions, mapGetters } from 'vuex';
import TransactionItem from '../partials/TransactionItem.vue';
import ItemDetail from '../partials/ItemDetail.vue';
import DeleteTransactionModal from '../modals/DeleteTransactionModal.vue';
import { ITEM_DETAILS } from '../../store/constants.js';

export default {
  /**
   * The component's name.
   */
  name: 'Share',

  components: {
    TransactionItem, ItemDetail, DeleteTransactionModal,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      share: null,
      itemDetails: ITEM_DETAILS,
    }
  },

  computed: {
    ...mapGetters([
      'getPortfolioById',
    ]),

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
      'fetchTransactionsByParams', 'destroyShare', 'setSnackbar',
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

    showDeleteTransactionModal(id) {
      this.$refs.deleteTransactionModal.open(id);
    },
  },

  created() {
    this.isLoading = true;

    this.fetchTransactionsByParams(this.$route.path)
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
              <span class="px-2">{{ share.symbol.last_price }}</span>
              <span>{{ $n(share.symbol.rate_of_change, "percent") }}</span>
            </v-subheader>
            <v-subheader class="pl-1 mx-0">{{ share.symbol.session_time }}</v-subheader>
            <v-spacer></v-spacer>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn icon small class="mx-1"
                  v-if="share.transactions.length === 0"
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
          <transaction-item
            :items="share.transactions"
          />
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
