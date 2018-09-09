<script type="text/ecmascript-6">

    export default {
        props: ['status', 'color'],

        /*
         * The component's name.
         */
        name: 'Status',

    }
</script>

<template>
    <v-alert :color="color" icon="check_circle" :value="status">
        {{ status }}
    </v-alert>
</template>