<script>

import { mapActions, mapGetters } from 'vuex';
import { ITEM_DETAILS } from '../../store/constants.js';
import { parseErrors } from '../../utilities/helpers.js';
import TransactionItem from '../partials/TransactionItem.vue';
import ItemDetail from '../partials/ItemDetail.vue';
import DeleteTransactionModal from '../modals/DeleteTransactionModal.vue';

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
          this.setSnackbar({ color: 'error', text: parseErrors(error) });
        });
    },
  },

  created() {
    this.isLoading = true;

    this.fetchTransactionsByParams(this.$route.path)
      .then((res) => {
        this.share = res.data;
        this.isLoading = false;
      })
      .catch();
  },
}
</script>

<template>
  <v-row align="center" justify="center" v-if="!isLoading">
    <delete-transaction-modal ref="deleteTransactionModal" />
    <v-col cols="12" sm="8" md="4" lg="10">
      <v-card>
        <v-toolbar flat class="pl-2">
          <v-btn icon to="/" exact>
            <v-icon color="grey darken-2">arrow_back</v-icon>
          </v-btn>
          <v-toolbar-title class="pl-2">{{ share.symbol.code }}</v-toolbar-title>
          <v-subheader>
            <span class="pr-3 font-weight-thin">{{ share.symbol.last_price }}</span>
            <v-chip label small class="font-weight-thin"
              :color="
                (share.symbol.trend == -1) ? 'red lighten-1' :
                (share.symbol.trend == 1) ? 'green lighten-1' : ''"
            >
              {{ share.symbol.rate_of_change }}
            </v-chip>
          </v-subheader>
          <v-spacer></v-spacer>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn icon small class="mx-1"
                v-if="!count"
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
                v-if="count"
                @click="$refs.deleteTransactionModal.open(lastTransaction.id)"
              >
                <v-icon small color="red darken-2" v-on="on">backspace</v-icon>
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
        <v-divider></v-divider>
        <v-card-text>
          <transaction-item
            :items="share.transactions"
          />
          <div class="ma-4">
            <v-icon x-small dense>access_time</v-icon>
            <span class="mx-1 caption font-weight-thin">SG: {{ share.symbol.session_time }}</span>
          </div>
        </v-card-text>
        <v-card-actions style="background-color: #323639;">
          <v-flex class="px-2">
            <v-list dense color="#323639">
              <template v-for="(item, index) in itemDetails">
                <item-detail :key="item.key"
                  :item="item"
                  :value="share[item.key]"
                  :baseLink="`shares/${share.id}`"
                />
                <v-divider
                  v-if="index + 1 < itemDetails.length"
                  :key="index"
                ></v-divider>
              </template>
            </v-list>
          </v-flex>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>
