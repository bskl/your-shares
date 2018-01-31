<script type="text/ecmascript-6">
    import { portfolioStore } from '../../stores/portfolioStore.js';
    import AddPortfolioModal from '../modals/AddPortfolioModal.vue';
    import EditPortfolioModal from '../modals/EditPortfolioModal.vue';
    import AddShareModal from '../modals/AddShareModal.vue';
    import AddTransactionModal from '../modals/AddTransactionModal.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'Portfolios',

        components: {
            AddPortfolioModal, EditPortfolioModal, AddShareModal, AddTransactionModal,
        },

        /*
         * The component's data.
         */
        data() {
            return {
                state: portfolioStore.state,
            }
        },

        /**
         * Prepare the component.
         */
        mounted() {
            Bus.$on('portfolioAdded', payload => this.pushPortfolio(payload.portfolio));
            Bus.$on('portfolioUpdated', payload => this.updatePortfolio(payload.portfolio));
            Bus.$on('portfolioDeleted', payload => this.deletePortfolio(payload.portfolioId));
            Bus.$on('shareAdded', payload => this.pushShare(payload.share));
            Bus.$on('transactionAdded', payload => this.updateShare(payload.share));
        },

        created() {
            
        },

        methods: {
            /**
             * Push created portfolio to portfolios.
             */
            pushPortfolio(portfolio) {
                this.state.portfolios.push(portfolio);
                Bus.$off('portfolioAdded', portfolio);
            },

            /**
             * Change updated portfolio on portfolios.
             */
            updatePortfolio(portfolio) {
                let index = _.findIndex(this.state.portfolios, ['id', portfolio.id]);
                this.state.portfolios.splice(index, 1, portfolio);
                Bus.$off('portfolioUpdated', portfolio);
            },

            /**
             * Delete deleted portfolio on portfolios.
             */
            deletePortfolio(portfolioId) {
                let index = _.findIndex(this.state.portfolios, ['id', portfolioId]);
                this.state.portfolios.splice(index, 1);
                Bus.$off('portfolioDeleted', portfolioId);
            },

            /**
             * Push added share to given portfolio.
             */
            pushShare(share) {
                let portfolioIndex = _.findIndex(this.state.portfolios, ['id', share.portfolio_id]);
                this.state.portfolios[portfolioIndex].shares.push(share);
                Bus.$off('shareAdded', share);
            },

            /**
             * Change updated share on portfolios.
             */
            updateShare(share) {
                let portfolioIndex = _.findIndex(this.state.portfolios, ['id', share.portfolio_id]);
                let index = _.findIndex(this.state.portfolios[portfolioIndex].shares, ['id', share.id]);
                this.state.portfolios[portfolioIndex].shares.splice(index, 1, share);
                this.state.portfolios[portfolioIndex].total_sale_amount = share.portfolio.total_sale_amount;
                this.state.portfolios[portfolioIndex].total_average_amount = share.portfolio.total_average_amount;
                this.state.portfolios[portfolioIndex].total_commission_amount = share.portfolio.total_commission_amount;
                this.state.portfolios[portfolioIndex].total_dividend_gain = share.portfolio.total_dividend_gain;
                this.state.portfolios[portfolioIndex].total_bonus_issue_share = share.portfolio.total_bonus_issue_share;
                this.state.portfolios[portfolioIndex].total_gain = share.portfolio.total_gain;
                Bus.$off('transactionAdded', share);
            },

            /**
             * Delete selected share
             */
            deleteShare(share) {
                axios.delete('/share/' + share.id)
                    .then(response => {
                        let portfolioIndex = _.findIndex(this.state.portfolios, ['id', share.portfolio_id]);
                        let index = _.findIndex(this.state.portfolios[portfolioIndex].shares, ['id', share.id]);
                        this.state.portfolios[portfolioIndex].shares.splice(index, 1);
                    })
            },

            /**
             * Open the modal for editing portfolio.
             */
            showEditPortfolioModal(portfolio) {
                this.$refs.editPortfolioModal.open(portfolio);
            },
            
            /**
             * Open the modal for adding a new share.
             */
            showAddShareModal(portfolioId) {
                this.$refs.addShareModal.open(portfolioId);
            },

            /**
             * Open the modal for adding a new transaction.
             */
            showAddTransactionModal(shareId) {
                this.$refs.addTransactionModal.open(shareId);
            },

            calculateGain(portfolio) {
                let shareGain = 0;

                _.forEach(portfolio.shares, function(share) {
                    shareGain += (+share.gain)
                })

                return (+shareGain) + (+portfolio.total_gain)
            }
        },
    }
</script>

