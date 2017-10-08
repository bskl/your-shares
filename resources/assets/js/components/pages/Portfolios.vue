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
            Bus.$on('portfolioAdded', payload => this.pushPortfolio(payload.portfolios));
            Bus.$on('portfolioUpdated', payload => this.updatePortfolio(payload.portfolio));
            Bus.$on('portfolioDeleted', payload => this.deletePortfolio(payload.portfolioId));
            Bus.$on('symbolAdded', payload => this.pushSymbolToPortfolio(payload.symbol));
            Bus.$on('transactionAdded', payload => this.updateSymbol(payload.symbol));
        },

        created() {
            
        },

        methods: {
            /**
             * Push created portfolio to portfolios.
             */
            pushPortfolio(portfolios) {
                this.state.portfolios = portfolios;
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
            showAddTransactionModal(portfolioSymbolId) {
                this.$refs.addTransactionModal.open(portfolioSymbolId);
            }
        },
    }
</script>

<template>
    <div>
        <div class="portfolio" v-for="portfolio in state.portfolios" :key="portfolio.id">
            <div class="portfolio clearfix">
                <nav>
                    <ul class="nav nav-pills float-right">
                        <li class="nav-item">
                            <a class="btn action-link nav-link"
                                @click="showAddSymbolModal(portfolio.id)">{{ $t("Add Symbol") }}
                                <span class="sr-only">{{ $t("Add Symbol") }}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="btn action-link nav-link"
                                @click="showEditPortfolioModal(portfolio)">{{ $t("Edit") }}
                                <span class="sr-only">{{ $t("Edit") }}</span></a>
                        </li>
                    </ul>
                </nav>
                <h4 class="text-muted float-left">{{ portfolio.name }}</h4>
            </div>
            <table class="table table-striped">
                <tr class="no-symbol" v-if="!portfolio.symbols.length">
                    <td>
                        <p class="lead">
                            {{ $t("You have not created any symbol.") }}
                        </p>
                    </td>
                </tr>
                <tr class="clearfix" v-else v-for="portfolioSymbol in portfolio.symbols" :key="portfolioSymbol.id">
                    <td>{{ portfolioSymbol.symbol.code }}</td>
                    <td v-bind:class="{ 'text-danger': portfolioSymbol.symbol.trend == -1, 'text-success': portfolioSymbol.symbol.trend == 1 }">
                        {{ portfolioSymbol.symbol.last_price_formatted }}</td>
                    <td v-bind:class="{ 'text-danger': portfolioSymbol.symbol.trend == -1, 'text-success': portfolioSymbol.symbol.trend == 1 }">
                        {{ portfolioSymbol.symbol.rate_of_change }}%</td>
                    <td>{{ portfolioSymbol.share }}</td>
                    <td>{{ portfolioSymbol.average_formatted }}</td>
                    <td>{{ portfolioSymbol.amount_formatted }}</td>                
                    <td>{{ portfolioSymbol.total_average_formatted }}</td>
                    <td v-bind:class="{ 'text-danger': portfolioSymbol.gain_formatted < 0, 'text-success': portfolioSymbol.gain_formatted > 0 }">
                        {{ portfolioSymbol.gain_formatted }}</td>                
                    <td>
                        <a class="btn btn-sm action-link"
                            @click="showAddTransactionModal(portfolioSymbol.id)">{{ $t("Add Transaction") }}
                            <span class="sr-only">{{ $t("Add Transaction") }}</span></a>
                    </td>
                </tr>
            </table>
        </div>

        <div class="row justify-content-end no-symbol">
            <button class="btn btn-primary"
                    @click="showAddPortfolioModal()">{{ $t("Add Portfolio") }}</button>
        </div>
        
        <add-portfolio-modal ref="addPortfolioModal" />
        <edit-portfolio-modal ref="editPortfolioModal" />
        <add-symbol-modal ref="addSymbolModal" />
        <add-transaction-modal ref="addTransactionModal" />
    </div>
</template>
