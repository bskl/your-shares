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
                        <v-card-title>
                            <div class="subheading">{{ share.symbol.code }}</div>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-data-table
                                :items="share.transactions"
                                :headers="[
                                    { text: $t('Transaction Date'), value: 'transaction_date', align: 'left', sortable: false },
                                    { text: $t('Transaction'), value: 'transaction', sortable: false },
                                    { text: $t('Lots'), value: 'lots', sortable: false },
                                    { text: $t('Transaction Price'), value: 'transaction_price', sortable: false },
                                    { text: $t('Transaction Amount'), value: 'transaction_amount', sortable: false },
                                    { text: $t('Commission Price'), value: 'commission_price', sortable: false },
                                    { text: $t('Gain'), value: 'gain', sortable: false },
                                ]"
                                item-key="id"
                                :no-data-text="$t('You have not any transaction.')"
                            >
                                <template slot="items" slot-scope="props">
                                    <td>{{ $d(new Date(props.item.date_at), 'short') }}</td>
                                    <td class="text-xs-right">{{ $t('transactions[' + props.item.type + ']') }}</td>
                                    <td class="text-xs-right">{{ props.item.lot }}</td>
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
                            <v-flex xs5 offset-xs0 offset-md4 offset-lg7>
                                <v-list dense>
                                    <v-list-tile dense>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Toplam Maliyet</v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action>
                                            {{ $n(share.total_amount, 'currency') }}
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile dense>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Toplam Komisyon Maliyeti</v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action>
                                            {{ $n(share.total_commission_amount, 'currency') }}
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile dense>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Toplam Temettü Kazancı</v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action>
                                            {{ $n(share.total_dividend_gain, 'currency') }}
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile dense>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Toplam Bedelsiz Kazancı</v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action>
                                            {{ share.total_bonus_issue_share }}
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile dense>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Toplam Kazanç</v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action :class="{ 'red--text darken-1': calculateGain() < 0, 'green--text darken-1': calculateGain() > 0 }">
                                            {{ $n(calculateGain(), 'currency') }}
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                </v-list>
                            </v-flex>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-flex>
    </v-layout>
</template>
