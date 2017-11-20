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
        name: 'AddPortfolioModal',

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
                    name: '',
                    currency: '',
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
                this.$refs.form.reset()
            },

            /**
             * Save the portfolio and hide the modal.
             */
            savePortfolio() {
                if (this.$refs.form.validate()) {
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
                }
            },
        },
    }
</script>

<template>
    <modal width="360" :dialog="showModal">
        <modal-heading>
            <span class="headline">{{ $t("Add Portfolio") }}</span>
        </modal-heading>
        <modal-body>
            <div class="text-xs-center" v-if="saving">
                <v-progress-circular indeterminate color="primary"></v-progress-circular>
            </div>
            <template v-else>
                <v-form v-model="valid" ref="form">
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
                </v-form>
            </template>
        </modal-body>
        <modal-footer>
            <v-spacer></v-spacer>
            <v-btn color="grey darken-1" flat @click="close">{{ $t("Close") }}</v-btn>
            <v-btn color="blue darken-1" flat @click="savePortfolio">{{ $t("Create") }}</v-btn>
        </modal-footer>
    </modal>
</template>
