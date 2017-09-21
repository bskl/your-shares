<script type="text/ecmascript-6">
    import Modal from '../modals/modal/Modal.vue';
    import ModalHeading from '../modals/modal/ModalHeading.vue';
    import ModalBody from '../modals/modal/ModalBody.vue';
    import Spinner from '../loaders/Spinner.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'EditPortfolioModal',

        components: {
            Modal, ModalHeading, ModalBody, Spinner,
        },

        /**
         * The component's data.
         */
        data() {
            return {
                form: new Form({
                    id: 0,
                    user_id: 0,
                    name: '',
                    currency: '',
                    order: 0,
                }),
                currencies: [
                    {
                        'alphabeticCode': 'TRY',
                        'currency': 'Turkish Lira'
                    }
                ],
                saving: false,
                showModal: false,
            };
        },

        /**
         * Prepare the component.
         */
        mounted() {
            document.addEventListener("keydown", (e) => {
                if (e.keyCode == 27) {
                    this.close();
                }
            });
        },

        methods: {
            /**
             * Set form elements from given data and open the model.
             */
            open(portfolio) {
                this.form = new Form(portfolio);
                this.showModal = true;
            },

            /**
             * Close the modal and reset form elements.
             */
            close() {
                this.showModal = false;
                this.saving = false;
                this.form.reset();
            },

            /**
             * Save the portfolio and hide the modal.
             */
            updatePortfolio() {
                this.saving = true;

                this.form.put('/portfolio/' + this.form.id)
                    .then(response => {
                        console.log(response.data);
                        Bus.$emit('portfolioUpdated', {
                            portfolio: response.data
                        });

                        this.close();
                    }, error => {
                        this.saving = false;
                    });
            },

            /**
             * Delete selected portfolio.
             */
            destroyPortfolio() {
                this.saving = true;

                this.form.delete('/portfolio/' + this.form.id)
                    .then(response => {
                        Bus.$emit('portfolioDeleted', {
                            portfolioId: this.form.id
                        });

                        this.close();
                    }, error => {
                        this.saving = false;
                    });
            },
        },
    }
</script>

<template>
    <modal width="360" v-show="this.showModal">
        <modal-heading>
            <span>{{ $t("Update Portfolio") }}</span>
            <button type="button" class="close" aria-label="Close" @click="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </modal-heading>
        <modal-body>
            <div v-if="saving">
                <spinner />
            </div>
            <div v-else>
                <form class="form-horizontal" role="form" method="PUT"
                        v-on:submit.prevent="updatePortfolio" v-on:keydown="form.errors.clear($event.target.name)">
                    <div class="form-group">
                        <div class="col-md-12"
                                v-bind:class="{ 'has-danger': form.errors.has('name') }">
                            <input type="text" id="name" name="name" class="form-control" autofocus
                                    v-bind:placeholder="$t('Portfolio Name')" v-model="form.name">
                            <label class="sr-only" for="name">{{ $t("Portfolio Name") }}</label>

                            <span class="form-text text-danger"
                                    v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12"
                                v-bind:class="{ 'has-danger': form.errors.has('currency') }">
                            <select id="currency" name="currency" class="form-control" 
                                    v-bind:placeholder="$t('Currency')" v-model="form.currency">
                                <option disabled value="">{{ $t("Currency") }}</option>
                                <option v-for="currency in currencies" v-bind:value="currency.alphabeticCode" :key="currency.key">
                                    {{ currency.currency }}
                                </option>
                            </select>
                            <label class="sr-only" for="currency">{{ $t("Currency") }}</label>

                            <span class="form-text text-danger"
                                    v-if="form.errors.has('currency')" v-text="form.errors.get('currency')"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <button type="button" class="btn btn-danger"
                                    @click="destroyPortfolio()">{{ $t("Delete Portfolio") }}</button>
                            <button type="submit" class="btn btn-primary"
                                    :disabled="form.errors.any()">{{ $t("Update") }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </modal-body>
    </modal>
</template>

<style scoped>
    .ft13 { font-size: 1.3rem }
    .pa2 { padding: 2rem }
    .df { display: flex }
    .aic { align-items: center }
    .acc { align-content: center }
    .jcc { justify-content: center }
    .frame {
        margin-left: 2rem;
        margin-right: 2rem;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }
    .mb2 { margin-bottom: 2rem }
    .lh2 { line-height: 2 }
    .basic-text { color: #424C55 }
    .tar { text-align: right }
</style>
