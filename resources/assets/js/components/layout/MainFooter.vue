<script type="text/ecmascript-6">
    export default {
        /*
         * The component's name.
         */
        name: 'MainFooter',

        /*
         * The component's data.
         */
        data() {
            return {}
        },
    }
</script>

<template>
    <v-footer app class="pa-3">
        <v-spacer></v-spacer>
        <div>© {{ new Date().getFullYear() }}</div>
    </v-footer>
</template>
