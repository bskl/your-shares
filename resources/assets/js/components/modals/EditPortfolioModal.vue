<script type="text/ecmascript-6">
    import Modal from '../modals/modal/Modal.vue';
    import ModalHeading from '../modals/modal/ModalHeading.vue';
    import ModalBody from '../modals/modal/ModalBody.vue';
    import ModalFooter from '../modals/modal/ModalFooter.vue';
    import FormErrors from '../partials/FormErrors.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'EditPortfolioModal',

        components: {
            Modal, ModalHeading, ModalBody, ModalFooter, FormErrors,
        },

        /**
         * The component's data.
         */
        data() {
            return {
                showModal: false,
                valid: true,
                form: new Form({
                    id: 0,
                    user_id: 0,
                    name: '',
                    currency: '',
                    order: 0,
                }),
                nameRules: [
                    (v) => !!v || this.$t("Name is required"),
                ],
                currencyRules: [
                    (v) => !!v || this.$t("Currency is required"),
                ],
                saving: false,
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
                this.$refs.portfolioForm.reset()
            },

            /**
             * Save the portfolio and hide the modal.
             */
            updatePortfolio() {
                if (this.$refs.portfolioForm.validate()) {
                    this.saving = true;

                    this.form.put('/portfolio/' + this.form.id)
                        .then(response => {
                            Bus.$emit('portfolioUpdated', {
                                portfolio: response.data
                            });

                            this.close();
                        }, error => {
                            this.saving = false;
                        });
                }
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
    <modal width="360" :dialog="showModal">
        <modal-heading>
            <span class="headline">{{ $t("Update Portfolio") }}</span>
        </modal-heading>
        <v-form v-model="valid" ref="portfolioForm">
            <modal-body>
                <div class="text-xs-center" v-if="saving">
                    <v-progress-circular indeterminate color="primary"></v-progress-circular>
                </div>
                <template v-else>
                        <form-errors :errors="form.errors" />
                        <v-text-field name="name" id="name" type="text"
                            v-model="form.name"
                            :label="$t('Portfolio Name')"
                            :rules="nameRules"
                            required
                        ></v-text-field>
                        <v-select name="currency" id="currency" type="select"
                            v-model="form.currency"
                            :items="['TRY']"
                            :label="$t('Currency')"
                            :rules="currencyRules"
                            required
                        ></v-select>
                </template>
            </modal-body>
            <modal-footer>
                <v-btn color="red darken-1" flat @click="destroyPortfolio">{{ $t("Delete Portfolio") }}</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="grey darken-1" flat @click="close">{{ $t("Close") }}</v-btn>
                <v-btn color="blue darken-1" flat @click="updatePortfolio">{{ $t("Update") }}</v-btn>
            </modal-footer>
        </v-form>
    </modal>
</template>
