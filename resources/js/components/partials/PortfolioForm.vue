<script type="text/ecmascript-6">

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

    /*
     * The component's name.
     */
    name: 'PortfolioForm',

    components: {
        FormErrors,
    },

    /*
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
        <v-text-field
            name="name"
            id="name"
            type="text"
            v-model="form.name"
            :label="$t('Portfolio Name')"
            :rules="nameRules"
            autofocus
            required
        ></v-text-field>
        <v-select
            name="currency"
            id="currency"
            type="select"
            v-model="form.currency"
            :items="['TRY']"
            :label="$t('Currency')"
            :rules="currencyRules"
            required
        ></v-select>
        <v-text-field
            name="commission"
            id="commission"
            type="number"
            step="0.0001"
            v-model="form.commission"
            :label="$t('Enter Commission Rate')"
            :rules="commissionRules"
            :hint="$t('For example; Garanti Bank: 0,188')"
            required
        ></v-text-field>
    </v-card-text>
</template>
