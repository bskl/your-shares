<script>

import { mapActions } from 'vuex';
import Modal from '../modals/modal/Modal.vue';
import ModalHeading from '../modals/modal/ModalHeading.vue';
import ModalBody from '../modals/modal/ModalBody.vue';
import ModalFooter from '../modals/modal/ModalFooter.vue';

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
      <v-toolbar-title>{{ $t("Delete Transaction") }}</v-toolbar-title>
    </modal-heading>
      <modal-body>
        <div class="text-xs-center">{{ $t("Are you sure you want to delete the last transaction?") }}</div>
      </modal-body>
      <v-divider></v-divider>
      <modal-footer>
        <v-spacer></v-spacer>
        <v-progress-circular v-show="isLoading" indeterminate color="rgba(89, 135, 209, 1)" width="3" size="30" />
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
          {{ $t("Delete") }}
        </v-btn>
      </modal-footer>
  </modal>
</template>
