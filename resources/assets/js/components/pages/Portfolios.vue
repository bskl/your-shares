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
            Bus.$on('symbolAdded', payload => this.pushSymbolToPortfolio(payload.symbol));
            Bus.$on('transactionAdded', payload => this.updateSymbol(payload.symbol));
        },

        created() {
            
        },

        methods: {
            /**
             * Determine if we have any shares.
             */
            anyShares() {
                return ! _.isEmpty(this.state.portfolios.shares);
            },

            /**
             * Push created portfolio to portfolios.
             */
            pushPortfolio(portfolio) {
                this.state.portfolios.push(portfolio);
                Bus.$off('portfolioAdded', portfolios);
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
             * Push added symbol to given portfolio.
             */
            pushSymbolToPortfolio(symbol) {
                let index = _.findIndex(this.state.portfolios, ['id', symbol.portfolio_id]);
                this.state.portfolios[index].symbols.push(symbol);
                Bus.$off('symbolAdded', symbol);
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
             * Open the modal for adding a new portfolio.
             */
            showAddPortfolioModal() {
                this.$refs.addPortfolioModal.open();
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
    <div class="container">
        <div class="row" v-for="portfolio in state.portfolios" :key="portfolio.id">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <nav class="nav nav-pills float-right">
                            <a class="nav-link" @click="showAddSymbolModal(portfolio.id)">{{ $t("Add Symbol") }}</a>
                            <a class="nav-link" @click="showEditPortfolioModal(portfolio)">{{ $t("Edit") }}</a>
                        </nav>
                        <h5 class="float-left mt-2">{{ portfolio.name }}</h5>
                    </div>
                </div>
                <hr />
                <div class="row justify-content-center">
                    <div class="col-md-10" v-if="!anyShares()">
                        <p class="text-center mt-3">{{ $t("You have not created any symbol.") }}</p>
                    </div>
                    <div class="col-md-10" v-else>
                        <table class="table table-striped">
                            <tr class="clearfix" v-for="share in portfolio.shares" :key="share.id">
                                <td>{{ share.symbol.code }}</td>
                                <td v-bind:class="{ 'text-danger': share.symbol.trend == -1, 'text-success': share.symbol.trend == 1 }">
                                    {{ share.symbol.last_price_formatted }}</td>
                                <td v-bind:class="{ 'text-danger': share.symbol.trend == -1, 'text-success': share.symbol.trend == 1 }">
                                    {{ share.symbol.rate_of_change }}%</td>
                                <td>{{ share.share }}</td>
                                <td>{{ share.average_formatted }}</td>
                                <td>{{ share.amount_formatted }}</td>                
                                <td>{{ share.total_average_formatted }}</td>
                                <td v-bind:class="{ 'text-danger': share.gain_formatted < 0, 'text-success': share.gain_formatted > 0 }">
                                    {{ share.gain_formatted }}</td>                
                                <td>
                                    <a class="btn btn-sm action-link"
                                        @click="showAddTransactionModal(share.id)">{{ $t("Add Transaction") }}
                                        <span class="sr-only">{{ $t("Add Transaction") }}</span></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-2">
                <button class="btn btn-primary"
                        @click="showAddPortfolioModal()">{{ $t("Add Portfolio") }}</button>
            </div>
        </div>
        
        <add-portfolio-modal ref="addPortfolioModal" />
        <edit-portfolio-modal ref="editPortfolioModal" />
        <add-symbol-modal ref="addSymbolModal" />
        <add-transaction-modal ref="addTransactionModal" />
    </div>
</template>
