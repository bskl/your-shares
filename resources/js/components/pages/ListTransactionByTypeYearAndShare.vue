<script>

import TransactionItem from '../partials/TransactionItem.vue';
import upperFirst from 'lodash/upperFirst';
import { mapActions } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'ListTransactionByTypeYearAndShare',

  components: {
    TransactionItem,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      transactions: [],
      returnLink: null,
    }
  },

  computed: {
    title() {
      return this.$t(`${upperFirst(this.$route.params.type)} Transactions`);
    },
  },

  methods: {
    ...mapActions([
      'fetchTransactionsByParams',
    ]),
  },

  created() {
    this.isLoading = true;

    this.fetchTransactionsByParams(this.$route.path)
      .then((res) => {
        this.transactions = res.data;
        this.isLoading = false;
      })
      .catch();
  },

  beforeRouteEnter (to, from, next) {
    next(vm => {
      vm.returnLink = from.fullPath;
    });
  },
}
</script>

<template>
  <v-row align="center" justify="center" v-if="!isLoading">
    <v-col cols="12" sm="8" md="4" lg="10">
      <v-card>
        <v-toolbar flat class="pl-2">
          <v-btn icon exact
            :to="returnLink"
          >
            <v-icon color="grey darken-2">arrow_back</v-icon>
          </v-btn>
          <v-toolbar-title class="pl-2">{{ title }}</v-toolbar-title>
        </v-toolbar>
        <v-divider></v-divider>
        <v-card-text>
          <transaction-item
            :items="transactions"
          />
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>
