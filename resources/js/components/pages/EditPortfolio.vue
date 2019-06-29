<script>

import PortfolioForm from '../partials/PortfolioForm.vue';
import DeletePortfolioModal from '../modals/DeletePortfolioModal.vue';
import { mapActions, mapGetters } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'EditPortfolio',

  components: {
    PortfolioForm, DeletePortfolioModal,
  },

  /**
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
          this.form = new Form(res);
        })
        .catch();
    },

    /**
     * Update Portfolio.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.isLoading = true;

        this.updatePortfolio(this.form)
          .then(() => {
            this.$router.push({ name: 'Home' });
          })
          .catch((error) => {
            this.form.onFail(error.response.data);
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
    showDeletePortfolioModal(id) {
      this.$refs.deletePortfolioModal.open(id);
    },
  },
}
</script>

<template>
  <div>
    <v-layout row wrap justify-center>
      <v-flex xs12 sm6 md4>
        <v-card>
          <v-form ref="form" v-model="valid" lazy-validation @keyup.native.enter="submit">
            <v-card-title>
              <div class="headline mb-0">{{ $t("Update Portfolio") }}</div>
            </v-card-title>
            <portfolio-form
              :form="form"
            ></portfolio-form>
            <v-card-actions>
              <v-btn color="red"
                v-if="this.portfoliosCount > 1"
                @click="showDeletePortfolioModal({id: form.id})"
              >
                {{ $t("Delete") }}
              </v-btn>
              <v-spacer></v-spacer>
              <v-btn :to="'/'">{{ $t("Close") }}</v-btn>
              <v-btn color="primary" :loading="isLoading" @click="submit">{{ $t("Update") }}</v-btn>
            </v-card-actions>
          </v-form>
        </v-card>
      </v-flex>
    </v-layout>
    <delete-portfolio-modal ref="deletePortfolioModal" />
  </div>
</template>
