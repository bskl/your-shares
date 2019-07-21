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
        this.transactions = res;
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
  <v-layout row wrap v-if="!isLoading">
    <v-flex xs12 sm12 md10 offset-md1>
      <v-card>
        <v-card-title class="pt-0 pb-0 elevation-3 no-wrap">
          <v-toolbar color="white" flat>
            <v-btn icon light class="ml-0" exact
              :to="returnLink"
            >
              <v-icon color="grey darken-2">arrow_back</v-icon>
            </v-btn>
            <v-toolbar-title class="grey--text text--darken-4 ml-1">{{ title }}</v-toolbar-title>
          </v-toolbar>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <transaction-item
            :items="transactions"
          />
        </v-card-text>
      </v-card>
    </v-flex>
  </v-layout>
</template>
