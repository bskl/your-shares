<script type="text/ecmascript-6">
    import Modal from '../modals/modal/Modal.vue';
    import ModalHeading from '../modals/modal/ModalHeading.vue';
    import ModalBody from '../modals/modal/ModalBody.vue';
    import Spinner from '../loaders/Spinner.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'AddPortfolioModal',

        components: {
            Modal, ModalHeading, ModalBody, Spinner,
        },

        /**
         * The component's data.
         */
        data() {
            return {
                form: new Form({
                    name: '',
                    currency: '',
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
             * Open the model.
             */
            open() {
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
            savePortfolio() {
                this.saving = true;

                this.form.post('/portfolio')
                    .then(response => {
                        Bus.$emit('portfolioAdded', {
                            portfolio: response.data
                        });

                        this.close();
                    }, error => {
                        this.saving = false;
                    })

            },
        },
    }
</script>

<template>
    <modal width="360" v-bind:show-modal="this.showModal">
        <modal-heading>
            <span class="ft13">{{ $t("Add Portfolio") }}</span>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </modal-heading>
        <modal-body>
            <div v-if="saving">
                <spinner />
            </div>
            <div v-else>
                <form class="form-horizontal" role="form" method="POST"
                        v-on:submit.prevent="savePortfolio" v-on:keydown="form.errors.clear($event.target.name)">
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
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block"
                                    :disabled="form.errors.any()">{{ $t("Create") }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </modal-body>
    </modal>
</template>
