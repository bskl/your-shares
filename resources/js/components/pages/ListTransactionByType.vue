<script>

import upperFirst from 'lodash/upperFirst';
import { mapActions } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'ListTransactionByType',

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      transactions: [],
      title: this.$t(`${upperFirst(this.$route.params.type)} Transactions`),
      headers: [
        { text: this.$t('Year'), value: 'item', align: 'left' },
        { text: this.$t('January'), value: '1', align: 'center' },
        { text: this.$t('February'), value: '2', align: 'center' },
        { text: this.$t('March'), value: '3', align: 'center' },
        { text: this.$t('April'), value: '4', align: 'center' },
        { text: this.$t('May'), value: '5', align: 'center' },
        { text: this.$t('June'), value: '6', align: 'center' },
        { text: this.$t('July'), value: '7', align: 'center' },
        { text: this.$t('August'), value: '8', align: 'center' },
        { text: this.$t('September'), value: '9', align: 'center' },
        { text: this.$t('October'), value: '10', align: 'center' },
        { text: this.$t('November'), value: '11', align: 'center' },
        { text: this.$t('December'), value: '12', align: 'center' },
        { text: this.$t('Total'), value: 'total', align: 'center' },
      ],
    }
  },

  computed: {
    returnLink() {
      const items = this.$route.path
        .split('/')
        .filter(item => item.trim().length);
      
      return (items[0] === 'shares') ? `/shares/${this.$route.params.id}/transactions` : '/';
    }
  },

  methods: {
    ...mapActions([
      'fetchTransactionsByParams',
    ]),

    itemLink(year) {
      return `${this.$route.path}/${year}`;
    },
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
          <v-data-table item-key="id" disable-sort
            :mobile-breakpoint="0"
            :items="transactions"
            :headers="headers"
          >
            <template v-slot:body="{ items }">
              <tbody>
                <tr v-if="items.length == 0">
                  <td class="text-center"
                    :colspan="headers.length"
                  >
                    {{ $t('You have not any transaction.') }}
                  </td>
                </tr>
                <router-link tag="tr" v-for="item in items" :key="item.item" :to="itemLink(item.item)">
                  <td class="text-center"
                    v-for="(header, index) in headers" :key="index"
                  >
                    {{ item[header.value] || '-' }}
                  </td>
                </router-link>
              </tbody>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>
