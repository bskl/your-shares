<script>

import { mapActions, mapGetters } from 'vuex';
import { ITEM_DETAILS } from '../../store/constants.js';
import { parseErrorMessage, parseSuccessMessage } from '../../utilities/helpers.js';
import last from 'lodash/last';
import loadingHandler from '../../mixins/loadingHandler.js';
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

  mixins: [
    loadingHandler,
  ],

  props: {
    initialShare: {
      type: Object,
      required: true,
    },
  },

  /**
   * The component's data.
   */
  data() {
    return {
      waitFor: 'fetch_share_transactions',
      share: this.initialShare,
      itemDetails: ITEM_DETAILS,
    }
  },

  computed: {
    ...mapGetters([
      'getPortfolioById',
    ]),

    createTransactionParams() {
      return {
        shareId: this.share.id,
        code: this.share.symbol.code,
        commission: this.getPortfolioById(this.share.portfolio_id).commission
      }
    }
  },

  methods: {
    ...mapActions([
      'destroyShare',
    ]),

    count() {
      return this.share.transactions.length;
    },

    lastTransaction() {
      return last(this.share.transactions);
    },

    getTextColor(value) {
      return (value == -1) ? 'red lighten-1' : (value == 1) ? 'green lighten-1' : '';
    },

    deleteShare() {
      this.destroyShare({ 'id': this.share.id, 'portfolio_id': this.share.portfolio_id })
        .then((res) => {
          parseSuccessMessage(this.$t('Share has been successfully deleted from your portfolio.'));
          this.$router.push({ name: 'Home' });
        })
        .catch((error) => {
          parseErrorMessage(error);
        });
    },
  },
}
</script>

<template>
  <v-row
    v-if="!isLoading"
    align="center"
    justify="center"
  >
    <delete-transaction-modal ref="deleteTransactionModal" />
    <v-col
      cols="12"
      sm="8"
      md="4"
      lg="10"
    >
      <v-card>
        <v-toolbar
          flat
          class="pl-2"
        >
          <v-btn
            icon
            to="/"
            exact
          >
            <v-icon color="grey darken-2">
              arrow_back
            </v-icon>
          </v-btn>
          <v-toolbar-title class="pl-2">
            {{ share.symbol.code }}
          </v-toolbar-title>
          <v-subheader>
            <span class="pr-3 text-caption">{{ share.symbol.last_price }}</span>
            <v-chip
              label
              small
              class="font-weight-thin"
              :color="getTextColor(share.symbol.trend)"
            >
              {{ share.symbol.rate_of_change }}
            </v-chip>
          </v-subheader>
          <v-spacer />
          <v-tooltip
            v-if="!count()"
            bottom
          >
            <template #activator="{ on }">
              <v-btn
                icon
                small
                class="mx-1"
                @click="deleteShare()"
              >
                <v-icon
                  color="red darken-2"
                  v-on="on"
                >
                  delete
                </v-icon>
              </v-btn>
            </template>
            <span>{{ $t("Delete share from the portfolio") }}</span>
          </v-tooltip>
          <v-tooltip bottom>
            <template #activator="{ on }">
              <v-btn
                v-if="count() && (lastTransaction().type != 5 && lastTransaction().type != 6)"
                icon
                small
                class="mx-1"
                @click="$refs.deleteTransactionModal.open(lastTransaction().id)"
              >
                <v-icon
                  small
                  color="red darken-2"
                  v-on="on"
                >
                  backspace
                </v-icon>
              </v-btn>
            </template>
            <span>{{ $t("Delete last item of transactions.") }}</span>
          </v-tooltip>
          <v-tooltip bottom>
            <template #activator="{ on }">
              <v-btn
                icon
                small
                class="mx-1"
                :to="{ name: 'CreateTransaction', params: createTransactionParams }"
              >
                <v-icon
                  color="green darken-2"
                  v-on="on"
                >
                  add
                </v-icon>
              </v-btn>
            </template>
            <span>{{ $t("Add Transaction") }}</span>
          </v-tooltip>
        </v-toolbar>
        <v-divider />
        <v-card-text>
          <transaction-item
            :items="share.transactions"
            :session-time="share.symbol.session_time"
          />
        </v-card-text>
        <v-card-actions style="background-color: #323639;">
          <v-flex class="px-2">
            <v-list
              dense
              color="#323639"
            >
              <template v-for="(item, index) in itemDetails">
                <item-detail
                  :key="item.key"
                  :item="item"
                  :value="share[item.key]"
                  :base-link="`shares/${share.id}`"
                />
                <v-divider
                  v-if="index + 1 < itemDetails.length"
                  :key="index"
                />
              </template>
            </v-list>
          </v-flex>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>
