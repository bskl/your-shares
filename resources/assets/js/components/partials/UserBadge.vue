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
    <ul class="navbar-nav">
        <li class="nav-item dropdown show" v-if="isAuthenticated()">
            <a class="nav-item nav-link dropdown-toggle" href="#" id="user-badge" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                {{ state.user.email }}
            </a>
            <div class="dropdown-menu dropdown-menu-right show" aria-labelledby="user-badge">
                <router-link v-on:click.native="logout" class="dropdown-item active" to="/">{{ $t("Logout") }}</router-link>
            </div>
        </li>
        <li class="nav-item" v-else>
            <a class="nav-item nav-link" href="/register">
                {{ $t("Register") }}
            </a>
        </li>
    </ul>
</template>