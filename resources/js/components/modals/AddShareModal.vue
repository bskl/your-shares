<script>

import Modal from '../modals/modal/Modal.vue';
import ModalHeading from '../modals/modal/ModalHeading.vue';
import ModalBody from '../modals/modal/ModalBody.vue';
import ModalFooter from '../modals/modal/ModalFooter.vue';
import FormErrors from '../partials/FormErrors.vue';
import { mapActions } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'AddShareModal',

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
      form: new Form({
        symbol_id: '',
        portfolio_id: '',
      }),
      symbolRules: [
        (v) => !!v || this.$t("Symbol is required"),
      ],
    };
  },

  watch: {
    search (val) {
      if (val == null || this.symbols.length > 0 || this.isLoading) return

      this.searching = true;

      this.fetchSymbols()
        .then((res) => {
          this.symbols = res;
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
      this.form = new Form({
        symbol_id: '',
        portfolio_id: portfolioId,
      });
      this.showModal = true;
    },

    /**
     * Close the modal and reset form elements.
     */
    close() {
      this.searching = false;
      this.$refs.form.reset();
      this.$refs.form.resetValidation();
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
            this.form.onFail(error.response.data)
          })
          .finally(() => {
            this.isLoading = false
          });
      }
    },
  },
}
</script>

<template>
  <modal width="360" :dialog="showModal">
    <modal-heading>
      <span class="headline">{{ $t("Add Symbol") }}</span>
    </modal-heading>
    <v-form ref="form" v-model="valid" lazy-validation
      @keyup.native.enter="submit"
    >
      <modal-body>
        <form-errors :errors="form.errors" />
        <v-autocomplete clearable required autofocus
          :label="$t('Search Symbol')"
          :no-data-text="$t('No data available')"
          :loading="searching"
          :items="symbols"
          item-text="code"
          item-value="id"
          :rules="symbolRules"
          :search-input.sync="search"
          v-model="form.symbol_id"
        ></v-autocomplete>
      </modal-body>
      <modal-footer>
        <v-spacer></v-spacer>
        <v-btn color="grey darken-1" flat @click="close">{{ $t("Close") }}</v-btn>
        <v-btn color="blue darken-1" flat :loading="isLoading" @click="submit">{{ $t("Create") }}</v-btn>
      </modal-footer>
    </v-form>
  </modal>
</template>
