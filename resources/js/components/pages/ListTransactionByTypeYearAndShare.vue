<script>

import { mapGetters } from 'vuex';
import upperFirst from 'lodash/upperFirst';
import loadingHandler from '../../mixins/loadingHandler.js';
import TransactionItem from '../partials/TransactionItem.vue';

export default {
  props: {
    initialTransactions: {
      type: [Array, Object],
      required: true,
    },
  },

  /**
   * The component's name.
   */
  name: 'ListTransactionByTypeYearAndShare',

  mixins: [
    loadingHandler,
  ],

  components: {
    TransactionItem,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      transactions: this.initialTransactions,
    }
  },

  computed: {
    ...mapGetters([
      'getShareById',
    ]),

    waitFor() {
      const [ model, id, unused, type, year ] = this.$route.to.fullPath.split('/').filter(item => item.trim().length);

      return `fetch_transactions_by_${model}_${type}_${year}`;
    },

    title() {
      return this.$t('list_by_type_year_title', {
        year: this.$route.params.year,
        code: this.code(),
        type: this.$t(upperFirst(this.$route.params.type))
      });
    },
  },

  methods: {
    code() {
      return this.getShareById(this.$route.params.id).symbol.code;
    },

    goBack() {
      (window.history.length > 1)
        ? this.$router.go(-1)
        : this.$router.push({ name: 'Home' });
    }
  },
}
</script>

<template>
  <v-row align="center" justify="center" v-if="!isLoading">
    <v-col cols="12" sm="8" md="4" lg="10">
      <v-card>
        <v-toolbar flat class="pl-2">
          <v-btn icon exact
            @click="goBack()"
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
