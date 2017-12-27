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
                        <v-card-title>
                            <div class="subheading">{{ portfolio.name }}</div>
                            <v-spacer></v-spacer>
                            <v-btn icon small @click="showEditPortfolioModal(portfolio)">
                                <v-icon color="blue darken-2">edit</v-icon>
                            </v-btn>
                            <v-btn icon small @click="showAddShareModal(portfolio.id)">
                                <v-icon color="green darken-2">add</v-icon>
                            </v-btn>
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
                                        {{ props.item.symbol.rate_of_change }}%</td>
                                    <td class="text-xs-right">{{ props.item.lot }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.average, 'currency') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.amount, 'currency') }}</td>
                                    <td class="text-xs-right">{{ $n(props.item.average_amount, 'currency') }}</td>
                                    <td class="text-xs-right" :class="{ 'red--text darken-1': props.item.gain < 0, 'green--text darken-1': props.item.gain > 0 }">
                                        {{ $n(props.item.gain, 'currency') }}</td>
                                    <td class="text-xs-right">
                                        <v-btn v-if="props.item.average == 0" icon small @click="deleteShare(props.item)">
                                            <v-icon color="red darken-2">delete</v-icon>
                                        </v-btn>
                                        <v-btn v-if="props.item.average != 0" icon small :to="'/share/' + props.item.id + '/transactions'">
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
                            <v-spacer></v-spacer>
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