<template>
    <v-layout row wrap>
        <v-flex xs12 sm12 md10 offset-md1>
            <v-layout row wrap>
                <v-flex xs12 v-for="portfolio in state.portfolios" :key="portfolio.id">
                    <v-card>
                        <v-card-title class="pt-0 pb-0 elevation-4">
                            <v-toolbar color="white" flat>
                                <v-btn icon light disabled>
                                    <v-icon color="grey darken-2">home</v-icon>
                                </v-btn>
                                <v-toolbar-title class="grey--text text--darken-4">{{ portfolio.name }}</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-btn icon small @click="showEditPortfolioModal(portfolio)">
                                    <v-icon color="green darken-2">edit</v-icon>
                                </v-btn>
                                <v-btn icon small @click="showAddShareModal(portfolio.id)">
                                    <v-icon color="blue darken-2">add</v-icon>
                                </v-btn>
                            </v-toolbar>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-data-table
                                :items="portfolio.shares"
                                :headers="[
                                    { text: $t('Symbol'), value: 'symbol', align: 'left', sortable: false },
                                    { text: $t('Last Price'), value: 'last_price', sortable: false },
                                    { text: $t('Change'), value: 'change', sortable: false },
                                    { text: $t('Lots'), value: 'lots', sortable: false },
                                    { text: $t('Average'), value: 'average', sortable: false },
                                    { text: $t('Amount'), value: 'amount', sortable: false },
                                    { text: $t('Average Amount'), value: 'average_amount', sortable: false },
                                    { text: $t('Gain'), value: 'gain', sortable: false },
                                    { text: $t('Transactions'), value: 'transactions', align: 'center', sortable: false }
                                ]"
                                item-key="id"
                                hide-actions
                                :no-data-text="$t('You have not created any symbol.')"
                            >
                                <template slot="items" slot-scope="props">
                                    <td>{{ props.item.symbol.code }}</td>
                                    <td class="text-xs-right" :class="{ 'red--text darken-1': props.item.symbol.trend == -1, 'green--text darken-1': props.item.symbol.trend == 1 }">
                                        {{ $n(props.item.symbol.last_price, 'currency') }}</td>
                                    <td class="text-xs-right" :class="{ 'red--text darken-1': props.item.symbol.trend == -1, 'green--text darken-1': props.item.symbol.trend == 1 }">
                                        {{ $n(props.item.symbol.rate_of_change, 'percent') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.lot, 'decimal') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.average, 'currency') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.amount, 'currency') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.average_amount, 'currency') }}</td>
                                    <td class="text-xs-right" :class="{ 'red--text darken-1': props.item.gain < 0, 'green--text darken-1': props.item.gain > 0 }">
                                        {{ $n(props.item.gain, 'currency') }}</td>
                                    <td class="text-xs-right">
                                        <v-btn v-if="props.item.total_amount == 0" icon small @click="deleteShare(props.item)">
                                            <v-icon color="red darken-2">delete</v-icon>
                                        </v-btn>
                                        <v-btn v-if="props.item.total_amount != 0" icon small :to="'/share/' + props.item.id + '/transactions'">
                                            <v-icon color="blue darken-2">line_weight</v-icon>
                                        </v-btn>
                                        <v-btn icon small @click="showAddTransactionModal(props.item.id)">
                                            <v-icon color="green darken-2">add_circle_outline</v-icon>
                                        </v-btn>
                                    </td>
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
                                                <span class="grey--text text--lighten-1"> - İlgili portföydeki hisselerin tüm satış tutarların toplamı</span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action>
                                            <strong>{{ $n(portfolio.total_sale_amount, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Total Average Amount') }}
                                                <span class="grey--text text--lighten-1"> - İlgili portföydeki hisselerin ilk alım işleminden itibaren ödenen işlem tutarlarının toplamı</span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action class="red--text darken-1">
                                            <strong>{{ $n(portfolio.total_average_amount, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Total Comission Amount') }}
                                                <span class="grey--text text--lighten-1"> - İlgili portföydeki hisselerin tüm alım/satım işlemlerinde ödenen komisyon tutarlarının toplamı</span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action class="red--text darken-1">
                                            <strong>{{ $n(portfolio.total_commission_amount, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Total Dividend Gain') }}
                                                <span class="grey--text text--lighten-1"> - İlgili portföydeki hisselerden kazanılan tüm temettü tutarlarının toplamı</span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action class="green--text darken-1">
                                            <strong>{{ $n(portfolio.total_dividend_gain, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Total Bonus Issue Share Gain') }}
                                                <span class="grey--text text--lighten-1"> - İlgili portföydeki hisselerden kazanılan tüm bedelsiz hisse miktarlarının toplamı</span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action class="green--text darken-1">
                                            <strong>{{ $n(portfolio.total_bonus_issue_share, 'decimal') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Total Gain') }}
                                                <span class="grey--text text--lighten-1"> - (satış karı+temettü kazancı)-(toplam tutar+komisyon tutarı) ile hesaplanan tutar</span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action :class="{ 'red--text darken-1': portfolio.total_gain < 0, 'green--text darken-1': portfolio.total_gain > 0 }">
                                            <strong>{{ $n(portfolio.total_gain, 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                    <v-divider></v-divider>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                {{ $t('Instant Total Gain') }}
                                                <span class="grey--text text--lighten-1"> - İlgili portföydeki hisselerin anlık hisse fiyatı ile kazanılacak kazanç ile hesaplanan tutar</span>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                        <v-list-tile-action :class="{ 'red--text darken-1': calculateGain(portfolio) < 0, 'green--text darken-1': calculateGain(portfolio) > 0 }">
                                            <strong>{{ $n(calculateGain(portfolio), 'currency') }}</strong>
                                        </v-list-tile-action>
                                    </v-list-tile>
                                </v-list>
                            </v-flex>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-flex>

        <edit-portfolio-modal ref="editPortfolioModal" />
        <add-share-modal ref="addShareModal" />
        <add-transaction-modal ref="addTransactionModal" />

    </v-layout>
</template>
