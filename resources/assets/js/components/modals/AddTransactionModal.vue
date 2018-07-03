<script type="text/ecmascript-6">
    import _ from 'lodash';
    import Modal from '../modals/modal/Modal.vue';
    import ModalHeading from '../modals/modal/ModalHeading.vue';
    import ModalBody from '../modals/modal/ModalBody.vue';
    import ModalFooter from '../modals/modal/ModalFooter.vue';
    import FormErrors from '../partials/FormErrors.vue';
    import { VMoney } from 'v-money';

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
                showPrice: true,
                showCommission: true,
                showDividend: false,
                symbolCode: null,
                form: new Form({
                    share_id: null,
                    type: null,
                    date_at: null,
                    lot: null,
                    price: null,
                    commission: null,
                    dividend_gain: null,
                }),
                money: {
                    decimal: '.',
                    thousands: '.',
                    precision: 2,
                },
                transactions: [
                    { id: 0, label: this.$t("Buying") },
                    { id: 1, label: this.$t("Sale") },
                    { id: 2, label: this.$t("Dividend") },
                    { id: 3, label: this.$t("Bonus Share") },
                ],
                transactionRules: [
                    (v) => !!v || this.$t("Transaction is required"),
                ],
                dateAtRules: [
                    (v) => !!v || this.$t("Date is required"),
                ],
                lotRules: [
                    (v) => !!v || this.$t("Lot is required"),
                    (v) => v>0 || this.$t("Lot must be more than 0")
                ],
                priceRules: [
                    (v) => !!v || this.$t("Price is required"),
                ],
                commissionRules: [
                    (v) => !!v || this.$t("Commission is required"),
                ],
                dividendGainRules: [
                    (v) => !!v || this.$t("Dividend Gain Price is required"),
                ],
                saving: false,
            };
        },

        directives: {
            money: VMoney
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
            open(shareId, symbolCode) {
                this.form = new Form({
                    share_id: shareId,
                    type: null,
                    date_at: null,
                    lot: null,
                    price: null,
                    commission: null,
                    dividend_gain: null,
                });
                this.symbolCode = symbolCode;
                this.showModal = true;
            },

            /**
             * Close the modal and reset form elements.
             */
            close() {
                this.$refs.transactionForm.reset();
                this.showModal = false;
                this.saving = false;
                this.form.reset();
            },

            changeInput() {
                setTimeout(() => {
                    if (this.form.type == 0 || this.form.type == 1) {
                        this.showPrice = true;
                        this.showCommission = true;
                        this.showDividend = false;
                        this.form.dividend_gain = '0';
                    }
                    if (this.form.type == 2) {
                        this.showPrice = false;
                        this.showDividend = true;
                        this.showCommission = false;
                        this.form.price = '0';
                        this.form.commission = '0';
                    }
                    if (this.form.type == 3) {
                        this.showPrice = false;
                        this.showDividend = false;
                        this.showCommission = false;
                        this.form.commission = '0';
                        this.form.dividend_gain = '0';
                    }
                }, 500)
            },

            /**
             * Save the transaction and hide the modal.
             */
            saveTransaction() {
                if (this.$refs.transactionForm.validate()) {
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

            /**
             * Set the allowed dates for date time picker.
             */
            allowedDates: val => ((new Date(val)).getDay() !== 0 && (new Date(val)).getDay() !== 6 && new Date(val) <= new Date())
        },
    }
</script>

<template>
    <modal width="360" :dialog="showModal">
        <modal-heading>
            <span class="headline"><span class="red--text darken-4">{{ symbolCode }}</span> - {{ $t("Add Transaction") }}</span>
        </modal-heading>
        <v-form v-model="valid" ref="transactionForm">
            <modal-body>
                <div class="text-xs-center" v-if="saving">
                    <v-progress-circular indeterminate color="primary"></v-progress-circular>
                </div>
                <template v-else>
                    <form-errors :errors="form.errors" />
                    <v-select
                        :items="transactions"
                        item-text="label"
                        item-value="id"
                        v-model="form.type"
                        :label="$t('Select Transaction')"
                        autofocus
                        single-line
                        bottom
                        required
                        @change="changeInput()"
                    ></v-select>

                    <v-menu
                        ref="menu"
                        lazy
                        :close-on-content-click="false"
                        v-model="menu"
                        transition="scale-transition"
                        offset-y
                        full-width
                        :nudge-right="40"
                        min-width="290px"
                        :return-value.sync="form.date_at"
                    >
                        <v-text-field
                            slot="activator"
                            :label="$t('Select Date')"
                            v-model="form.date_at"
                            :rules="dateAtRules"
                            prepend-icon="event"
                            readonly
                            required
                        ></v-text-field>
                        <v-date-picker :allowed-dates="allowedDates" :first-day-of-week="1" :locale="this.$i18n.locale" v-model="form.date_at" no-title scrollable>
                            <v-spacer></v-spacer>
                            <v-btn flat color="primary" @click="menu = false">Cancel</v-btn>
                            <v-btn flat color="primary" @click="$refs.menu.save(form.date_at)">OK</v-btn>
                        </v-date-picker>
                    </v-menu>

                    <v-text-field name="lot" id="lot" type="number" step="1"
                        v-model="form.lot"
                        :label="$t('Enter Share Amount')"
                        :rules="lotRules"
                        required
                    ></v-text-field>

                    <v-text-field v-show="showPrice" name="price" id="price" type="text"
                        v-model.lazy="form.price"
                        :label="$t('Enter Share Price')"
                        :rules="priceRules"
                        v-money="money"
                        required
                    ></v-text-field>

                    <v-text-field v-show="showCommission" name="commission" id="commission" type="number" step="0.0001"
                        v-model="form.commission"
                        :label="$t('Enter Commission Rate')"
                        :rules="commissionRules"
                        :hint="$t('For example; Garanti Bank: 0,188')"
                        required
                    ></v-text-field>

                    <v-text-field v-show="showDividend" name="dividend_gain" id="dividend_gain" type="text"
                        v-model.lazy="form.dividend_gain"
                        :label="$t('Enter Dividend Gain Price')"
                        :rules="dividendGainRules"
                        v-money="money"
                        required
                    ></v-text-field>
                </template>
            </modal-body>
            <modal-footer>
                <v-spacer></v-spacer>
                <v-btn color="grey darken-1" flat @click="close">{{ $t("Close") }}</v-btn>
                <v-btn color="blue darken-1" flat @click="saveTransaction">{{ $t("Create") }}</v-btn>
            </modal-footer>
        </v-form>
    </modal>
</template>
