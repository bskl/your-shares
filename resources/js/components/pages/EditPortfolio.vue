<script>

import { mapActions, mapGetters } from 'vuex';
import { parseSuccessMessage } from '../../utilities/helpers.js';
import validationHandler from '../../mixins/validationHandler.js';
import loadingHandler from '../../mixins/loadingHandler.js';
import DeletePortfolioModal from '../modals/DeletePortfolioModal.vue';
import FormErrors from '../partials/FormErrors.vue';

export default {
  /**
   * The component's name.
   */
  name: 'EditPortfolio',

  components: {
    FormErrors, DeletePortfolioModal,
  },

  mixins: [
    validationHandler,
    loadingHandler
  ],

  props: {
    portfolio: {
      type: Object,
      required: true,
    },
  },

  /**
   * The component's data.
   */
  data() {
    return {
      waitFor: 'update_portfolio',
      form: {
        name: this.portfolio.name,
        currency: this.portfolio.currency,
        commission: this.portfolio.commission,
        filtered: this.portfolio.filtered,
      },
      valid: true,
    }
  },

  computed: {
    ...mapGetters([
      'portfoliosCount',
    ]),
  },

  methods: {
    ...mapActions([
      'updatePortfolio',
    ]),

    /**
     * Update Portfolio.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.startLoading();

        this.updatePortfolio({ id: this.$route.params.id, form: this.form })
          .then((res) => {
            this.clearErrors();
            parseSuccessMessage(this.$t('Your portfolio is successfully updated.'));
            this.$router.push({ name: 'Home' });
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
  },
}
</script>

<template>
  <v-row
    align="center"
    justify="center"
  >
    <delete-portfolio-modal ref="deletePortfolioModal" />
    <v-col
      cols="12"
      sm="8"
      md="4"
    >
      <v-card>
        <v-card-title>
          <span class="title font-weight-light">{{ $t("Update Portfolio") }}</span>
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
            <v-text-field
              id="name"
              ref="name"
              v-model="form.name"
              type="text"
              name="name"
              filled
              autofocus
              prepend-inner-icon="text_fields"
              :disabled="isLoading"
              :label="$t('Portfolio Name')"
              :rules="[rules.required]"
              :error-messages="getError('name')"
            />
            <v-select
              id="currency"
              ref="currency"
              v-model="form.currency"
              type="select"
              name="currency"
              filled
              prepend-inner-icon="money"
              :disabled="isLoading"
              :items="['TRY']"
              :label="$t('Currency')"
              :rules="[rules.required]"
              :error-messages="getError('currency')"
            />
            <v-text-field
              id="commission"
              ref="commission"
              v-model="form.commission"
              type="number"
              name="commission"
              filled
              prepend-inner-icon="donut_large"
              step="0.0001"
              :disabled="isLoading"
              :label="$t('Enter Commission Rate')"
              :rules="[rules.required]"
              :error-messages="getError('commission')"
              :hint="$t('for_example', { example: $t('Garanti Bank: 0.188') })"
            />
            <v-checkbox
              v-model="form.filtered"
              :label="$t('Filter stocks that are no lot')"
            />
          </v-form>
        </v-card-text>
        <v-card-actions class="pb-4 px-4">
          <v-btn
            v-if="portfoliosCount > 1"
            class="btn-warning"
            @click="$refs.deletePortfolioModal.open($route.params.id)"
          >
            {{ $t("Delete") }}
          </v-btn>
          <v-spacer />
          <v-progress-circular
            v-show="isLoading"
            indeterminate
          />
          <v-btn
            class="btn-close"
            to="/"
            :disabled="isLoading"
          >
            {{ $t("Close") }}
          </v-btn>
          <v-btn
            class="btn-action"
            :disabled="isLoading"
            @click="submit"
          >
            {{ $t("Update") }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>
