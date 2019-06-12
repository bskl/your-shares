<script type="text/ecmascript-6">

import { mapActions, mapGetters } from 'vuex';
import PortfolioForm from '../partials/PortfolioForm.vue';

export default {
    /*
     * The component's name.
     */
    name: 'CreatePortfolio',

    components: {
        PortfolioForm,
    },

    /*
     * The component's data.
     */
    data() {
        return {
            isLoading: false,
            form: new Form({
                name: '',
                currency: '',
                commission: '',
            }),
            valid: true,
        }
    },

    methods: {
        ...mapActions([
            'createPortfolio',
        ]),

        /**
         * Create Portfolio.
         */
        submit() {
            if (this.$refs.form.validate()) {
                this.isLoading = true;

                this.createPortfolio(this.form)
                    .then(() => {
                        this.$router.push({ name: 'Home' });
                    })
                    .catch((error) => {
                        this.form.onFail(error.response.data)
                    })
                    .finally(() => {
                        this.isLoading = false;
                        this.$refs.form.resetValidation();
                    });
            }
        }
    }
}
</script>

<template>
  <v-layout row wrap justify-center>
    <v-flex xs12 sm6 md4>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card>
            <v-form ref="form" v-model="valid" lazy-validation @keyup.native.enter="submit">
              <v-card-title>
                <div class="headline mb-0">{{ $t("Add Portfolio") }}</div>
              </v-card-title>
              <portfolio-form :form="this.form"></portfolio-form>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn :to="'/'">
                    {{ $t("Close") }}
                </v-btn>
                <v-btn color="primary" :loading="isLoading" @click="submit">
                    {{ $t("Create") }}
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>
