<script type="text/ecmascript-6">

import _ from 'lodash';
import FormErrors from '../partials/FormErrors.vue';
import { VMoney } from 'v-money';
import { mapGetters, mapActions } from 'vuex';

export default {
    /*
     * The component's name.
     */
    name: 'AddTransaction',

    components: {
        FormErrors, VMoney,
    },

    /**
     * The component's data.
     */
    data() {
        return {
            isLoading: false,
            form: new Form({
                share_id: null,
                type: null,
                date_at: null,
                lot: null,
                price: null,
                commission: null,
                dividend_gain: null,
            }),
            valid: true,
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
            menu: false,
            symbolCode: null,
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
        };
    },

    computed: {
        ...mapGetters([
            'getShareByPortfolio', 'getPortfolioById',
        ]),
    },

    directives: {
        money: VMoney
    },

    methods: {
        ...mapActions([
            'createTransaction',
        ]),

        changeInput() {
            setTimeout(() => {
                this.form.price = '0';
                this.form.commission = '0';
                this.form.dividend_gain = '0';
                this.$refs.form.resetValidation();
            }, 500)
        },

        /**
         * Save the transaction.
         */
        submit() {
            if (this.$refs.form.validate()) {
                this.isLoading = true;

                this.createTransaction(this.form)
                    .then(() => {
                        this.isLoading = false;
                        this.$router.replace('/');
                    })
                    .catch((error) => {
                        this.$refs.form.resetValidation();
                        this.isLoading = false;
                        this.form.onFail(error.response.data)
                    });
            }
        },

        /**
         * Set the allowed dates for date time picker.
         */
        allowedDates: val => ((new Date(val)).getDay() !== 0 && (new Date(val)).getDay() !== 6 && new Date(val) <= new Date())
    },

    mounted() {
        const { portfolioId, shareId } = this.$route.params;

        if (portfolioId && shareId) {
            this.form.share_id = shareId;
                this.symbolCode = this.getShareByPortfolio(portfolioId, shareId).symbol.code;
                this.form.commission = this.getPortfolioById(portfolioId).commission;
        }
    },
}
</script>

<template>
  <v-layout row wrap justify-center>
    <v-flex xs12 sm6 md4>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card>
            <v-form ref="form" v-model="valid" lazy-validation @keyup.native.enter="submit">
              <v-card-title>
                <div class="headline mb-0">
                    <span class="red--text darken-4">{{ symbolCode }}</span>
                    {{ $t("Add Transaction") }}
                </div>
              </v-card-title>
              <v-card-text>
                <form-errors :errors="form.errors" />
                <v-select
                    :items="transactions"
                    item-text="label"
                    item-value="id"
                    v-model="form.type"
                    :label="$t('Select Transaction')"
                    autofocus
                    single-line
                    menu-props="bottom"
                    required
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
                    <v-date-picker
                        :allowed-dates="allowedDates"
                        :first-day-of-week="1"
                        :locale="this.$i18n.locale"
                        v-model="form.date_at"
                        no-title
                        scrollable
                    >
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="menu = false">{{ $t('Close') }}</v-btn>
                        <v-btn flat color="primary" @click="$refs.menu.save(form.date_at)">{{ $t('Ok') }}</v-btn>
                    </v-date-picker>
                </v-menu>

                <v-text-field
                    name="lot"
                    id="lot"
                    type="number"
                    step="1"
                    v-model="form.lot"
                    :label="$t('Enter Share Amount')"
                    :rules="lotRules"
                    required
                ></v-text-field>

                <v-text-field
                    v-show="this.form.type == 0 || this.form.type == 1"
                    name="price"
                    id="price"
                    type="text"
                    v-model.lazy="form.price"
                    :label="$t('Enter Share Price')"
                    :rules="priceRules"
                    v-money="money"
                    required
                ></v-text-field>

                <v-text-field
                    v-show="this.form.type == 0 || this.form.type == 1"
                    name="commission"
                    id="commission"
                    type="number"
                    step="0.0001"
                    v-model="form.commission"
                    :label="$t('Enter Commission Rate')"
                    :rules="commissionRules"
                    :hint="$t('For example; Garanti Bank: 0,188')"
                    required
                ></v-text-field>

                <v-text-field
                    v-show="this.form.type == 2"
                    name="dividend_gain"
                    id="dividend_gain"
                    type="text"
                    v-model.lazy="form.dividend_gain"
                    :label="$t('Enter Dividend Gain Price')"
                    :rules="dividendGainRules"
                    v-money="money"
                    required
                ></v-text-field>
                </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn :to="'/'">
                    {{ $t("Close") }}
                </v-btn>
                <v-btn color="primary" :loading="isLoading" @click="submit">
                    {{ $t("Create") }}
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>
