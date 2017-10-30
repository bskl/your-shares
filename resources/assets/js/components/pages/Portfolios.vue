<script type="text/ecmascript-6">
    import { portfolioStore } from '../../stores/portfolioStore.js';
    import AddPortfolioModal from '../modals/AddPortfolioModal.vue';
    import EditPortfolioModal from '../modals/EditPortfolioModal.vue';
    import AddSymbolModal from '../modals/AddSymbolModal.vue';
    import AddTransactionModal from '../modals/AddTransactionModal.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'Portfolios',

        components: {
            AddPortfolioModal, EditPortfolioModal, AddSymbolModal, AddTransactionModal,
        },

        /*
         * The component's data.
         */
        data() {
            return {
                headers: [
                    { text: this.$t("Symbol"), value: 'symbol', align: 'left', sortable: false },
                    { text: this.$t("Last Price"), value: 'last_price', sortable: false },
                    { text: this.$t("Change"), value: 'change', sortable: false },
                    { text: this.$t("Lots"), value: 'lots', sortable: false },
                    { text: this.$t("Average"), value: 'average', sortable: false },
                    { text: this.$t("Total Amount"), value: 'total_amount', sortable: false },
                    { text: this.$t("Average Amount"), value: 'average_amount', sortable: false },
                    { text: this.$t("Gain"), value: 'gain', sortable: false }
                ],
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
            Bus.$on('transactionAdded', payload => this.updateSymbol(payload.symbol));
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
                this.state.portfolios.splice(index);
                Bus.$off('portfolioDeleted', portfolioId);
            },

            /**
             * Push added share to given portfolio.
             */
            pushShare(share) {
                let index = _.findIndex(this.state.portfolios, ['id', share.portfolio_id]);
                this.state.portfolios[index].shares.push(share);
                Bus.$off('shareAdded', share);
            },

            /**
             * Change updated symbol on portfolios.
             */
            updateSymbol(symbol) {
                let portfolioIndex = _.findIndex(this.state.portfolios, ['id', symbol.portfolio_id]);
                let index = _.findIndex(this.state.portfolios[portfolioIndex].symbols, ['id', symbol.id]);
                this.state.portfolios[portfolioIndex].symbols.splice(index, 1, symbol);

                Bus.$off('transactionAdded', symbol);
            },

            /**
             * Open the modal for editing portfolio.
             */
            showEditPortfolioModal(portfolio) {
                this.$refs.editPortfolioModal.open(portfolio);
            },
            
            /**
             * Open the modal for adding a new symbol.
             */
            showAddSymbolModal(portfolioId) {
                this.$refs.addSymbolModal.open(portfolioId);
            },

            /**
             * Open the modal for adding a new transaction.
             */
            showAddTransactionModal(shareId) {
                this.$refs.addTransactionModal.open(shareId);
            }
        },
    }
</script>

<template>
    <v-layout row wrap justify-center>
        <v-flex xs12 sm12 md10 offset-md1>
            <v-layout row wrap>
                <v-flex xs12 v-for="portfolio in state.portfolios" :key="portfolio.id">
                    <v-card>
                        <v-card-title>
                            <div class="subheading">{{ portfolio.name }}</div>
                            <v-spacer></v-spacer>
                            <v-btn icon small @click="showEditPortfolioModal(portfolio)">
                                <v-icon>edit</v-icon>
                            </v-btn>
                            <v-btn icon small @click="showAddSymbolModal(portfolio.id)">
                                <v-icon>add</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-data-table
                                :items="portfolio.shares"
                                :headers="headers"
                                item-key="id"
                                hide-actions
                                :no-data-text="$t('You have not created any symbol.')"
                                class="elevation-1"
                            >
                                <template slot="items" slot-scope="props">
                                    <td>{{ props.item.symbol.code }}</td>
                                    <td class="text-xs-right" :class="{ 'red--text darken-1': props.item.symbol.trend == -1, 'green--text darken-1': props.item.symbol.trend == 1 }">
                                        {{ $n(props.item.symbol.last_price, 'currency') }}</td>
                                    <td class="text-xs-right" :class="{ 'red--text darken-1': props.item.symbol.trend == -1, 'green--text darken-1': props.item.symbol.trend == 1 }">
                                        {{ props.item.symbol.rate_of_change }}%</td>
                                    <td class="text-xs-right">{{ props.item.lot }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.average, 'currency') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.total_amount, 'currency') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.average_amount, 'currency') }}</td>
                                    <td class="text-xs-right" :class="{ 'red--text darken-1': props.item.gain < 0, 'green--text darken-1': props.item.gain > 0 }">
                                        {{ $n(props.item.gain, 'currency') }}</td>
                                    <td>
                                        <v-btn icon @click="showAddTransactionModal(props.item.id)">
                                            <v-icon>add_circle_outline</v-icon>
                                        </v-btn>
                                    </td>
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

        <edit-portfolio-modal ref="editPortfolioModal" />
        <add-symbol-modal ref="addSymbolModal" />
        <add-transaction-modal ref="addTransactionModal" />

    </v-layout>
</template>
