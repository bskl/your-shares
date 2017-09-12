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
                symbols: [ ],
            }
        },

        /**
         * Prepare the component.
         */
        mounted() {
            Bus.$on('portfolioAdded', payload => this.pushCreatedPortfolio(payload.portfolio));
            Bus.$on('portfolioUpdated', payload => this.changeUpdatedPortfolio(payload.portfolio));
            Bus.$on('portfolioDeleted', payload => this.deleteDeletedPortfolio(payload.portfolioId));
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
             * Open the modal for adding a new portfolio.
             */
            showAddPortfolioModal() {
                this.$refs.addPortfolio.showModal = true;
            },

            /**
             * Open the modal for editing portfolio.
             */
            showEditPortfolioModal(portfolio) {
                this.$refs.editPortfolio.open(portfolio);
            },
            
            /**
             * Open the modal for adding a new symbol.
             */
            showAddSymbolModal() {
                this.$refs.addSymbol.showModal = true;
            },
        },
    }
</script>

<template>
    <div style="margin-bottom: 20px;">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card" style="margin-bottom: 20px;"
                     v-for="portfolio in state.portfolios" :key="portfolio.id">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                {{ portfolio.name }}
                            </span>
                            <span>
                                <a class="action-link text-primary" 
                                   @click="showAddSymbolModal()">{{ $t("Add Symbol") }}</a>
                                <a class="action-link text-primary" 
                                   @click="showEditPortfolioModal(portfolio)">{{ $t("Edit") }}</a>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="m-b-none" 
                           v-if="symbols.length === 0">
                           {{ $t("You have not created any symbol.") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-8 text-right">
                <button class="btn btn-primary"
                        @click="showAddPortfolioModal()">{{ $t("Add Portfolio") }}</button>
            </div>
        </div>

        <add-portfolio-modal ref="addPortfolio" />
        <edit-portfolio-modal ref="editPortfolio" />
        <add-symbol-modal ref="addSymbol" />
    </div>
</template>

<style scoped>
    .action-link {
        cursor: pointer;
    }

    .m-b-none {
        margin-bottom: 0;
    }
</style>