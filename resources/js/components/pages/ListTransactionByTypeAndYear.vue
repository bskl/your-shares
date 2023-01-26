<script>

import upperFirst from 'lodash/upperFirst';
import loadingHandler from '../../mixins/loadingHandler.js';

export default {

  /**
   * The component's name.
   */
  name: 'ListTransactionByTypeAndYear',

  mixins: [
    loadingHandler,
  ],

  props: {
    initialTransactions: {
      type: [Array, Object],
      required: true,
    },
  },

  /**
   * The component's data.
   */
  data() {
    return {
      transactions: this.initialTransactions,
      headers: [
        { text: this.$t('Symbol'), value: 'item', align: 'start' },
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
    waitFor() {
      const [ model, id, unused, type, year ] = this.$route.fullPath.split('/').filter(item => item.trim().length);

      return `fetch_transactions_by_${model}_${type}_${year}`;
    },

    title() {
      return this.$t('list_by_type_year_title', {
        year: this.$route.params.year,
        code: this.$t('Portfolio'),
        type: this.$t(upperFirst(this.$route.params.type))
      });
    },
  },

  methods: {
    itemLink(id, year) {
      return `/shares/${id}/transactions/${this.$route.params.type}/${year}`
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
  <v-row
    v-if="!isLoading"
    align="center"
    justify="center"
  >
    <v-col
      cols="12"
      sm="8"
      md="4"
      lg="10"
    >
      <v-card>
        <v-toolbar
          flat
          class="pl-2"
        >
          <v-btn
            icon
            exact
            @click="goBack()"
          >
            <v-icon color="grey darken-2">
              arrow_back
            </v-icon>
          </v-btn>
          <v-toolbar-title class="pl-2">
            {{ title }}
          </v-toolbar-title>
        </v-toolbar>
        <v-divider />
        <v-card-text>
          <v-data-table
            item-key="id"
            disable-sort
            :mobile-breakpoint="0"
            :items="transactions"
            :headers="headers"
          >
            <template #body="{ items }">
              <tbody>
                <tr v-if="items.length == 0">
                  <td
                    class="text-center"
                    :colspan="headers.length"
                  >
                    {{ $t('You have not any transaction.') }}
                  </td>
                </tr>
                <router-link
                  v-for="item in items"
                  v-slot="{ navigate }"
                  :key="item.item"
                  custom
                  :to="itemLink(item.share_id, item.year)"
                >
                  <tr
                    role="link"
                    @click="navigate"
                  >
                    <td
                      v-for="(header, index) in headers"
                      :key="index"
                      class="text-center"
                    >
                      {{ item[header.value] || '-' }}
                    </td>
                  </tr>
                </router-link>
              </tbody>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>
