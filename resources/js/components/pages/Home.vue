<script type="text/ecmascript-6">

import { mapState, mapActions, mapMutations, mapGetters } from 'vuex';
import AddShareModal from '../modals/AddShareModal.vue';
import ItemDetail from '../partials/ItemDetail.vue';
import { ITEM_DETAIL_MAP } from '../../store/constants.js';

export default {
    /*
     * The component's name.
     */
    name: 'Home',

    components: {
      AddShareModal, ItemDetail,
    },

    /*
     * The component's data.
     */
    data() {
      return {
          isLoading: false,
      }
    },

    watch: {
      $route() {
        this.fetchData();
      },
    },

    computed: {
      ...mapState([
        'portfolios',
      ]),

      ...mapGetters([
        'portfoliosCount', 'getPortfolioByIndex',
      ]),

      itemDetails() {
        return ITEM_DETAIL_MAP;
      }
    },

    methods: {
      ...mapActions([
        'fetchData', 'destroyShare', 'fetchSymbolsData',
      ]),

      ...mapMutations([
        'SET_SNACKBAR',
      ]),

      /**
       * Delete selected share
       */
      deleteShare(share) {
        this.destroyShare(share)
            .then()
            .catch((error) => {
              if (error.response.status == 404) {
                this.$router.push({ name: 'NotFound' });
              } else {
                this.SET_SNACKBAR({ color: 'error', text: error.response.data });
              }
            });
      },

      getSymbolsData() {
        this.fetchSymbolsData()
          .then()
          .catch((error) => {
            this.SET_SNACKBAR({ color: 'error', text: error.response.data });
          });
      }
    },

    created() {
      this.isLoading = true;

      this.fetchData()
        .then(() => {
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
    <v-flex xs12 sm12 md10 offset-md1>
      <v-layout row wrap>
        <v-flex
          xs12
          pb-4
          v-for="portfolio in portfolios"
          :key="portfolio.id"
        >
          <v-card>
            <v-card-title class="pt-0 pb-0 elevation-3">
              <v-toolbar color="white" flat>
                <v-btn icon light disabled class="ml-0">
                  <v-icon color="grey darken-2">home</v-icon>
                </v-btn>
                <v-toolbar-title class="grey--text text--darken-4 ml-1">
                  {{ portfolio.name }}
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon small class="mx-1"
                  @click="getSymbolsData()">
                  <v-icon>refresh</v-icon>
                </v-btn>
                <v-btn icon small class="mx-1"
                  :to="`/portfolio/${portfolio.id}/edit`">
                  <v-icon color="green darken-2">edit</v-icon>
                </v-btn>
                <v-btn icon small class="mx-1"
                  @click="$refs.addShareModal.open(portfolio.id)">
                  <v-icon color="blue darken-2">add</v-icon>
                </v-btn>
              </v-toolbar>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
              <v-data-table
                :items="portfolio.shares"
                :headers="[
                  {
                    text: $t('Symbol'),
                    value: 'symbol',
                    align: 'left',
                    sortable: false
                  },
                  {
                    text: $t('Last Price'),
                    value: 'last_price',
                    align: 'center',
                    sortable: false
                  },
                  {
                    text: $t('Change'),
                    value: 'change',
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
                    text: $t('Average Cost'),
                    value: 'average_cost',
                    align: 'center',
                    sortable: false
                  },
                  {
                    text: $t('Amount'),
                    value: 'amount',
                    align: 'center',
                    sortable: false
                  },
                  {
                    text: $t('Average Amount'),
                    value: 'average_amount',
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
                hide-actions
                :no-data-text="$t('You have not created any symbol.')"
              >
                <template slot="items" slot-scope="props">
                  <td class="no-wrap">
                    <strong>{{ props.item.symbol.code }}</strong>
                    <span class="ml-1 caption grey--text font-weight-thin">{{
                      props.item.symbol.session_time
                    }}</span>
                  </td>
                  <td
                    class="text-xs-right"
                    :class="{
                      'red--text darken-1': props.item.symbol.trend == -1,
                      'green--text darken-1': props.item.symbol.trend == 1
                    }"
                  >
                    {{ $n(props.item.symbol.last_price, "currency") }}
                  </td>
                  <td
                    class="text-xs-right"
                    :class="{
                      'red--text darken-1': props.item.symbol.trend == -1,
                      'green--text darken-1': props.item.symbol.trend == 1
                    }"
                  >
                    {{ $n(props.item.symbol.rate_of_change, "percent") }}
                  </td>
                  <td class="text-xs-right">
                    {{ $n(props.item.lot, "decimal") }}
                  </td>
                  <td class="text-xs-right no-wrap">
                    <span>{{ $n(props.item.average, "currency") }}</span>
                    <span class="ml-1 caption grey--text font-weight-thin"
                      >({{
                        $n(props.item.average_with_dividend, "currency")
                      }})</span
                    >
                  </td>
                  <td class="text-xs-right">
                    {{ $n(props.item.amount, "currency") }}
                  </td>
                  <td class="text-xs-right no-wrap">
                    <span>{{ $n(props.item.average_amount, "currency") }}</span>
                    <span class="ml-1 caption grey--text font-weight-thin"
                      >({{
                        $n(props.item.average_amount_with_dividend, "currency")
                      }})</span
                    >
                  </td>
                  <td class="text-xs-right no-wrap">
                    <span
                      :class="{
                        'red--text darken-1': props.item.gain < 0,
                        'green--text darken-1': props.item.gain > 0
                      }"
                      >{{ $n(props.item.gain, "currency") }}</span
                    >
                    <span
                      class="ml-1 caption font-weight-thin"
                      :class="{
                        'red--text darken-1': props.item.gain_with_dividend < 0,
                        'green--text darken-1':
                          props.item.gain_with_dividend > 0
                      }"
                      >({{
                        $n(props.item.gain_with_dividend, "currency")
                      }})</span
                    >
                  </td>
                  <td class="justify-center layout px-0">
                    <v-btn
                    v-if="props.item.total_purchase_amount == 0"
                      icon
                      small
                      class="mx-1"
                      @click="deleteShare(props.item)"
                    >
                      <v-icon color="red darken-2">delete</v-icon>
                    </v-btn>
                    <v-btn
                      v-if="props.item.total_purchase_amount != 0"
                      icon
                      small
                      class="mx-1"
                      :to="`/share/${props.item.id}/transactions`"
                    >
                      <v-icon color="blue darken-2">line_weight</v-icon>
                    </v-btn>
                    <v-btn
                      icon
                      small
                      class="mx-1"
                      :to="`/${portfolio.id}/${props.item.id}/transaction/add`"
                    >
                      <v-icon color="green darken-2">add_circle_outline</v-icon>
                    </v-btn>
                  </td>
                </template>
              </v-data-table>
            </v-card-text>

            <v-card-actions>
              <v-flex xs12>
                <v-list dense>
                  <item-detail 
                    v-for="(item, index) in itemDetails" :key="index"
                    :item="item"
                    :value="portfolio[item.key]"
                  />
                </v-list>
              </v-flex>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>

    <add-share-modal ref="addShareModal" />
  </v-layout>
</template>
