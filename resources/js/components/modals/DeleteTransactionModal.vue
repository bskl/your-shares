<script>

import { mapState, mapActions } from 'vuex';
import { parseErrorMessage, parseSuccessMessage } from '../../utilities/helpers.js';
import loadingHandler from '../../mixins/loadingHandler.js';
import Modal from '../modals/modal/Modal.vue';
import ModalHeading from '../modals/modal/ModalHeading.vue';
import ModalBody from '../modals/modal/ModalBody.vue';
import ModalFooter from '../modals/modal/ModalFooter.vue';

export default {
  /**
   * The component's name.
   */
  name: 'DeleteTransactionModal',

  mixins: [
    loadingHandler
  ],

  components: {
    Modal, ModalHeading, ModalBody, ModalFooter,
},

  /**
   * The component's data.
   */
  data() {
    return {
      waitFor: 'destroy_transaction',
      id: null,
    };
  },

  computed: {
    ...mapState([
      'showModal',
    ]),
  },

  /**
   * Prepare the component.
   */
  mounted() {
    document.addEventListener("keydown", (e) => {
      if (e.keyCode == 27) {
        this.close();
      }
    });
  },

  methods: {
    ...mapActions([
      'destroyTransaction', 'setShowModal',
    ]),

    /**
     * Set form elements from given data and open the model.
     */
    open(id) {
      this.id = id;
      this.setShowModal(true);
    },

    /**
     * Close the modal and reset form elements.
     */
    close() {
      this.id = null;
      this.setShowModal(false);
    },

    /**
     * Delete selected portfolio.
     */
    submit() {
      this.startLoading();

      this.destroyTransaction(this.id)
        .then((res) => {
          parseSuccessMessage(res);
        })
        .catch((error) => {
          parseErrorMessage(error);
        })
        .finally(() => {
          this.stopLoading();
          this.close();
        });
    }
  },
}
</script>

<template>
  <modal width="360" :dialog="showModal">
    <modal-heading>
      {{ $t("Delete Transaction") }}
    </modal-heading>
    <modal-body>
      <div class="text-xs-center">{{ $t("Are you sure you want to delete the last transaction?") }}</div>
    </modal-body>
    <modal-footer :is-loading="isLoading">
      <v-btn class="btn-close"
        :disabled="isLoading"
        @click="close"
      >
        {{ $t("Close") }}
      </v-btn>
      <v-btn class="btn-warning"
        :disabled="isLoading"
        @click="submit"
      >
        {{ $t("Delete") }}
      </v-btn>
    </modal-footer>
  </modal>
</template>
