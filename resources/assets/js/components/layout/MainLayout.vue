<script type="text/ecmascript-6">
    import MainHeader from './MainHeader.vue';
    import MainFooter from './MainFooter.vue';

    export default {
        props: ['loading'],
        /*
         * The component's name.
         */
        name: 'MainLayout',

        components: { 
            MainHeader, MainFooter,
        },
    }
</script>

<template>
    <v-app light v-if="!loading">

        <main-header />

        <main>
            <v-content>
                <v-container fluid grid-list-xl>
                    <slot />
                </v-container>
            </v-content>
        </main>

        <main-footer />

    </v-app>
</template>
