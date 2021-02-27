<script>

import { mapGetters, mapActions } from 'vuex';
import { TRANSACTION_TYPES, TRANSACTION_TYPES_MAP } from '../../store/constants.js';
import { parseSuccessMessage } from '../../utilities/helpers.js';
import validationHandler from '../../mixins/validationHandler.js';
import loadingHandler from '../../mixins/loadingHandler.js';
import FormErrors from '../partials/FormErrors.vue';
import VCurrencyField from '../partials/VCurrencyField.vue';
import SearchSymbolField from '../partials/SearchSymbolField.vue';

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
    FormErrors, VCurrencyField, SearchSymbolField,
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
        symbol_id: 0,
        lot: null,
        price: null,
        exchange_ratio: 0,
        commission: this.commission,
        dividend_gain: null,
      },
      date: null,
      valid: true,
      menu: false,
      symbolCode: this.code,
    };
  },

  computed: {
    ...mapGetters([
      'getShareById',
    ]),

    transactionTypes() {
      return TRANSACTION_TYPES_MAP
        .filter((item, index) => index != TRANSACTION_TYPES.MergerIn)
        .map((item, index) => {
          return { id: index, label: this.$t(item) }
        });
    },
  },

  watch: {
    date: function (val) {
      this.form.date_at = this.formatDate(val)
    },
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

    onChangeType(type) {
      this.form.lot = (type == TRANSACTION_TYPES.MergerOut)
        ? this.getShareById(this.form.share_id).lot
        : null;
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
              @change="onChangeType"
              item-text="label"
              item-value="id"
              menu-props="bottom"
            ></v-select>
            <v-menu ref="menu" offset-y min-width="auto" transition="scale-transition"
              v-model="menu"
              :close-on-content-click="false"
              :nudge-right="40"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field name="date_at" ref="date_at" id="date_at" readonly filled clearable
                  prepend-inner-icon="calendar_today"
                  v-model="form.date_at"
                  :disabled="isLoading"
                  :label="$t('Select Date')"
                  :rules="[rules.required]"
                  :error-messages="getError('date_at')"
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker no-title scrollable elevation="15"
                v-model="date"
                :allowed-dates="allowedDates"
                :first-day-of-week="1"
                @input="menu = false"
              >
              </v-date-picker>
            </v-menu>
            <search-symbol-field v-if="form.type == 5" :symbolId.sync="form.symbol_id"></search-symbol-field>
            <v-text-field type="number" name="lot" ref="lot" id="lot" filled clearable
              prepend-inner-icon="format_list_numbered"
              v-model="form.lot"
              :disabled="isLoading"
              :label="$t('Enter Share Amount')"
              :rules="[rules.required]"
              :error-messages="getError('lot')"
              :readonly="form.type == 5"
              :hint="
                form.type == 3 ? $t('You must write your bonus shares.') :
                form.type == 4 ? $t('You must write your rights shares.') : ''"
            ></v-text-field>
            <v-currency-field v-if="form.type == 0 || form.type == 1 || form.type == 2" name="price" v-model="form.price"></v-currency-field>
            <v-text-field type="number" name="exchange_ratio" ref="exchange_ratio" id="exchange_ratio" filled clearable
              v-if="form.type == 5"
              prepend-inner-icon="donut_large"
              v-model="form.exchange_ratio"
              :disabled="isLoading"
              :label="$t('Enter Exchange Ratio')"
              :rules="[rules.required]"
              :error-messages="getError('exchange_ratio')"
              :hint="$t('for_example', { example: '1,15997' })"
            ></v-text-field>
            <v-text-field type="number" name="commission" ref="commission" id="commission" filled clearable
              v-if="form.type == 0 || form.type == 1"
              step="0.0001"
              prepend-inner-icon="donut_large"
              v-model="form.commission"
              :disabled="isLoading"
              :label="$t('Enter Commission Rate')"
              :rules="[rules.required]"
              :error-messages="getError('commission')"
              :hint="$t('for_example', { example: 'Garanti Bank: 0,188' })"
            ></v-text-field>
            <v-currency-field v-if="form.type == 2" name="dividend_gain" v-model="form.dividend_gain"></v-currency-field>
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
