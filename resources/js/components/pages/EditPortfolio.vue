<script>

import { mapActions, mapGetters } from 'vuex';
import DeletePortfolioModal from '../modals/DeletePortfolioModal.vue';
import FormErrors from '../partials/FormErrors.vue';
import validationHandler from '../../mixins/validationHandler';

export default {
  /**
   * The component's name.
   */
  name: 'EditPortfolio',

  mixins: [validationHandler],

  components: {
    FormErrors, DeletePortfolioModal,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      form: {
        name: '',
        currency: '',
        commission: '',
      },
      valid: true,
    }
  },

  watch: {
    $route() {
      this.fetchData();
    },
  },

  computed: {
    ...mapGetters([
      'portfoliosCount',
    ]),
  },

  created() {
    this.fetchData();
  },

  methods: {
    ...mapActions([
      'updatePortfolio', 'fetchPortfolio',
    ]),

    fetchData() {
      this.fetchPortfolio(this.$route.params.id)
        .then((res) => {
          this.form = res;
        })
        .catch();
    },

    /**
     * Update Portfolio.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.isLoading = true;

        this.updatePortfolio({ id: this.$route.params.id, data: this.form })
          .then(() => {
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
  },
}
</script>

<template>
  <v-row align="center" justify="center">
    <delete-portfolio-modal ref="deletePortfolioModal" />
    <v-col cols="12" sm="8" md="4">
      <v-card>
        <v-toolbar flat class="pl-2">
          <v-toolbar-title>{{ $t("Update Portfolio") }}</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form v-model="valid" ref="form" lazy-validation
            @keyup.native.enter="submit"
            @keydown.native="clearError($event.target.name)"
          >
            <form-errors :errors="errors" />
            <v-text-field type="text" name="name" ref="name" id="name" outlined autofocus
              prepend-icon="person"
              v-model="form.name"
              :disabled="isLoading"
              :label="$t('Portfolio Name')"
              :rules="[rules.required]"
              :error-messages="getError('name')"
            ></v-text-field>
            <v-select type="select" name="currency" ref="currency" id="currency" outlined
              prepend-icon="person"
              v-model="form.currency"
              :disabled="isLoading"
              :items="['TRY']"
              :label="$t('Currency')"
              :rules="[rules.required]"
              :error-messages="getError('currency')"
            ></v-select>
            <v-text-field type="number" name="commission" ref="commission" id="commission" outlined
              prepend-icon="person"
              step="0.0001"
              v-model="form.commission"
              :disabled="isLoading"
              :label="$t('Enter Commission Rate')"
              :rules="[rules.required]"
              :error-messages="getError('commission')"
              :hint="$t('For example; Garanti Bank: 0,188')"
            ></v-text-field>
          </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="pa-4">
          <v-btn class="btn-custom"
            v-if="this.portfoliosCount > 1"
            @click="$refs.deletePortfolioModal.open(form.id)"
          >
            {{ $t("Delete") }}
          </v-btn>
          <v-spacer></v-spacer>
          <v-progress-circular v-show="isLoading" indeterminate color="rgba(89, 135, 209, 1)" width="3" size="30" />
          <v-btn class="btn-custom" to="/"
            :disabled="isLoading"
          >
            {{ $t("Close") }}
          </v-btn>
          <v-btn class="btn-custom"
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
