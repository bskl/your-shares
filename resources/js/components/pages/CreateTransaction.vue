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
  /**
   * The component's name.
   */
  name: 'CreateTransaction',

  components: {
    FormErrors, VCurrencyField, SearchSymbolField,
  },

  mixins: [
    validationHandler,
    loadingHandler,
  ],

  props: {
    shareId: {
      type: Number,
      default: 0,
      required: false,
    },
    code: {
      type: String,
      default: '',
      required: false,
    },
    commission: {
      type: Number,
      default: 0,
      required: false,
    },
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
        preference: null,
        lot: null,
        price: 0,
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
        .map((item, index) => {
          return { id: index, label: this.$t(item) }
        })
        .filter((item, index) => index != TRANSACTION_TYPES.MergerIn);
    },
  },

  watch: {
    date: function (val) {
      this.form.date_at = this.formatDate(val)
    },
  },

  created() {
    if (typeof this.shareId === 'undefined') {
      this.readFromLocalStorage();
    } else {
      this.saveToLocalStorage();
    }
  },

  methods: {
    ...mapActions([
      'storeTransaction',
    ]),

    formatDate(date) {
      if (!date) return null;

      const [year, month, day] = date.split('-');

      return `${day}.${month}.${year}`;
    },

    onChangeType(type) {
      switch (type) {
        case TRANSACTION_TYPES.Bonus:
        case TRANSACTION_TYPES.Rights:
          this.form.preference = this.getShareById(this.form.share_id).lot;
          break;

        case TRANSACTION_TYPES.Dividend:
        case TRANSACTION_TYPES.MergerOut:
          this.form.lot = this.getShareById(this.form.share_id).lot;
          break;

        case TRANSACTION_TYPES.PublicOffering:
          this.form.commission = 0;
          break;
      }
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
}
</script>

<template>
  <v-row
    align="center"
    justify="center"
  >
    <v-col
      cols="12"
      sm="8"
      md="4"
    >
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
          <v-form
            ref="form"
            v-model="valid"
            lazy-validation
            @keyup.native.enter="submit"
            @keydown.native="clearError($event.target.name)"
          >
            <form-errors :errors="errors" />
            <v-input
              id="share_id"
              ref="share_id"
              v-model="form.share_id"
              type="hidden"
              name="share_id"
              readonly
              hide-details
              dense
            />
            <v-select
              id="type"
              ref="type"
              v-model="form.type"
              name="type"
              autofocus
              single-line
              filled
              clearable
              prepend-inner-icon="format_list_bulleted"
              :disabled="isLoading"
              :items="transactionTypes"
              :label="$t('Select Transaction')"
              :rules="[rules.required]"
              :error-messages="getError('type')"
              item-text="label"
              item-value="id"
              menu-props="bottom"
              @change="onChangeType"
            />
            <v-menu
              ref="menu"
              v-model="menu"
              :close-on-content-click="false"
              transition="scale-transition"
              offset-y
              :nudge-right="40"
              min-width="auto"
            >
              <template #activator="{ on, attrs }">
                <v-text-field
                  id="date_at"
                  ref="date_at"
                  v-model="form.date_at"
                  name="date_at"
                  readonly
                  filled
                  clearable
                  prepend-inner-icon="calendar_today"
                  :disabled="isLoading"
                  :label="$t('Select Date')"
                  :rules="[rules.required]"
                  :error-messages="getError('date_at')"
                  v-bind="attrs"
                  v-on="on"
                />
              </template>
              <v-date-picker
                v-model="date"
                no-title
                scrollable
                elevation="15"
                :allowed-dates="allowedDates"
                :first-day-of-week="1"
                @input="menu = false"
              />
            </v-menu>
            <search-symbol-field
              v-if="form.type == 5"
              :symbol-id.sync="form.symbol_id"
            />
            <v-text-field
              v-if="form.type == 3 || form.type == 4"
              id="preference"
              ref="preference"
              v-model="form.preference"
              type="number"
              name="preference"
              filled
              clearable
              prepend-inner-icon="format_list_numbered"
              :disabled="isLoading"
              :label="$t('Enter Preference Amount')"
              :rules="[rules.required]"
              :error-messages="getError('preference')"
              :hint="form.type == 3
                ? $t('You must write your share amount on the day of bonus.')
                : $t('You must write your share amount on the day of rights.')"
            />
            <v-text-field
              id="lot"
              ref="lot"
              v-model="form.lot"
              type="number"
              name="lot"
              filled
              clearable
              prepend-inner-icon="format_list_numbered"
              :disabled="isLoading"
              :label="$t('Enter Share Amount')"
              :rules="[rules.required]"
              :error-messages="getError('lot')"
              :readonly="form.type == 5"
              :hint="
                form.type == 3 ? $t('You must write your bonus shares.') :
                form.type == 4 ? $t('You must write your rights shares.') : ''"
            />
            <v-currency-field
              v-if="form.type != 3 || form.type != 4 || form.type != 6"
              ref="price"
              v-model="form.price"
              name="price"
              :label="$t('Enter Transaction Price')"
              :is-loading="isLoading"
            />
            <v-text-field
              v-if="form.type == 5"
              id="exchange_ratio"
              ref="exchange_ratio"
              v-model="form.exchange_ratio"
              type="number"
              name="exchange_ratio"
              filled
              clearable
              prepend-inner-icon="donut_large"
              :disabled="isLoading"
              :label="$t('Enter Exchange Ratio')"
              :rules="[rules.required]"
              :error-messages="getError('exchange_ratio')"
              :hint="$t('for_example', { example: '1,15997' })"
            />
            <v-text-field
              v-if="form.type == 0 || form.type == 1 || form.type == 7"
              id="commission"
              ref="commission"
              v-model="form.commission"
              type="number"
              name="commission"
              filled
              clearable
              step="0.0001"
              prepend-inner-icon="donut_large"
              :disabled="isLoading"
              :label="$t('Enter Commission Rate')"
              :rules="[rules.required]"
              :error-messages="getError('commission')"
              :hint="$t('for_example', { example: $t('Garanti Bank: 0.188') })"
            />
            <v-currency-field
              v-if="form.type == 2"
              ref="dividend_gain"
              v-model="form.dividend_gain"
              name="dividend_gain"
              :label="$t('Enter Dividend Gain Price')"
              :is-loading="isLoading"
            />
          </v-form>
        </v-card-text>
        <v-card-actions class="pb-4 pr-4">
          <v-spacer />
          <v-progress-circular
            v-show="isLoading"
            indeterminate
          />
          <v-btn
            class="btn-close"
            :disabled="isLoading"
            @click="$router.go(-1)"
          >
            {{ $t("Close") }}
          </v-btn>
          <v-btn
            class="btn-action"
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
