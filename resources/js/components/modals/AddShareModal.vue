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
        name: 'AddShareModal',

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
                search: null,
                symbols: [],
                form: new Form({
                    symbol_id: '',
                    portfolio_id: '',
                }),
                symbolRules: [
                    (v) => !!v || this.$t("Symbol is required"),
                ],
                loading: false,
                saving: false,
            };
        },

        watch: {
            search (val) {
                val && this.searchSymbol(val)
            }
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
            open(portfolioId) {
                this.form = new Form({
                    symbol_id: '',
                    portfolio_id: portfolioId,
                });
                this.showModal = true;
            },

            /**
             * Close the modal and reset form elements.
             */
            close() {
                this.showModal = false;
                this.saving = false;
                this.symbols = [];
                this.form.reset();
                this.$refs.shareForm.reset();
            },

            /**
             * Search symbols.
             */
            searchSymbol(val) {
                this.loading = true

                setTimeout(() => {
                    axios.get('/symbol/search', {
                        params: {
                            q: val
                        }
                    }).then(response => {
                        this.symbols = response.data
                        this.loading = false
                    })
                }, 500)
            },

            /**
             * Save the share and hide the modal.
             */
            saveShare() {
                if (this.$refs.shareForm.validate()) {
                    this.saving = true;

                    this.form.post('/share')
                        .then(response => {
                            Bus.$emit('shareAdded', {
                                share: response.data
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
            <span class="headline">{{ $t("Add Symbol") }}</span>
        </modal-heading>
        <v-form v-model="valid" ref="shareForm">
            <modal-body>
                <div class="text-xs-center" v-if="saving">
                    <v-progress-circular indeterminate color="primary"></v-progress-circular>
                </div>
                <template v-else>
                    <form-errors :errors="form.errors" />
                    <v-autocomplete
                        :label="$t('Search Symbol')"
                        :no-data-text = "$t('No data available')"
                        clearable
                        :loading="loading"
                        required
                        :items="symbols"
                        item-text="code"
                        item-value="id"
                        :rules="symbolRules"
                        :search-input.sync="search"
                        v-model="form.symbol_id"
                        autofocus
                    ></v-autocomplete>
                </template>
            </modal-body>
            <modal-footer>
                <v-spacer></v-spacer>
                <v-btn color="grey darken-1" flat @click="close">{{ $t("Close") }}</v-btn>
                <v-btn color="blue darken-1" flat @click="saveShare">{{ $t("Create") }}</v-btn>
            </modal-footer>
        </v-form>
    </modal>
</template>
