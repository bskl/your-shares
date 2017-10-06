<script type="text/ecmascript-6">
    import { portfolioStore } from '../../stores/portfolioStore.js';
    import AddPortfolioModal from '../Modals/AddPortfolioModal.vue';
    import EditPortfolioModal from '../Modals/EditPortfolioModal.vue';
    import AddSymbolModal from '../Modals/AddSymbolModal.vue';
    import AddTransactionModal from '../Modals/AddTransactionModal.vue';

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
                let indexOfItem = _.findIndex(this.state.portfolios, ['id', portfolio.id]);
                this.state.portfolios.splice(indexOfItem, 1, portfolio);
                Bus.$off('portfolioUpdated', portfolio);
            },

            /**
             * Delete deleted portfolio on portfolios.
             */
            deletePortfolio(portfolioId) {
                let indexOfItem = _.findIndex(this.state.portfolios, ['id', portfolioId]);
                this.state.portfolios.splice(indexOfItem);
                Bus.$off('portfolioDeleted', portfolioId);
            },

            /**
             * Push added symbol to given portfolio.
             */
            pushSymbolToPortfolio(symbol) {
                let indexOfItem = _.findIndex(this.state.portfolios, ['id', symbol.portfolio_id]);
                this.state.portfolios[indexOfItem].symbols.push(symbol);
                Bus.$off('symbolAdded', symbol);
            },

            /**
             * Change updated symbol on portfolios.
             */
            updateSymbol(symbol) {
                _.forEach(this.state.portfolios, function(value, key) {
                    let indexOfItem = _.findIndex(value.symbols, ['id', symbol.id]);
                    if(indexOfItem != -1) {
                        value.symbols.splice(indexOfItem, 1, symbol);
                        break;
                    }
                });
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
            <div class="no-symbol" v-if="!portfolio.symbols.length">
                <p class="lead">
                    {{ $t("You have not created any symbol.") }}
                </p>
            </div>
            <div class="clearfix" v-else v-for="portfolioSymbol in portfolio.symbols" :key="portfolioSymbol.id">
                <nav>
                    <ul class="nav nav-pills float-right">
                        <li class="nav-item">
                            <a class="btn btn-sm action-link"
                                @click="showAddTransactionModal(portfolioSymbol.id)">{{ $t("Add Transaction") }}
                                <span class="sr-only">{{ $t("Add Transaction") }}</span></a>
                        </li>
                    </ul>
                </nav>
                <div class="symbol float-left">
                    <span>{{ portfolioSymbol.symbol.code }}</span>
                    <span v-bind:class="{ 'text-danger': portfolioSymbol.symbol.change == -1, 'text-success': portfolioSymbol.symbol.change == 1 }">
                        {{ portfolioSymbol.symbol.last_price_formatted }}</span>
                    <span v-bind:class="{ 'text-danger': portfolioSymbol.symbol.change == -1, 'text-success': portfolioSymbol.symbol.change == 1 }">
                        {{ portfolioSymbol.symbol.rate_of_change }}%</span>
                    <span>{{ portfolioSymbol.share }}</span>
                    <span>{{ portfolioSymbol.average_formatted }}</span>
                    <span>{{ portfolioSymbol.amount_formatted }}</span>                
                    <span>{{ portfolioSymbol.total_average_formatted }}</span>
                    <span v-bind:class="{ 'text-danger': portfolioSymbol.gain_formatted < 0, 'text-success': portfolioSymbol.gain_formatted > 0 }">
                        {{ portfolioSymbol.gain_formatted }}</span>                
                </div>
            </div>
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
