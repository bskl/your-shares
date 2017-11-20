<script type="text/ecmascript-6">
    import { userStore } from '../../stores/userStore.js';
    import AddPortfolioModal from '../modals/AddPortfolioModal.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'UserBadge',

        components: {
            AddPortfolioModal,
        },

        /*
         * The component's data.
         */
        data() {
            return {
                isLogged: userStore.isAuthenticated(),
                state: userStore.state
            }
        },

        mounted() {
            Bus.$on('userLoggedIn', event => this.isLogged = userStore.isAuthenticated());
        },

        methods: {
            /**
             * Open the modal for adding a new portfolio.
             */
            showAddPortfolioModal() {
                this.$refs.addPortfolioModal.open();
            },

            logout () {
                userStore.logout()
            },
        }
    }
</script>

<template>
    <v-toolbar-items class="hidden-sm-and-down">
        <v-menu offset-y v-if="isLogged">
            <v-btn flat small slot="activator">
                <span class="pr-2">{{ state.user.email }}</span>
                <v-icon dark>more_vert</v-icon>
            </v-btn>
            <v-list dense>
                <v-list-tile @click="showAddPortfolioModal()">
                    <v-icon class="pr-2">add</v-icon>
                    <v-list-tile-title>
                        {{ $t("Add Portfolio") }}
                    </v-list-tile-title>
                </v-list-tile>
                <v-divider></v-divider>
                <v-list-tile @click="logout()">
                    <v-icon class="pr-2">exit_to_app</v-icon>
                    <v-list-tile-title>
                        {{ $t("Logout") }}
                    </v-list-tile-title>
                </v-list-tile>
            </v-list>
        </v-menu>
        <v-btn flat to="/register" v-if="!isLogged">{{ $t("Register") }}</v-btn>

        <add-portfolio-modal ref="addPortfolioModal" />
    </v-toolbar-items>
</template>