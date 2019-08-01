<script type="text/ecmascript-6">

import { mapState, mapActions, mapGetters } from 'vuex';
import AddShareModal from '../modals/AddShareModal.vue';
import ItemDetail from '../partials/ItemDetail.vue';
import { ITEM_DETAILS } from '../../store/constants.js';

export default {
  /**
   * The component's name.
   */
  name: 'Home',

  components: {
    AddShareModal, ItemDetail,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      loading: false,
      itemDetails: ITEM_DETAILS,
    }
  },

  computed: {
    ...mapState([
      'portfolios',
    ]),

    ...mapGetters([
      'portfoliosCount', 'getPortfolioByIndex', 'isAdmin',
    ]),
  },

  methods: {
    ...mapActions([
      'fetchData', 'destroyShare', 'fetchSymbolsData', 'setSnackbar',
    ]),

    trendClass(trend) {
      return {
        'red--text': trend == -1,
        'green--text': trend == 1
      }
    },

    getSymbolsData() {
      this.loading = true;

      this.fetchSymbolsData()
        .then()
        .catch((error) => {
          this.setSnackbar({ color: 'error', text: error.response.data });
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },

  created() {
    this.isLoading = true;

    this.fetchData()
      .finally(() => {
        this.isLoading = false;
      });

    for (let index = 0, count = this.portfoliosCount; index <  count; ++index) {
      let portfolio = this.getPortfolioByIndex(index);
      if (portfolio.commission == null) {
        this.SET_SNACKBAR({ color: 'error', text: this.$t('Your portfolio commission rate has not been recorded.') });
        this.$router.push({ name: 'EditPortfolio', params: { id: portfolio.id } });
        break;
      }
    }
  },
}
</script>

<template>
  <v-layout row wrap v-if="!isLoading">
    <v-flex xs12 sm12 md10 offset-md1 mt-4
      v-for="portfolio in portfolios" :key="portfolio.id"
    >
      <v-card>
        <v-card-title class="pt-0 pb-0 elevation-3">
          <v-toolbar color="white" flat>
            <v-btn icon light disabled class="ml-0">
              <v-icon color="grey darken-2">home</v-icon>
            </v-btn>
            <v-toolbar-title class="grey--text text--darken-4 ml-1">{{ portfolio.name }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon small class="mx-1"
              v-if="isAdmin"
              :loading="loading"
              @click="getSymbolsData()"
            >
              <v-icon>refresh</v-icon>
            </v-btn>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn icon small class="mx-1"
                  :to="`/portfolio/${portfolio.id}/edit`"
                >
                  <v-icon color="green darken-2" v-on="on">edit</v-icon>
                </v-btn>
              </template>
              <span>{{ $t("Update Portfolio") }}</span>
            </v-tooltip>
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn icon small class="mx-1"
                  @click="$refs.addShareModal.open(portfolio.id)"
                >
                  <v-icon color="blue darken-2" v-on="on">add</v-icon>
                </v-btn>
              </template>
              <span>{{ $t("Add Symbol") }}</span>
            </v-tooltip>
          </v-toolbar>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-data-table item-key="id" hide-actions
            :items="portfolio.shares"
            :headers="[
              { text: $t('Symbol'), value: 'symbol', align: 'left', sortable: false },
              { text: $t('Last Price'), value: 'last_price', align: 'center', sortable: false },
              { text: $t('Change'), value: 'change', align: 'center', sortable: false },
              { text: $t('Lots'), value: 'lots', align: 'center', sortable: false },
              { text: $t('Average Cost'), value: 'average_cost', align: 'center', sortable: false },
              { text: $t('Amount'), value: 'amount', align: 'center', sortable: false },
              { text: $t('Average Amount'), value: 'average_amount', align: 'center', sortable: false },
              { text: $t('Gain/Loss'), value: 'gain_loss', align: 'center', sortable: false },
            ]"
            :no-data-text="$t('You have not created any symbol.')"
          >
            <template slot="items" slot-scope="props">
              <router-link tag="tr" :to="`/share/${props.item.id}/transactions`">
                <td class="no-wrap">
                  <strong>{{ props.item.symbol.code }}</strong>
                  <span class="ml-1 caption grey--text font-weight-thin">{{ props.item.symbol.session_time }}</span>
                </td>
                <td class="text-xs-right darken-1"
                  :class="trendClass(props.item.symbol.trend)"
                >
                  {{ props.item.symbol.last_price }}
                </td>
                <td class="text-xs-right darken-1"
                  :class="trendClass(props.item.symbol.trend)"
                >
                  {{ props.item.symbol.rate_of_change }}
                </td>
                <td class="text-xs-right">
                  {{ $n(props.item.lot, "decimal") }}
                </td>
                <td class="text-xs-right no-wrap">
                  <span>{{ props.item.average }}</span>
                  <span class="ml-1 caption grey--text font-weight-thin">({{ props.item.average_with_dividend }})</span>
                </td>
                <td class="text-xs-right">
                  {{ props.item.amount }}
                </td>
                <td class="text-xs-right no-wrap">
                  <span>{{ props.item.average_amount }}</span>
                  <span class="ml-1 caption grey--text font-weight-thin">({{ props.item.average_amount_with_dividend }})</span>
                </td>
                <td class="text-xs-right no-wrap">
                  <span class="darken-1"
                    :class="[props.item.gain < 0 ? 'red--text' : 'green--text']"
                  >
                    {{ props.item.gain }}
                  </span>
                  <span class="ml-1 caption font-weight-thin darken-1"
                    :class="[props.item.gain_with_dividend < 0 ? 'red--text' : 'green--text']"
                  >
                    ({{ props.item.gain_with_dividend }})
                  </span>
                </td>
              </router-link>
            </template>
          </v-data-table>
        </v-card-text>
        <v-card-actions>
          <v-flex>
            <v-list dense>
              <item-detail v-for="(item, index) in itemDetails" :key="index"
                :item="item"
                :value="portfolio[item.key]"
                :baseLink="`portfolio/${portfolio.id}`"
              />
            </v-list>
          </v-flex>
        </v-card-actions>
      </v-card>
    </v-flex>

    <add-share-modal ref="addShareModal" />
  </v-layout>
</template>
