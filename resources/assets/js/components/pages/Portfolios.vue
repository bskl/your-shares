<script type="text/ecmascript-6">
    import { portfolioStore } from '../../stores/portfolioStore.js';
    import AddPortfolioModal from '../Modals/AddPortfolioModal.vue';
    import EditPortfolioModal from '../Modals/EditPortfolioModal.vue';
    import AddSymbolModal from '../Modals/AddSymbolModal.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'Portfolios',

        components: {
            AddPortfolioModal, EditPortfolioModal, AddSymbolModal,
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
            Bus.$on('portfolioAdded', payload => this.pushCreatedPortfolio(payload.portfolio));
            Bus.$on('portfolioUpdated', payload => this.changeUpdatedPortfolio(payload.portfolio));
            Bus.$on('portfolioDeleted', payload => this.deleteDeletedPortfolio(payload.portfolioId));
            Bus.$on('symbolAdded', payload => this.pushSymbolToPortfolio(payload.symbol));
        },

        created() {
            
        },

        methods: {
            /**
             * Push created portfolio to portfolios.
             */
            pushCreatedPortfolio(portfolio) {
                this.state.portfolios.push(portfolio);
            },

            /**
             * Change updated portfolio on portfolios.
             */
            changeUpdatedPortfolio(portfolio) {
                console.log(portfolio);
                let indexOfItem = _.findIndex(this.state.portfolios, ['id', portfolio.id]);
                this.state.portfolios.splice(indexOfItem, 1, portfolio);
            },

            /**
             * Delete deleted portfolio on portfolios.
             */
            deleteDeletedPortfolio(portfolioId) {
                let indexOfItem = _.findIndex(this.state.portfolios, ['id', portfolioId]);
                this.state.portfolios.splice(indexOfItem);
            },

            /**
             * Push added symbol to given portfolio.
             */
            pushSymbolToPortfolio(symbol) {
                let indexOfItem = _.findIndex(this.state.portfolios, ['id', symbol.pivot.portfolio_id]);
                this.state.portfolios[indexOfItem].symbols.push(symbol);
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
            <div class="marketing" v-if="!portfolio.symbols.length">
                <p class="lead">
                    {{ $t("You have not created any symbol.") }}
                </p>
            </div>
            <div class="symbol" v-else v-for="symbol in portfolio.symbols" :key="symbol.id">
                <span class="code">{{ symbol.code }}</span>
                <span class="code" v-bind:class="{ 'text-danger': symbol.change == -1, 'text-success': symbol.change == 1 }">{{ symbol.last_price }}</span>
                <span class="code" v-bind:class="{ 'text-danger': symbol.change == -1, 'text-success': symbol.change == 1 }">{{ symbol.rate_of_change }}</span>
                <span class="code">0,10</span>
                <span class="code">100</span>
                <span class="code">10.50</span>
                <span class="code">1050</span>
                <span class="code text-success">1590</span>
            </div>
        </div>

        <div class="row justify-content-end marketing">
            <button class="btn btn-primary"
                    @click="showAddPortfolioModal()">{{ $t("Add Portfolio") }}</button>
        </div>
        
        <add-portfolio-modal ref="addPortfolioModal" />
        <edit-portfolio-modal ref="editPortfolioModal" />
        <add-symbol-modal ref="addSymbolModal" />
    </div>
</template>
