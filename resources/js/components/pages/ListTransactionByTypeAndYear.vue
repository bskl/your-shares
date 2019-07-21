<script>

import upperFirst from 'lodash/upperFirst';
import { mapActions } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'ListTransactionByTypeAndYear',

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      transactions: [],
      headers: [
        { text: this.$t('Symbol'), value: 'item', align: 'left', sortable: false },
        { text: this.$t('January'), value: '1', align: 'center', sortable: false },
        { text: this.$t('February'), value: '2', align: 'center', sortable: false },
        { text: this.$t('March'), value: '3', align: 'center', sortable: false },
        { text: this.$t('April'), value: '4', align: 'center', sortable: false },
        { text: this.$t('May'), value: '5', align: 'center', sortable: false },
        { text: this.$t('June'), value: '6', align: 'center', sortable: false },
        { text: this.$t('July'), value: '7', align: 'center', sortable: false },
        { text: this.$t('August'), value: '8', align: 'center', sortable: false },
        { text: this.$t('September'), value: '9', align: 'center', sortable: false },
        { text: this.$t('October'), value: '10', align: 'center', sortable: false },
        { text: this.$t('November'), value: '11', align: 'center', sortable: false },
        { text: this.$t('December'), value: '12', align: 'center', sortable: false },
        { text: this.$t('Total'), value: 'total', align: 'center', sortable: false },
      ],
    }
  },

  computed: {
    title() {
      return this.$t(`${upperFirst(this.$route.params.type)} Transactions`);
    },

    returnLink() {
      const items = this.$route.path
        .split('/')
        .filter(item => item.trim().length);
      
      return `/${items[0]}/${this.$route.params.id}/transactions/${this.$route.params.type}`;
    }
  },

  methods: {
    ...mapActions([
      'fetchTransactionsByParams',
    ]),

    itemLink(id, year) {
      return `/share/${id}/transactions/${this.$route.params.type}/${year}`
    }
  },

  created() {
    this.link;
    this.isLoading = true;

    this.fetchTransactionsByParams(this.$route.path)
      .then((res) => {
        this.transactions = res;
        this.isLoading = false;
      })
      .catch();
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
          <v-data-table item-key="id"
            :items="transactions"
            :headers="headers"
            :no-data-text="$t('You have not any transaction.')"
            :rows-per-page-text="$t('Rows per page:')"
            :rows-per-page-items="[5, 10, 25, { text: $t('All'), value: -1 }]"
          >
            <template v-slot:items="props">
              <router-link tag="tr"
                :to="itemLink(props.item.share_id, props.item.year)"
              >
                <td class="text-xs-center"
                  v-for="(header, index) in headers" :key="index"
                >
                  {{ props.item[header.value] || '-' }}
                </td>
              </router-link>
            </template>
            <template slot="pageText" slot-scope="props">
              {{ $t("page_text", { itemsLength: props.itemsLength, pageStart: props.pageStart, pageStop: props.pageStop }) }}
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-flex>
  </v-layout>
</template>
