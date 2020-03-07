<script>

import { mapActions } from 'vuex';
import Modal from '../modals/modal/Modal.vue';
import ModalHeading from '../modals/modal/ModalHeading.vue';
import ModalBody from '../modals/modal/ModalBody.vue';
import ModalFooter from '../modals/modal/ModalFooter.vue';
import FormErrors from '../partials/FormErrors.vue';
import validationHandler from '../../mixins/validationHandler';

export default {
  /**
   * The component's name.
   */
  name: 'AddShareModal',

  mixins: [validationHandler],

  components: {
    Modal, ModalHeading, ModalBody, ModalFooter, FormErrors,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      searching: false,
      showModal: false,
      valid: true,
      search: null,
      symbols: [],
      form: {
        symbol_id: 0,
        portfolio_id: 0,
      },
    };
  },

  watch: {
    search (val) {
      if (val == null || this.symbols.length > 0 || this.isLoading) return

      this.searching = true;

      this.fetchSymbols()
        .then((res) => {
          this.symbols = res.data;
          this.searching = false
        });
    }
  },

  mounted() {
    document.addEventListener("keydown", (e) => {
      if (e.keyCode == 27) {
        this.close();
      }
    });
  },

  methods: {
    ...mapActions([
      'fetchSymbols', 'addShare',
    ]),

    /**
     * Open the model.
     */
    open(portfolioId) {
      this.form.portfolio_id = portfolioId;
      this.showModal = true;
    },

    /**
     * Close the modal and reset form elements.
     */
    close() {
      this.searching = false;
      this.clearErrors();
      this.$refs.form.reset();
      this.showModal = false;
    },

    /**
     * Add the share and hide the modal.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.isLoading = true;

        this.addShare(this.form)
          .then(() => {
            this.close();
          })
          .catch((error) => {
            this.syncErrors(error);
          })
          .finally(() => {
            this.isLoading = false
          });
      } else {
        this.focusFirstErrorInput();
      }
    },
  },
}
</script>

<template>
  <modal :width="460" :dialog="showModal">
    <modal-heading>
      <v-toolbar-title>{{ $t("Add Symbol") }}</v-toolbar-title>
    </modal-heading>
    <modal-body>
      <v-form ref="form" v-model="valid" lazy-validation
        @keyup.native.enter="submit"
        @keydown.native="clearError($event.target.name)"
      >
        <form-errors :errors="errors" />
        <v-autocomplete name="symbol_id" ref="symbol_id" id="symbol_id" outlined clearable
          v-model="form.symbol_id"
          :items="symbols"
          :loading="searching"
          :search-input.sync="search"
          :rules="[rules.required]"
          :label="$t('Search Symbol')"
          :no-data-text="$t('No data available')"
          :error-messages="getError('symbol_id')"
          :disabled="isLoading"
          item-text="code"
          item-value="id"
        />
      </v-form>
    </modal-body>
    <v-divider></v-divider>
    <modal-footer>
      <v-spacer></v-spacer>
      <v-progress-circular v-show="isLoading" indeterminate color="rgba(89, 135, 209, 1)" width="3" size="30" />
      <v-btn class="btn-custom"
        :disabled="isLoading"
        @click="close"
      >
        {{ $t("Close") }}
      </v-btn>
      <v-btn class="btn-custom"
        :disabled="isLoading"
        @click="submit"
      >
        {{ $t("Create") }}
      </v-btn>
    </modal-footer>
  </modal>
</template>
