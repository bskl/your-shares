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
            }
        },
    }
</script>

<template>
    <v-layout row wrap v-if="!loading">
        <v-flex xs12 sm12 md10 offset-md1>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card>
                        <v-card-title class="pt-0 pb-0 elevation-4">
                            <v-toolbar color="white" flat>
                                <v-btn icon light to="/" exact>
                                    <v-icon color="grey darken-2">arrow_back</v-icon>
                                </v-btn>
                                <v-toolbar-title class="grey--text text--darken-4">{{ share.symbol.code }}</v-toolbar-title>
                                <v-icon slot="divider">chevron_right</v-icon>
                                <v-subheader :class="{ 'red--text darken-1': share.symbol.trend == -1, 'green--text darken-1': share.symbol.trend == 1 }">
                                    <i class="material-icons" v-show="share.symbol.trend == 1">arrow_drop_up</i>
                                    <i class="material-icons" v-show="share.symbol.trend == -1">arrow_drop_down</i>
                                    {{ $n(share.symbol.last_price, 'currency') }}
                                </v-subheader>
                                <v-subheader :class="{ 'red--text darken-1': share.symbol.trend == -1, 'green--text darken-1': share.symbol.trend == 1 }">
                                    {{ $n(share.symbol.rate_of_change, 'percent') }}
                                </v-subheader>
                                <v-subheader>
                                    {{ share.symbol.session_time }}
                                </v-subheader>
                            </v-toolbar>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-data-table
                                :items="share.transactions"
                                :headers="[
                                    { text: $t('Transaction Date'), value: 'transaction_date', align: 'left', sortable: false },
                                    { text: $t('Transaction'), value: 'transaction', align: 'center', sortable: false },
                                    { text: $t('Lots'), value: 'lots', align: 'center', sortable: false },
                                    { text: $t('Transaction Price'), value: 'transaction_price', align: 'center', sortable: false },
                                    { text: $t('Transaction Amount'), value: 'transaction_amount', align: 'center', sortable: false },
                                    { text: $t('Commission Price'), value: 'commission_price', align: 'center', sortable: false },
                                    { text: $t('Gain/Loss'), value: 'gain_loss', align: 'center', sortable: false },
                                ]"
                                item-key="id"
                                :no-data-text="$t('You have not any transaction.')"
                            >
                                <template slot="items" slot-scope="props">
                                    <td>{{ $d(new Date(props.item.date_at), 'short') }}</td>
                                    <td class="text-xs-right">{{ $t('transactions[' + props.item.type + ']') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.lot, 'decimal') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.price, 'currency') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.amount, 'currency') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.commission_price, 'currency') }}</td>
                                    <td class="text-xs-right" :class="{ 'red--text darken-1': props.item.sale_gain < 0, 'green--text darken-1': props.item.sale_gain > 0 }" v-if="props.item.type == 0 || props.item.type == 1">{{ $n(props.item.sale_gain, 'currency') }}</td>
                                    <td class="text-xs-right green--text darken-1" v-if="props.item.type == 2">{{ $n(props.item.dividend_gain, 'currency') }}</td>
                                    <td class="text-xs-right green--text darken-1" v-if="props.item.type == 3">{{ $n(props.item.bonus_issue, 'percent') }}</td>
                                </template>
                            </v-data-table>
                        </v-card-text>
                        <v-card-actions>
                            <v-flex xs12>
                                <v-list dense>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Total Sale Amount') }}
                                                <span class="grey--text text--lighten-1"> - <i>{{ $t('Total amount of all sale transactions.') }}</i></span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action>
                                            <strong>{{ $n(share.total_sale_amount, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Total Purchase Amount') }}
                                                <span class="grey--text text--lighten-1"> - <i>{{ $t('Total amount of all purchase transactions.') }}</i></span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action class="red--text darken-1">
                                            <strong>{{ $n(share.total_purchase_amount, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider><v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Paid Amount') }}
                                                <span class="grey--text text--lighten-1"> - <i>{{ $t('Total amount of purchase transactions.') }}</i></span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action class="red--text darken-1">
                                            <strong>{{ $n(share.paid_amount, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Gain/Loss') }}
                                                <span class="grey--text text--lighten-1"> - <i>{{ $t('Total gain/loss after sales.') }}</i></span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action :class="{ 'red--text darken-1': share.gain_loss < 0, 'green--text darken-1': share.gain_loss > 0 }">
                                            <strong>{{ $n(share.gain_loss, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Total Comission Amount') }}
                                                <span class="grey--text text--lighten-1"> - <i>{{ $t('Sum of commission amounts paid in all purchase / sale transactions.') }}</i></span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action class="red--text darken-1">
                                            <strong>{{ $n(share.total_commission_amount, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Total Dividend Gain') }}
                                                <span class="grey--text text--lighten-1"> - <i>{{ $t('Sum of dividend amounts.') }}</i></span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action class="green--text darken-1">
                                            <strong>{{ $n(share.total_dividend_gain, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Total Bonus Issue Share Gain') }}
                                                <span class="grey--text text--lighten-1"> - <i>{{ $t('Sum of bonus shares.') }}</i></span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action class="green--text darken-1">
                                            <strong>{{ $n(share.total_bonus_issue_share, 'decimal') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Total Gain') }}
                                                <span class="grey--text text--lighten-1"> - <i>{{ $t('Total gain. [(gain/loss + dividend) - commission amount]') }}</i></span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action :class="{ 'red--text darken-1': share.total_gain < 0, 'green--text darken-1': share.total_gain > 0 }">
                                            <strong>{{ $n(share.total_gain, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Instant Total Gain') }}
                                                <span class="grey--text text--lighten-1"> - <i>{{ $t('Gain on the instant stock price.') }}</i></span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action :class="{ 'red--text darken-1': calculateGain() < 0, 'green--text darken-1': calculateGain() > 0 }">
                                            <strong>{{ $n(calculateGain(), 'currency') }}</strong>
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
