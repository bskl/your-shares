<script>

import { mapState, mapGetters, mapActions } from 'vuex';
import { parseSuccessMessage } from '../../utilities/helpers.js';
import validationHandler from '../../mixins/validationHandler.js';
import loadingHandler from '../../mixins/loadingHandler.js';
import Modal from '../modals/modal/Modal.vue';
import ModalHeading from '../modals/modal/ModalHeading.vue';
import ModalBody from '../modals/modal/ModalBody.vue';
import ModalFooter from '../modals/modal/ModalFooter.vue';
import FormErrors from '../partials/FormErrors.vue';
import SearchSymbolField from '../partials/SearchSymbolField.vue';

export default {
  /**
   * The component's name.
   */
  name: 'AddShareModal',

  mixins: [
    validationHandler,
    loadingHandler
  ],

  components: {
    Modal, ModalHeading, ModalBody, ModalFooter, FormErrors, SearchSymbolField,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      waitFor: 'store_share',
      valid: true,
      form: {
        symbol_id: 0,
        portfolio_id: 0,
      },
    };
  },

  computed: {
    ...mapState([
      'showModal',
    ]),
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
      'storeShare', 'setShowModal',
    ]),

    /**
     * Open the modal.
     */
    open(portfolioId) {
      this.form.portfolio_id = portfolioId;
      this.setShowModal(true);
    },

    /**
     * Close the modal and reset form elements.
     */
    close() {
      this.clearErrors();
      this.$refs.form.reset();
      this.setShowModal(false);
    },

    /**
     * Add the share and hide the modal.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.startLoading();

        this.storeShare(this.form)
          .then((res) => {
            parseSuccessMessage(this.$t('The new share has been successfully added to your portfolio.'));
            this.close();
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
  <modal :width="460" :dialog="showModal">
    <modal-heading>
      {{ $t("Add Symbol") }}
    </modal-heading>
    <modal-body>
      <v-form ref="form" v-model="valid" lazy-validation
        @keyup.native.enter="submit"
        @keydown.native="clearError($event.target.name)"
      >
        <form-errors :errors="errors" />
        <search-symbol-field :symbolId.sync="form.symbol_id"></search-symbol-field>
      </v-form>
    </modal-body>
    <modal-footer :is-loading="isLoading">
      <v-btn class="btn-close"
        :disabled="isLoading"
        @click="close"
      >
        {{ $t("Close") }}
      </v-btn>
      <v-btn class="btn-action"
        :disabled="isLoading"
        @click="submit"
      >
        {{ $t("Create") }}
      </v-btn>
    </modal-footer>
  </modal>
</template>
