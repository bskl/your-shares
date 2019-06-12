<script type="text/ecmascript-6">

import Modal from '../modals/modal/Modal.vue';
import ModalHeading from '../modals/modal/ModalHeading.vue';
import ModalBody from '../modals/modal/ModalBody.vue';
import ModalFooter from '../modals/modal/ModalFooter.vue';
import { mapActions, mapMutations } from 'vuex';

export default {
    /*
     * The component's name.
     */
    name: 'DeletePortfolioModal',

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
            form: new Form({
                id: 0,
            }),
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
            'destroyPortfolio',
        ]),

      ...mapMutations([
        'SET_SNACKBAR',
      ]),

        /**
         * Set form elements from given data and open the model.
         */
        open(data) {
            this.form = new Form(data);
            this.showModal = true;
        },

        /**
         * Close the modal and reset form elements.
         */
        close() {
            this.showModal = false;
            this.form.reset();
        },

        /**
         * Delete selected portfolio.
         */
        submit() {
            this.isLoading = true;

            this.destroyPortfolio(this.form.id)
                .then((res) => {
                    this.isLoading = false
                    this.close();
                    this.$router.push({ name: 'Home' });
                })
                .catch((error) => {
                    if (error.response.status == 404) {
                        this.$router.push({ name: 'NotFound' });
                    } else {
                      this.SET_SNACKBAR({ color: 'error', text: error.response.data });
                    }
                });
        }
    },
}
</script>

<template>
  <modal width="360" :dialog="showModal">
    <modal-heading>
      <span class="headline">{{ $t("Delete Portfolio") }}</span>
    </modal-heading>
      <modal-body>
        <div class="text-xs-center">
            {{ $t("Are you sure want to delete to portfolio?") }}
        </div>
      </modal-body>
      <modal-footer>
        <v-spacer></v-spacer>
        <v-btn color="grey darken-1" flat @click="close">
            {{ $t("Close") }}
        </v-btn>
        <v-btn color="red darken-1" :loading="isLoading" flat @click="submit">
            {{ $t("Delete") }}
        </v-btn>
      </modal-footer>
  </modal>
</template>
