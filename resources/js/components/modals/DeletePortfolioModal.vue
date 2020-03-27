<script>

import { mapState, mapActions } from 'vuex';
import { parseSuccessMessage, parseErrorMessage } from '../../utilities/helpers.js';
import loadingHandler from '../../mixins/loadingHandler.js';
import Modal from '../modals/modal/Modal.vue';
import ModalHeading from '../modals/modal/ModalHeading.vue';
import ModalBody from '../modals/modal/ModalBody.vue';
import ModalFooter from '../modals/modal/ModalFooter.vue';

export default {
  /**
   * The component's name.
   */
  name: 'DeletePortfolioModal',

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
      waitFor: 'destroy_portfolio',
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
      'destroyPortfolio', 'setShowModal',
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

      this.destroyPortfolio(this.id)
        .then((res) => {
          parseSuccessMessage(res)
          this.$router.push({ name: 'Home' });
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
};
</script>

<template>
  <modal :width="460" :dialog="showModal">
    <modal-heading>
      <v-toolbar-title>{{ $t("Delete Portfolio") }}</v-toolbar-title>
    </modal-heading>
    <modal-body>
      <div class="text-xs-center">{{ $t("Are you sure you want to delete this portfolio?") }}</div>
    </modal-body>
    <v-divider></v-divider>
    <modal-footer>
      <v-spacer></v-spacer>
      <v-progress-circular v-show="isLoading" indeterminate />
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
