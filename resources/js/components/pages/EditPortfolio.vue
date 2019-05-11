<script type="text/ecmascript-6">

import { mapActions, mapGetters } from 'vuex';
import PortfolioForm from '../partials/PortfolioForm.vue';
import DeletePortfolioModal from '../modals/DeletePortfolioModal.vue';

export default {
    /*
     * The component's name.
     */
    name: 'EditPortfolio',

    components: {
        PortfolioForm, DeletePortfolioModal,
    },

    /*
     * The component's data.
     */
    data() {
        return {
            isLoading: false,
            form: new Form({
                id: 0,
                name: '',
                currency: '',
                commission: '',
            }),
            valid: true,
        }
    },

    computed: {
        ...mapGetters([
            'getPortfolioById', 'getPortfolioWithKey',
        ]),
    },

    mounted() {
        const portfolioId = this.$route.params.portfolioId;

        if (portfolioId) {
            const portfolio = this.getPortfolioById(portfolioId);
            this.form = new Form(this.getPortfolioWithKey(portfolio, ['id', 'name', 'currency', 'commission']));
        }
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
                this.isLoading = true;

                this.updatePortfolio(this.form)
                    .then(() => {
                        this.$router.replace('/');
                    })
                    .catch((error) => {
                        this.form.onFail(error.response.data)
                    })
                    .finally(() => {
                        this.isLoading = false
                        this.$refs.form.resetValidation();
                    });
            }
        },

        /**
         * Open the modal for deleting portfolio.
         */
        showDeletePortfolioModal(portfolioId) {
            this.$refs.deletePortfolioModal.open(portfolioId);
        },
    }
}
</script>

<template>
  <div>
  <v-layout row wrap justify-center>
    <v-flex xs12 sm6 md4>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card>
            <v-form ref="form" v-model="valid" lazy-validation @keyup.native.enter="submit">
              <v-card-title>
                <div class="headline mb-0">{{ $t("Add Portfolio") }}</div>
              </v-card-title>
              <portfolio-form :form="form"></portfolio-form>
              <v-card-actions>
                <v-btn color="red" @click="showDeletePortfolioModal({id: form.id})">
                    {{ $t("Delete Portfolio") }}
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn :to="'/'">
                    {{ $t("Close") }}
                </v-btn>
                <v-btn color="primary" :loading="isLoading" @click="submit">
                    {{ $t("Update") }}
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
  <delete-portfolio-modal ref="deletePortfolioModal" />
  </div>
</template>
