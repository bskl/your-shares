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
    <v-toolbar app fixed dark class="elevation-0" style="background-color: #50514F;">
        <v-toolbar-title style="letter-spacing: 0em;"><b>Your Shares</b></v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-side-icon class="hidden-md-and-up"></v-toolbar-side-icon>

        <user-badge />

    </v-toolbar>
</template>
