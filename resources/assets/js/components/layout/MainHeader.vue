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
    <v-toolbar app dark fixed color="blue-grey darken-1">
        <v-toolbar-title>Your Shares</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-side-icon class="hidden-md-and-up"></v-toolbar-side-icon>

        <user-badge />

    </v-toolbar>
</template>
