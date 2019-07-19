<script>

import Modal from '../modals/modal/Modal.vue';
import ModalHeading from '../modals/modal/ModalHeading.vue';
import ModalBody from '../modals/modal/ModalBody.vue';
import ModalFooter from '../modals/modal/ModalFooter.vue';
import { mapActions } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'DeleteTransactionModal',

  components: {
    Modal, ModalHeading, ModalBody, ModalFooter,
},

  /**
   * The component's data.
   */
  data() {
    return {
      showModal: false,
      isLoading: false,
      id: null,
    };
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
      'destroyTransaction', 'setSnackbar',
    ]),

    /**
     * Set form elements from given data and open the model.
     */
    open(id) {
      this.id = id;
      this.showModal = true;
    },

    /**
     * Close the modal and reset form elements.
     */
    close() {
      this.id = null;
      this.showModal = false;
    },

    /**
     * Delete selected portfolio.
     */
    submit() {
      this.isLoading = true;

      this.destroyTransaction(this.id)
        .then((res) => {
          this.close();
          this.$router.push({ name: 'Home' });
        })
        .catch((error) => {
          this.setSnackbar({ color: 'error', text: error.response.data });
        })
        .finally(() => {
          this.isLoading = false;
        });
    }
  },
}
</script>

<template>
  <modal width="360" :dialog="showModal">
    <modal-heading>
      <span class="headline">{{ $t("Delete Transaction") }}</span>
    </modal-heading>
      <modal-body>
        <div class="text-xs-center">{{ $t("Are you sure you want to delete the last transaction?") }}</div>
      </modal-body>
      <modal-footer>
        <v-spacer></v-spacer>
        <v-btn color="grey darken-1" flat @click="close">{{ $t("Close") }}</v-btn>
        <v-btn color="red darken-1" flat :loading="isLoading" @click="submit">{{ $t("Delete") }}</v-btn>
      </modal-footer>
  </modal>
</template>
