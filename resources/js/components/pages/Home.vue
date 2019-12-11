<script type="text/ecmascript-6">

import { mapState, mapActions, mapGetters } from 'vuex';
import AddShareModal from '../modals/AddShareModal.vue';
import ItemDetail from '../partials/ItemDetail.vue';
import { ITEM_DETAILS } from '../../store/constants.js';

export default {
  /**
   * The component's name.
   */
  name: 'Home',

  components: {
    AddShareModal, ItemDetail,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      loading: false,
      itemDetails: ITEM_DETAILS,
    }
  },

  computed: {
    ...mapState([
      'portfolios',
    ]),

    ...mapGetters([
      'portfoliosCount', 'getPortfolioByIndex', 'isAdmin',
    ]),
  },

  methods: {
    ...mapActions([
      'fetchData', 'destroyShare', 'fetchSymbolsData', 'setSnackbar',
    ]),

    getColor(trend) {
      if (trend == -1) return 'red lighten-1';
      else if (trend == 1) return 'green lighten-1';
      else return '';
    },

    getTextColor(trend) {
      if (trend == -1) return 'red--text';
      else if (trend == 1) return 'green--text';
      else return 'grey--text';
    },

    getSymbolsData() {
      this.loading = true;

      this.fetchSymbolsData()
        .then()
        .catch((error) => {
          this.setSnackbar({ color: 'error', text: error.response.data });
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },

  created() {
    this.isLoading = true;

    this.fetchData()
      .finally(() => {
        this.isLoading = false;
      });

    for (let index = 0, count = this.portfoliosCount; index <  count; ++index) {
      let portfolio = this.getPortfolioByIndex(index);
      if (portfolio.commission == null) {
        this.setSnackbar({ color: 'error', text: this.$t('Your portfolio commission rate has not been recorded.') });
        this.$router.push({ name: 'EditPortfolio', params: { id: portfolio.id } });
        break;
      }
    }
  },
  //
}
</script>

<template>
  <v-row align="center" justify="center" v-if="!isLoading">
    <add-share-modal ref="addShareModal" />
    <v-col cols="12" sm="8" md="4" lg="10"
      v-for="portfolio in portfolios" :key="portfolio.id"
    >
      <v-card>
        <v-toolbar flat class="pl-2">
          <v-btn icon disabled>
            <v-icon>home</v-icon>
          </v-btn>
          <v-toolbar-title class="pl-2">{{ portfolio.name }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon small class="mx-1"
            v-if="isAdmin"
            :disabled="loading"
            @click="getSymbolsData()"
          >
            <v-icon>refresh</v-icon>
          </v-btn>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn icon small class="mx-1"
                v-on="on"
                :disabled="loading"
                :to="`/portfolio/${portfolio.id}/edit`"
              >
                <v-icon color="green darken-2">edit</v-icon>
              </v-btn>
            </template>
            <span>{{ $t("Update Portfolio") }}</span>
          </v-tooltip>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn icon small class="mx-1"
                v-on="on"
                :disabled="loading"
                @click="$refs.addShareModal.open(portfolio.id)"
              >
                <v-icon color="blue darken-2">add</v-icon>
              </v-btn>
            </template>
            <span>{{ $t("Add Symbol") }}</span>
          </v-tooltip>
        </v-toolbar>
        <v-divider></v-divider>
        <v-card-text>
          <v-data-table item-key="id" hide-default-footer disable-sort
            :mobile-breakpoint="0"
            :items="portfolio.shares"
            :no-data-text="$t('You have not created any symbol.')"
            :headers="[
              { text: $t('Symbol'), value: 'code', align: 'start' },
              { text: $t('Last Price'), value: 'last_price', align: 'center' },
              { text: $t('Change'), value: 'rate_of_change', align: 'center' },
              { text: $t('Lots'), value: 'lot', align: 'center' },
              { text: $t('Average Cost'), value: 'average', align: 'center' },
              { text: $t('Amount'), value: 'amount', align: 'center' },
              { text: $t('Average Amount'), value: 'average_amount', align: 'center' },
              { text: $t('Gain/Loss'), value: 'gain', align: 'center' },
            ]"
          >
            <template v-slot:item.code="{ item }">
              <v-btn text block class="font-weight-bold"
                :to="`/share/${item.id}/transactions`"
                :disabled="loading"
              >
                {{ item.symbol.code }}
                <v-spacer></v-spacer>
                <v-icon>horizontal_split</v-icon>
              </v-btn>
            </template>
            <template v-slot:item.last_price="{ item }">
              <div class="text--darken-1 text-right"
                :class="getTextColor(item.symbol.trend)"
              >
                {{ item.symbol.last_price }}
              </div>
            </template>
            <template v-slot:item.rate_of_change="{ item }">
              <v-chip label small
                :color="getColor(item.symbol.trend)"
              >
                {{ item.symbol.rate_of_change }}
              </v-chip>
            </template>
            <template v-slot:item.lot="{ item }">
              <div class="text-right">
                {{ item.lot }}
              </div>
            </template>
            <template v-slot:item.average="{ item }">
              <div class="d-flex align-center justify-center">
                <v-col cols="auto" class="pr-0 text-right">
                  {{ item.average }}
                </v-col>
                <v-col cols="auto" class="text-right overline font-weight-thin">
                  ({{ item.average_with_dividend }})
                </v-col>
              </div>
            </template>
            <template v-slot:item.amount="{ item }">
              <div class="text-right">
                {{ item.amount }}
              </div>
            </template>
            <template v-slot:item.average_amount="{ item }">
              <div class="d-flex align-center justify-center">
                <v-col cols="auto" class="pr-0 text-right">
                  {{ item.average_amount }}
                </v-col>
                <v-col cols="auto" class="text-right overline font-weight-thin">
                  ({{ item.average_amount_with_dividend }})
                </v-col>
              </div>
            </template>
            <template v-slot:item.gain="{ item }">
              <div class="d-flex align-center justify-center">
                <v-col cols="auto" class="pr-0 text-right"
                  :class="getTextColor(item.gain_trend)"
                >
                  {{ item.gain }}
                </v-col>
                <v-col cols="auto" class="text-right overline font-weight-thin"
                  :class="getTextColor(item.gain_with_dividend_trend)"
                >
                  ({{ item.gain_with_dividend }})
                </v-col>
              </div>
            </template>
          </v-data-table>
          <div class="ma-4">
            <v-icon x-small dense>access_time</v-icon>
            <span class="mx-1 caption font-weight-thin">SG: {{ portfolios[0].shares[0].symbol.session_time }}</span>
          </div>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions style="background-color: #323639;">
          <v-flex class="px-2">
            <v-list dense color="#323639">
              <template v-for="(item, index) in itemDetails">
                <item-detail :key="item.key"
                  :item="item"
                  :value="portfolio[item.key]"
                  :baseLink="`portfolio/${portfolio.id}`"
                />
                <v-divider
                  v-if="index + 1 < itemDetails.length"
                  :key="index"
                ></v-divider>
              </template>
            </v-list>
          </v-flex>
        </v-card-actions>
      </v-card>
    </v-col>
  </v-row>
</template>

<style scoped>
  table.v-data-table thead tr:last-child th {
    font-size: 0.9rem !important;
  }
</style>
