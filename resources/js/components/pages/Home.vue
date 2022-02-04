<script>

import { mapState, mapActions, mapGetters } from 'vuex';
import { ITEM_DETAILS } from '../../store/constants.js';
import { parseErrorMessage, parseSuccessMessage } from '../../utilities/helpers.js';
import loadingHandler from '../../mixins/loadingHandler.js';
import AddShareModal from '../modals/AddShareModal.vue';
import ItemDetail from '../partials/ItemDetail.vue';

export default {
  /**
   * The component's name.
   */
  name: 'Home',

  mixins: [
    loadingHandler,
  ],

  components: {
    AddShareModal, ItemDetail,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      waitFor: 'fetch_symbols_data',
      itemDetails: ITEM_DETAILS,
    }
  },

  computed: {
    ...mapState([
      'portfolios',
    ]),

    ...mapGetters([
      'isAdmin',
    ]),
  },

  methods: {
    ...mapActions([
      'fetchSymbolsData',
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
      this.startLoading();

      this.fetchSymbolsData()
        .then((res) => {
          parseSuccessMessage(this.$t('Share prices are successfully updated.'));
        })
        .catch((error) => {
          parseErrorMessage(error);
        })
        .finally(() => {
          this.stopLoading();
        });
    },
  },
}
</script>

<template>
  <v-row align="center" justify="center" v-if="!isInLoading('fetch_data')">
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
            :disabled="isLoading"
            @click="getSymbolsData()"
          >
            <v-icon>refresh</v-icon>
          </v-btn>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn icon small class="mx-1"
                v-on="on"
                :disabled="isLoading"
                :to="`/portfolios/${portfolio.id}/edit`"
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
                :disabled="isLoading"
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
          <v-data-table item-key="id"
            :mobile-breakpoint="0"
            :items="portfolio.shares"
            :no-data-text="$t('You have not created any symbol.')"
            hide-default-footer
            disable-pagination
            :locale="$i18n.locale"
            :headers="[
              { text: $t('Symbol'), sortable: true, value: 'symbol_code', align: 'start' },
              { text: $t('Last Price'), sortable: false, value: 'symbol_last_price', align: 'center' },
              { text: $t('Change'), sortable: true, value: 'symbol_rate_of_change', align: 'center' },
              { text: $t('Lots'), sortable: true, value: 'lot', align: 'center',
                filter: (value, search, item) => {
                  if (!portfolio.filtered) return true;

                  return value > 0 || item.total_purchase_amount == '₺0,00';
                }
              },
              { text: $t('Average Cost'), sortable: false, value: 'average', align: 'center' },
              { text: $t('Amount'), sortable: true, value: 'amount', align: 'end' },
              { text: $t('Average Amount'), sortable: false, value: 'average_amount', align: 'end' },
              { text: $t('Gain/Loss'), sortable: false, value: 'gain', align: 'end' },
              { text: $t('Gain/Loss (%)'), sortable: true, value: 'gain_percent', align: 'end' },
            ]"
          >
            <template v-slot:item.symbol_code="{ item }">
              <div class="d-flex align-center">
                <v-col cols="auto" class="px-0 float-left font-weight-bold">
                  {{ item.symbol.code }}
                </v-col>
                <v-spacer></v-spacer>
                <v-col cols="auto pr-0">
                  <v-btn text block small
                    :to="`/shares/${item.id}/transactions`"
                    :disabled="isLoading"
                  >
                    <v-icon small>horizontal_split</v-icon>
                  </v-btn>
                </v-col>
              </div>
            </template>
            <template v-slot:item.symbol_last_price="{ item }">
              <div :class="getTextColor(item.symbol.trend)">
                {{ item.symbol.last_price }}
              </div>
            </template>
            <template v-slot:item.symbol_rate_of_change="{ item }">
              <v-chip label small
                :color="getColor(item.symbol.trend)"
              >
                {{ item.symbol.rate_of_change }}
              </v-chip>
            </template>
            <template v-slot:item.lot="{ item }">
              <div>
                {{ $n(item.lot, 'decimal') }}
              </div>
            </template>
            <template v-slot:item.average="{ item }">
              <div class="d-flex">
                <v-col cols="auto" class="pr-0 float-right">
                  {{ item.average }}
                </v-col>
                <v-col cols="auto" class="float-right overline text-caption">
                  ({{ item.average_with_dividend }})
                </v-col>
              </div>
            </template>
            <template v-slot:item.gain="{ item }">
              <div :class="getTextColor(item.gain_trend)">
                {{ item.gain }}
              </div>
            </template>
            <template v-slot:item.gain_percent="{ item }">
              <div :class="getTextColor(item.gain_trend)">
                {{ $n(item.gain_percent, 'percent') }}
              </div>
            </template>
            <template v-slot:body.append>
              <tr>
                <td>{{ $t('Total') }}</td>
                <td colspan="4"></td>
                <td class="text-end">{{ portfolio.sum_amount }}</td>
                <td class="text-end">{{ portfolio.sum_average_amount }}</td>
                <td class="text-end">{{ portfolio.sum_gain }}</td>
                <td class="text-end">{{ $n(portfolio.total_gain_percent, 'percent') }}</td>
              </tr>
            </template>
            <template v-if="portfolio.shares.length" v-slot:footer>
              <div class="pl-4 py-5 d-flex align-center text-caption" style="border-top: thin solid hsla(0,0%,100%,.12);">
                <v-icon x-small dense>access_time</v-icon>
                <span class="mx-1">SG: {{ portfolio.shares[0].symbol.session_time }}</span>
              </div>
            </template>
          </v-data-table>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions style="background-color: #323639;">
          <v-flex class="px-2">
            <v-list dense color="#323639">
              <template v-for="(item, index) in itemDetails">
                <item-detail :key="item.key"
                  :item="item"
                  :value="portfolio[item.key]"
                  :baseLink="`portfolios/${portfolio.id}`"
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
