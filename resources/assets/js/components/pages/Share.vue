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
        },
    }
</script>


<template>
    <v-layout row wrap justify-center v-if="!loading">
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
                                hide-actions
                                :no-data-text="$t('You have not any transaction.')"
                            >
                                <template slot="items" slot-scope="props">
                                    <td>{{ props.item.date_at }}</td>
                                    <td class="text-xs-right">{{ props.item.type }}</td>
                                    <td class="text-xs-right">{{ props.item.lot }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.price, 'currency') }}</td>
                                    <td class="text-xs-right" v-if="props.item.type == 1 || props.item.type == 2">{{ $n(props.item.amount, 'currency') }}</td>
                                    <td class="text-xs-right" v-if="props.item.type == 3">{{ $n(props.item.dividend_gain, 'currency') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.commission_price, 'currency') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.sale_gain, 'currency') }}</td>
                                </template>
                            </v-data-table>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-flex>
    </v-layout>
</template>

