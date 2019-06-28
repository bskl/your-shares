<script>

import { mapActions } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'TransactionListingByType',

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      transactions: null,
      returnLink: '/',
      headers: [
        { text: this.$t('Year'), value: 'name', align: 'left', sortable: false },
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

  methods: {
    ...mapActions([
      'fetchTransactionsByTypeAndYear',
    ]),

    createLink(year) {
      return this.$route.params.year ? '' : `${this.$route.path}/${year}`;
    },

    fetchData(path) {
      this.isLoading = true;

      this.fetchTransactionsByTypeAndYear(path)
        .then((res) => {
          this.transactions = res;
          this.isLoading = false;
        })
        .catch();
    },
  },

  beforeRouteUpdate (to, from, next) {
    if (to.params.year) {
      this.headers[0].text = this.$t('Symbol');
      this.returnLink = from.fullPath;
    } else {
      this.returnLink = '/'
    }
    this.fetchData(to.fullPath);

    next();
  },

  created() {
    this.fetchData(this.$route.path);
  },
}
</script>

<template>
  <v-layout row wrap v-if="!isLoading">
    <v-flex xs10 offset-md1>
      <v-card>
        <v-card-title class="pt-0 pb-0 elevation-3 no-wrap">
          <v-toolbar color="white" flat>
            <v-btn icon light class="ml-0" :to="returnLink" exact>
              <v-icon color="grey darken-2">arrow_back</v-icon>
            </v-btn>
            <v-toolbar-title class="grey--text text--darken-4 ml-1">{{ $t('Dividend Transactions') }}</v-toolbar-title>
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
              <router-link tag="tr" :to="createLink(props.item.name)">
                <td v-for="(header, index) in headers" :key="header.value"
                  :class="{
                    'green--text': props.item[header.value] > 0 && index != 0,
                    'text-xs-right': index != 0
                  }"
                >
                  {{ index == 0 ? props.item[header.value] : $n(props.item[header.value], "currency") }}
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
