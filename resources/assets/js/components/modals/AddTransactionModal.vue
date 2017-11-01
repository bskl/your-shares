<script type="text/ecmascript-6">
    import _ from 'lodash';
    import Modal from '../modals/modal/Modal.vue';
    import ModalHeading from '../modals/modal/ModalHeading.vue';
    import ModalBody from '../modals/modal/ModalBody.vue';
    import ModalFooter from '../modals/modal/ModalFooter.vue';
    import FormErrors from '../partials/FormErrors.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'AddTransactionModal',

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
                menu: false,
                form: new Form({
                    share_id: '',
                    type: '',
                    date: null,
                    lot: '',
                    price: '',
                    commission: '',
                }),
                transactions: [
                    { id: 1, label: 'Buying' },
                    { id: 2, label: 'Sale' },
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
            open(shareId) {
                this.form = new Form({
                    share_id: shareId,
                    type: '',
                    date: null,
                    lot: '',
                    price: '',
                    commission: '',
                });
                this.showModal = true;
            },

            /**
             * Close the modal and reset form elements.
             */
            close() {
                this.showModal = false;
                this.saving = false;
                this.valid = true;
                this.form.reset();
            },

            /**
             * Save the transaction and hide the modal.
             */
            saveTransaction() {
                if (this.$refs.form.validate()) {
                    this.saving = true;

                    this.form.post('/transaction')
                        .then(response => {
                            Bus.$emit('transactionAdded', {
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
            <span class="headline">{{ $t("Add Transaction") }}</span>
        </modal-heading>
        <modal-body>
            <div class="text-xs-center" v-if="saving">
                <v-progress-circular indeterminate color="primary"></v-progress-circular>
            </div>
            <template v-else>
                <v-form v-model="valid" ref="form">
                    <form-errors :errors="form.errors" />
                    <v-select
                        :items="transactions"
                        item-text="label"
                        item-value="id"
                        v-model="form.type"
                        :label="$t('Select Transaction')"
                        single-line
                        bottom
                    ></v-select>

                    <v-menu
                        lazy
                        :close-on-content-click="false"
                        v-model="menu"
                        transition="scale-transition"
                        offset-y
                        full-width
                        :nudge-right="40"
                        max-width="290px"
                        min-width="290px"
                    >
                        <v-text-field
                            slot="activator"
                            :label="$t('Select Date')"
                            v-model="form.date"
                            prepend-icon="event"
                            readonly
                        ></v-text-field>
                        <v-date-picker v-model="form.date" no-title scrollable actions>
                        <template slot-scope="{ save, cancel }">
                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn flat color="primary" @click="cancel">Cancel</v-btn>
                            <v-btn flat color="primary" @click="save">OK</v-btn>
                            </v-card-actions>
                        </template>
                        </v-date-picker>
                    </v-menu>

                    <v-text-field name="lot" id="lot" type="text"
                        v-model="form.lot"
                        :label="$t('Enter Share Amount')"
                        mask="########"
                    ></v-text-field>

                    <v-text-field name="price" id="price" type="text"
                        v-model="form.price"
                        :label="$t('Enter Share Price')"
                        mask="###.###.###.###,##"
                    ></v-text-field>

                    <v-text-field name="commission" id="commission" type="text"
                        v-model="form.commission"
                        :label="$t('Enter Commission Rate')"
                        mask="#,####"
                    ></v-text-field>
                </v-form>
            </template>
        </modal-body>
        <modal-footer>
            <v-spacer></v-spacer>
            <v-btn color="grey darken-1" flat @click="close">{{ $t("Close") }}</v-btn>
            <v-btn color="blue darken-1" flat @click="saveTransaction">{{ $t("Create") }}</v-btn>
        </modal-footer>
    </modal>
</template>
