<script type="text/ecmascript-6">
import { userStore } from '../../stores/userStore.js';
import MainLayout from '../layout/MainLayout.vue';

export default {
    /*
     * The component's name.
     */
    name: 'Share',

    components: {
        MainLayout,
    },

    /*
     * The component's data.
     */
    data() {
        return {
            loading: true,
            share: '',
        }
    },

    mounted() {
        if (!userStore.isAuthenticated()) {
            this.$router.push('/login');
        } else {
            this.init();
        }
    },

    created() {

    },

    methods: {
        init() {
            NProgress.start();

            return new Promise((resolve, reject) => {
                http.get('/share/' + this.$route.params.shareId + '/transactions', ({ data }) => {
                    this.share = data;
                    resolve(this.share);
                    this.loading = false;
                }, error => reject(error))
            })
        },

        calculateGain() {
            return (+this.share.gain) + (+this.share.total_gain)
        },

        isLast(id) {
            for (let index = 0, len = this.share.transactions.length; index < len; ++index) {
                if (this.share.transactions[index].id == id && index + 1 == len) {
                    return true;
                }
            }
            return false;
        }
    },
}
</script>

<template>
  <v-layout row wrap v-if="!loading">
    <v-flex xs12 sm12 md10 offset-md1>
      <v-layout row wrap>
        <v-flex xs12 pb-4>
          <v-card>
            <v-card-title class="pt-0 pb-0 elevation-3 no-wrap">
              <v-toolbar color="white" flat>
                <v-btn icon light class="ml-0" to="/" exact>
                  <v-icon color="grey darken-2">arrow_back</v-icon>
                </v-btn>
                <v-toolbar-title class="grey--text text--darken-4 ml-1">{{
                  share.symbol.code
                }}</v-toolbar-title>
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
                :items="share.transactions"
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
                    {{ $t("transactions[" + props.item.type + "]") }}
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
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ $t("Sale Amount") }}
                        <span class="grey--text text--lighten-1">
                          -
                          <i>{{
                            $t("Total amount of all sale transactions.")
                          }}</i></span
                        >
                      </v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action>
                      <strong>{{
                        $n(share.total_sale_amount, "currency")
                      }}</strong>
                    </v-list-tile-action>
                  </v-list-tile>
                  <v-divider></v-divider>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ $t("Purchase Amount") }}
                        <span class="grey--text text--lighten-1">
                          -
                          <i>{{
                            $t("Total amount of all purchase transactions.")
                          }}</i></span
                        >
                      </v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action class="red--text darken-1">
                      <strong>{{
                        $n(share.total_purchase_amount, "currency")
                      }}</strong>
                    </v-list-tile-action>
                  </v-list-tile>
                  <v-divider></v-divider
                  ><v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ $t("Paid Amount") }}
                        <span class="grey--text text--lighten-1">
                          -
                          <i>{{
                            $t("Total amount of purchase transactions.")
                          }}</i></span
                        >
                      </v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action class="red--text darken-1">
                      <strong>{{ $n(share.paid_amount, "currency") }}</strong>
                    </v-list-tile-action>
                  </v-list-tile>
                  <v-divider></v-divider>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ $t("Gain/Loss") }}
                        <span class="grey--text text--lighten-1">
                          -
                          <i>{{ $t("Total gain/loss after sales.") }}</i></span
                        >
                      </v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action
                      :class="{
                        'red--text darken-1': share.gain_loss < 0,
                        'green--text darken-1': share.gain_loss > 0
                      }"
                    >
                      <strong>{{ $n(share.gain_loss, "currency") }}</strong>
                    </v-list-tile-action>
                  </v-list-tile>
                  <v-divider></v-divider>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ $t("Comission Amount") }}
                        <span class="grey--text text--lighten-1">
                          -
                          <i>{{
                            $t(
                              "Sum of commission amounts paid in all purchase / sale transactions."
                            )
                          }}</i></span
                        >
                      </v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action class="red--text darken-1">
                      <strong>{{
                        $n(share.total_commission_amount, "currency")
                      }}</strong>
                    </v-list-tile-action>
                  </v-list-tile>
                  <v-divider></v-divider>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ $t("Dividend Gain") }}
                        <span class="grey--text text--lighten-1">
                          - <i>{{ $t("Sum of dividend amounts.") }}</i></span
                        >
                      </v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action class="green--text darken-1">
                      <strong>{{
                        $n(share.total_dividend_gain, "currency")
                      }}</strong>
                    </v-list-tile-action>
                  </v-list-tile>
                  <v-divider></v-divider>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ $t("Bonus Share Gain") }}
                        <span class="grey--text text--lighten-1">
                          - <i>{{ $t("Sum of bonus shares.") }}</i></span
                        >
                      </v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action class="green--text darken-1">
                      <strong>{{
                        $n(share.total_bonus_share, "decimal")
                      }}</strong>
                    </v-list-tile-action>
                  </v-list-tile>
                  <v-divider></v-divider>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ $t("Gain") }}
                        <span class="grey--text text--lighten-1">
                          -
                          <i>{{
                            $t(
                              "Total gain. [(gain/loss + dividend) - commission amount]"
                            )
                          }}</i></span
                        >
                      </v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action
                      :class="{
                        'red--text darken-1': share.total_gain < 0,
                        'green--text darken-1': share.total_gain > 0
                      }"
                    >
                      <strong>{{ $n(share.total_gain, "currency") }}</strong>
                    </v-list-tile-action>
                  </v-list-tile>
                  <v-divider></v-divider>
                  <v-list-tile>
                    <v-list-tile-content>
                      <v-list-tile-title>
                        {{ $t("Instant Gain") }}
                        <span class="grey--text text--lighten-1">
                          -
                          <i>{{
                            $t("Gain on the instant stock price.")
                          }}</i></span
                        >
                      </v-list-tile-title>
                    </v-list-tile-content>
                    <v-list-tile-action
                      :class="{
                        'red--text darken-1': calculateGain() < 0,
                        'green--text darken-1': calculateGain() > 0
                      }"
                    >
                      <strong>{{ $n(calculateGain(), "currency") }}</strong>
                    </v-list-tile-action>
                  </v-list-tile>
                </v-list>
              </v-flex>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>
