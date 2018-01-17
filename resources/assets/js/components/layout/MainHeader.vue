<script type="text/ecmascript-6">
    import UserBadge from '../partials/UserBadge.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'MainHeader',

        components: { 
            UserBadge,
        },
    }
</script>

<template>
    <v-toolbar fixed app>
        <v-icon medium class="ml-3">account_balance</v-icon>
        <v-toolbar-title>Your Shares</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-side-icon class="hidden-md-and-up"></v-toolbar-side-icon>

        <user-badge />

    </v-toolbar>
</template>
