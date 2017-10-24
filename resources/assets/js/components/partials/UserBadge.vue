<script type="text/ecmascript-6">
    import { userStore } from '../../stores/userStore.js';

    export default {
        /*
         * The component's name.
         */
        name: 'UserBadge',

        /*
         * The component's data.
         */
        data() {
            return {
                state: userStore.state
            }
        },

        methods: {
            logout () {
                userStore.logout()
            },

            isAuthenticated() {
                return userStore.isAuthenticated()
            }
        }
    }
</script>

<template>
    <v-toolbar-items class="hidden-sm-and-down">
        <v-menu offset-y v-if="isAuthenticated()">
            <v-btn flat small slot="activator">
                <span class="pr-2">{{ state.user.email }}</span>
                <v-icon dark>more_vert</v-icon>
            </v-btn>
            <v-list dense>
                <v-list-tile @click="logout()">
                    <v-icon class="pr-2">exit_to_app</v-icon>
                    <v-list-tile-title>
                        {{ $t("Logout") }}
                    </v-list-tile-title>
                </v-list-tile>
            </v-list>
        </v-menu>
        <v-btn flat to="/register" v-else>{{ $t("Register") }}</v-btn>
    </v-toolbar-items>
</template>