<script>

import FormErrors from './FormErrors.vue';

export default {
  props: {
    form: {
      type: Object,
      default: function () {
        return {
          id: 0,
          name: '',
          currency: '',
          commission: '',
        }
      }
    },
  },

  /**
   * The component's name.
   */
  name: 'PortfolioForm',

  components: {
    FormErrors,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      nameRules: [
        (v) => !!v || this.$t("Name is required"),
      ],
      currencyRules: [
        (v) => !!v || this.$t("Currency is required"),
      ],
      commissionRules: [
        (v) => !!v || this.$t("Commission is required"),
      ],
    }
  },
}
</script>

<template>
  <v-card-text>
    <form-errors :errors="form.errors" />
    <v-text-field type="text" name="name" id="name" required autofocus
      v-model="form.name"
      :label="$t('Portfolio Name')"
      :rules="nameRules"
    ></v-text-field>
    <v-select type="select" name="currency" id="currency" required
      v-model="form.currency"
      :items="['TRY']"
      :label="$t('Currency')"
      :rules="currencyRules"
    ></v-select>
    <v-text-field type="number" name="commission" id="commission" required
      step="0.0001"
      v-model="form.commission"
      :label="$t('Enter Commission Rate')"
      :rules="commissionRules"
      :hint="$t('For example; Garanti Bank: 0,188')"
    ></v-text-field>
  </v-card-text>
</template>
