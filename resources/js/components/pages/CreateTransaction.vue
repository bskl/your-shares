<script>

import { mapActions } from 'vuex';
import { TRANSACTION_TYPES, TRANSACTION_TYPES_MAP } from '../../store/constants.js';
import { parseSuccessMessage } from '../../utilities/helpers.js';
import validationHandler from '../../mixins/validationHandler.js';
import loadingHandler from '../../mixins/loadingHandler.js';
import FormErrors from '../partials/FormErrors.vue';

export default {
  props: {
    shareId: {
      type: Number,
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
  name: 'CreateTransaction',

  mixins: [
    validationHandler,
    loadingHandler,
  ],

  components: {
    FormErrors,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      waitFor: 'store_transaction',
      form: {
        share_id: this.shareId,
        type: TRANSACTION_TYPES.Buying,
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
      return TRANSACTION_TYPES_MAP.map((item, index) => ({
        id: index,
        label: this.$t(item),
      }));
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
      'storeTransaction',
    ]),

    formatDate(date) {
      if (!date) return null

      const [year, month, day] = date.split('-')
      return `${day}.${month}.${year}`
    },

    goBack() {
      (window.history.length > 1)
        ? this.$router.go(-1)
        : this.$router.push({ name: 'Home' });
    },

    /**
     * Save the transaction.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.startLoading();

        this.storeTransaction(this.form)
          .then((res) => {
            parseSuccessMessage(res);
            this.clearErrors();
            this.goBack();
          })
          .catch((error) => {
            this.syncErrors(error);
          })
          .finally(() => {
            this.stopLoading();
          });
      } else {
        this.focusFirstErrorInput();
      }
    },

    /**
     * Set the allowed dates for date time picker.
    */
    allowedDates: val => ((new Date(val)).getDay() !== 0 && (new Date(val)).getDay() !== 6 && new Date(val) <= new Date()),

    saveToLocalStorage() {
      const storageData = {
        share_id: this.shareId,
        code: this.code,
        commission: this.commission
      }
      localStorage.setItem('transactionData', JSON.stringify(storageData));
    },

    readFromLocalStorage() {
      const storageData = JSON.parse(localStorage.getItem('transactionData'));
      this.form.share_id = storageData.share_id;
      this.form.commission = storageData.commission;
      this.symbolCode = storageData.code;
    }
  },

  created() {
    if (typeof this.shareId === 'undefined') {
      this.readFromLocalStorage();
    } else {
      this.saveToLocalStorage();
    }
  },
}
</script>

<template>
  <v-row align="center" justify="center">
    <v-col cols="12" sm="8" md="4">
      <v-card>
        <v-card-title>
          <v-toolbar-title>
            <span class="title font-weight-light">
              {{ symbolCode }}
              <v-icon small>keyboard_arrow_right</v-icon>
              {{ $t("Add Transaction") }}
            </span>
          </v-toolbar-title>
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation
            @keyup.native.enter="submit"
            @keydown.native="clearError($event.target.name)"
          >
            <form-errors :errors="errors" />
            <v-input type="hidden" name="share_id" ref="share_id" id="share_id" readonly hide-details dense
              v-model="form.share_id"
            />
            <v-select name="type" ref="type" id="type" autofocus single-line filled clearable
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
                <v-text-field name="date_at" ref="date_at" id="date_at" readonly filled clearable
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
            <v-text-field type="number" name="lot" ref="lot" id="lot" filled clearable
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
            <v-text-field type="text" name="price" ref="price" id="price" filled clearable
              v-if="this.form.type == 0 || this.form.type == 1 || this.form.type == 2"
              prepend-inner-icon="money"
              v-model.lazy="priceCurrency"
              :disabled="isLoading"
              :label="$t('Enter Transaction Price')"
              :rules="[rules.required]"
              :error-messages="getError('price')"
              v-currency
            ></v-text-field>
            <v-text-field type="number" name="commission" ref="commission" id="commission" filled clearable
              v-if="this.form.type == 0 || this.form.type == 1"
              step="0.0001"
              prepend-inner-icon="donut_large"
              v-model="form.commission"
              :disabled="isLoading"
              :label="$t('Enter Commission Rate')"
              :rules="[rules.required]"
              :error-messages="getError('commission')"
              :hint="$t('For example; Garanti Bank: 0,188')"
            ></v-text-field>
            <v-text-field type="text" name="dividend_gain" ref="dividend_gain" id="dividend_gain" filled clearable
              v-if="this.form.type == 2"
              prepend-inner-icon="money"
              v-model.lazy="dividendGainCurrency"
              :disabled="isLoading"
              :label="$t('Enter Dividend Gain Price')"
              :rules="[rules.required]"
              :error-messages="getError('dividend_gain')"
              v-currency
            ></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions class="pb-4 pr-4">
          <v-spacer></v-spacer>
          <v-progress-circular v-show="isLoading" indeterminate />
          <v-btn class="btn-close"
            :disabled="isLoading"
            @click="$router.go(-1)"
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
