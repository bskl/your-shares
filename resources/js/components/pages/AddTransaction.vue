<script>

import { mapActions } from 'vuex';
import { TRANSACTION_TYPES } from '../../store/constants.js';
import validationHandler from '../../mixins/validationHandler';
import FormErrors from '../partials/FormErrors.vue';

export default {
  props: {
    id: {
      type: [Number, String],
      required: false,
    },
    code: {
      type: String,
      required: false,
    },
    commission: {
      type: Number,
      required: false,
    },
  },

  /**
   * The component's name.
   */
  name: 'AddTransaction',

  mixins: [validationHandler],

  components: {
    FormErrors,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      form: {
        share_id: this.id,
        type: 0,
        date_at: null,
        lot: null,
        price: null,
        commission: this.commission,
        dividend_gain: null,
      },
      date: null,
      valid: true,
      menu: false,
      symbolCode: this.code,
      priceCurrency: null,
      dividendGainCurrency: null
    };
  },

  computed: {
    transactionTypes() {
      return TRANSACTION_TYPES.map((item, index) => ({
        id: index,
        label: this.$t(item),
      }))
    },
  },

  watch: {
    date: function (val) {
      this.form.date_at = this.formatDate(val)
    },
    priceCurrency: function (val) {
      this.form.price = this.$parseCurrency(val)
    },
    dividendGainCurrency: function (val) {
      this.form.dividend_gain = this.$parseCurrency(val)
    }
  },

  methods: {
    ...mapActions([
      'createTransaction',
    ]),

    formatDate (date) {
      if (!date) return null

      const [year, month, day] = date.split('-')
      return `${day}.${month}.${year}`
    },

    /**
     * Save the transaction.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.isLoading = true;

        this.createTransaction(this.form)
          .then((res) => {
            this.clearErrors();
            this.$router.push({ name: 'Home' });
          })
          .catch((error) => {
            this.syncErrors(error);
          })
          .finally(() => {
            this.isLoading = false;
          });
      } else {
        this.focusFirstErrorInput();
      }
    },

    /**
     * Set the allowed dates for date time picker.
    */
    allowedDates: val => ((new Date(val)).getDay() !== 0 && (new Date(val)).getDay() !== 6 && new Date(val) <= new Date())
  },

  created() {
    if (this.code === undefined || this.commission === undefined) {
      const storageData = JSON.parse(localStorage.getItem('transactionData'));
      this.symbolCode = storageData.code;
      this.form.commission = storageData.commission;
    } else {
      localStorage.setItem('transactionData', JSON.stringify({
        code: this.code,
        commission: this.commission
      }));
    }
  },
}
</script>

<template>
  <v-row align="center" justify="center">
    <v-col cols="12" sm="8" md="4">
      <v-card>
        <v-toolbar flat class="pl-2">
          <v-toolbar-title>
            {{ symbolCode }}
            <v-icon small>keyboard_arrow_right</v-icon>
            {{ $t("Add Transaction") }}
          </v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation
            @keyup.native.enter="submit"
            @keydown.native="clearError($event.target.name)"
          >
            <form-errors :errors="errors" />
            <v-input type="hidden" name="share_id" ref="share_id" id="share_id" readonly hide-details dense
              v-model="form.share_id"
            />
            <v-select name="type" ref="type" id="type" autofocus single-line outlined clearable
              prepend-inner-icon="format_list_bulleted"
              v-model="form.type"
              :disabled="isLoading"
              :items="transactionTypes"
              :label="$t('Select Transaction')"
              :rules="[rules.required]"
              :error-messages="getError('type')"
              item-text="label"
              item-value="id"
              menu-props="bottom"
            ></v-select>
            <v-menu ref="menu" offset-y min-width="290px" transition="scale-transition"
              v-model="menu"
              :close-on-content-click="false"
              :nudge-right="40"
              :return-value.sync="form.date_at"
            >
              <template v-slot:activator="{ on }">
                <v-text-field name="date_at" ref="date_at" id="date_at" readonly outlined clearable
                  prepend-inner-icon="calendar_today"
                  v-model="form.date_at"
                  :disabled="isLoading"
                  :label="$t('Select Date')"
                  :rules="[rules.required]"
                  :error-messages="getError('date_at')"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker no-title scrollable
                v-model="date"
                :allowed-dates="allowedDates"
                :first-day-of-week="1"
              >
              <v-spacer></v-spacer>
                <v-btn class="btn-close" @click="menu = false">{{ $t("Close") }}</v-btn>
                <v-btn class="btn-action" @click="$refs.menu.save(form.date_at)">{{ $t("Ok") }}</v-btn>
              </v-date-picker>
            </v-menu>
            <v-text-field type="number" name="lot" ref="lot" id="lot" outlined clearable
              step="1"
              prepend-inner-icon="format_list_numbered"
              v-model="form.lot"
              :disabled="isLoading"
              :label="$t('Enter Share Amount')"
              :rules="[rules.required]"
              :error-messages="getError('lot')"
              :hint="
                this.form.type == 3 ? $t('You must write your bonus shares.') : 
                this.form.type == 4 ? $t('You must write your rights shares.') : ''"
            ></v-text-field>
            <v-text-field type="text" name="price" ref="price" id="price" outlined clearable
              v-if="this.form.type == 0 || this.form.type == 1"
              prepend-inner-icon="money"
              v-model.lazy="priceCurrency"
              :label="$t('Enter Share Price')"
              :rules="[rules.required]"
              :error-messages="getError('price')"
              v-currency
            ></v-text-field>
            <v-text-field type="number" name="commission" ref="commission" id="commission" outlined clearable
              v-if="this.form.type == 0 || this.form.type == 1"
              step="0.0001"
              prepend-inner-icon="donut_large"
              v-model="form.commission"
              :label="$t('Enter Commission Rate')"
              :rules="[rules.required]"
              :error-messages="getError('commission')"
              :hint="$t('For example; Garanti Bank: 0,188')"
            ></v-text-field>
            <v-text-field type="text" name="dividend_gain" ref="dividend_gain" id="dividend_gain" outlined clearable
              v-if="this.form.type == 2"
              prepend-inner-icon="money"
              v-model.lazy="dividendGainCurrency"
              :label="$t('Enter Dividend Gain Price')"
              :rules="[rules.required]"
              :error-messages="getError('dividend_gain')"
              v-currency
            ></v-text-field>
          </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="pa-4">
          <v-spacer></v-spacer>
          <v-progress-circular v-show="isLoading" indeterminate color="rgba(89, 135, 209, 1)" width="3" size="30" />
          <v-btn class="btn-close" to="/"
            :disabled="isLoading"
          >
            {{ $t("Close") }}
          </v-btn>
          <v-btn class="btn-action"
            :disabled="isLoading" 
            @click="submit"
          >
            {{ $t("Create") }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>
