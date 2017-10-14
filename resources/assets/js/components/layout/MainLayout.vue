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
    <v-app v-if="!loading">
        <v-toolbar>
            <v-toolbar-title>Your Shares</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-side-icon class="hidden-md-and-up"></v-toolbar-side-icon>
            <v-toolbar-items class="hidden-sm-and-down">
                <v-btn flat>{{ $t("Register") }}</v-btn>
                <v-menu offset-y>
                    <v-btn flat slot="activator">
                        <span>Savas Kartal</span>
                        <v-spacer></v-spacer>
                        <v-icon dark>more_vert</v-icon>
                    </v-btn>
                    <v-list>
                        <v-list-tile @click="logout()">
                            <v-icon>exit_to_app</v-icon>
                            <v-spacer></v-spacer>
                            <v-list-tile-title>{{ $t("Logout") }}</v-list-tile-title>
                        </v-list-tile>
                    </v-list>
                </v-menu>
            </v-toolbar-items>
        </v-toolbar>
        <main>
            <v-content>
                <v-container fluid>
                    <router-view></router-view>
                </v-container>
            </v-content>
        </main>
        <v-footer app class="pa-3">
            <v-spacer></v-spacer>
            <div>© {{ new Date().getFullYear() }}</div>
        </v-footer>
    </v-app>
</template>
