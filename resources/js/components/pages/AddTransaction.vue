<script>

import FormErrors from '../partials/FormErrors.vue';
import { VMoney } from 'v-money';
import { mapActions } from 'vuex';
import { TRANSACTION_TYPES } from '../../store/constants.js';

export default {
  /**
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
        type: 0,
        date_at: null,
        lot: null,
        price: null,
        commission: null,
        dividend_gain: null,
      }),
      valid: true,
      money: {
        decimal: ',',
        thousands: '.',
        precision: 2,
      },
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

  watch: {
    $route() {
      this.fetchData();
    },
  },

  computed: {
    transactionTypes() {
      return TRANSACTION_TYPES.map((item, index) => ({
        id: index,
        label: this.$t(item),
      }))
    },
  },

  created() {
    this.fetchData();
  },

  directives: {
    money: VMoney
  },

  methods: {
    ...mapActions([
      'createTransaction', 'fetchPortfolio', 'fetchShare',
    ]),

    fetchData() {
      this.fetchPortfolio(this.$route.params.portfolioId)
        .then((res) => {
          this.form.share_id = this.$route.params.shareId;
          this.form.commission = res.commission;

          this.fetchShare(this.$route.params.shareId)
            .then((res) => {
              this.symbolCode = res.code;
            })
        })
        .catch((error) => {
          if (error.response.status == 404) {
            this.$router.push({ name: 'NotFound' });
          }
        });
    },

    /**
     * Save the transaction.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.isLoading = true;

        this.createTransaction(this.form)
          .then((res) => {
            this.$router.push({ name: 'Home' });
          })
          .catch((error) => {
            if (error.response.status == 404) {
              this.$router.push({ name: 'NotFound' });
            }
            this.form.onFail(error.response.data);
          })
          .finally(() => {
            this.isLoading = false
            this.$refs.form.reset();
            this.$refs.form.resetValidation();
          });
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
  <v-layout row wrap justify-center>
    <v-flex xs12 sm6 md4>
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
            <v-select autofocus single-line required
              :items="transactionTypes"
              item-text="label"
              item-value="id"
              v-model="form.type"
              :label="$t('Select Transaction')"
              menu-props="bottom"
            ></v-select>
            <v-menu lazy offset-y full-width min-width="290px" transition="scale-transition"
              ref="menu"
              :close-on-content-click="false"
              v-model="menu"
              :nudge-right="40"
              :return-value.sync="form.date_at"
            >
              <v-text-field prepend-icon="event" readonly required
                slot="activator"
                :label="$t('Select Date')"
                v-model="form.date_at"
                :rules="dateAtRules"
              ></v-text-field>
              <v-date-picker no-title scrollable
                :allowed-dates="allowedDates"
                :first-day-of-week="1"
                :locale="this.$i18n.locale"
                v-model="form.date_at"
              >
                <v-spacer></v-spacer>
                <v-btn flat color="primary" @click="menu = false">{{ $t('Close') }}</v-btn>
                <v-btn flat color="primary" @click="$refs.menu.save(form.date_at)">{{ $t('Ok') }}</v-btn>
              </v-date-picker>
            </v-menu>
            <v-text-field type="number" name="lot" id="lot" required
              step="1"
              v-model="form.lot"
              :label="$t('Enter Share Amount')"
              :rules="lotRules"
            ></v-text-field>
            <v-text-field type="text" name="price" id="price" required
              v-show="this.form.type == 0 || this.form.type == 1"
              v-model.lazy="form.price"
              :label="$t('Enter Share Price')"
              :rules="priceRules"
              v-money="money"
            ></v-text-field>
            <v-text-field type="number" name="commission" id="commission" required
              v-show="this.form.type == 0 || this.form.type == 1"
              step="0.0001"
              v-model="form.commission"
              :label="$t('Enter Commission Rate')"
              :rules="commissionRules"
              :hint="$t('For example; Garanti Bank: 0,188')"
            ></v-text-field>
            <v-text-field type="text" name="dividend_gain" id="dividend_gain" required
              v-show="this.form.type == 2"
              v-model.lazy="form.dividend_gain"
              :label="$t('Enter Dividend Gain Price')"
              :rules="dividendGainRules"
              v-money="money"
            ></v-text-field>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn :to="'/'">{{ $t("Close") }}</v-btn>
            <v-btn color="primary" :loading="isLoading" @click="submit">{{ $t("Create") }}</v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-flex>
  </v-layout>
</template>
