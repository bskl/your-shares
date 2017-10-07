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
            }
        }
    }
</script>

<template>
    <nav>
        <ul class="nav nav-pills float-right">
            <li class="nav-item">
                <span class="nav-link">{{ state.user.name }}</span>
            </li>
            <li class="nav-item">
                <span class="nav-link">
                    <router-link v-on:click.native="logout" to="/">{{ $t("Logout") }}</router-link>
                </span>
            </li>
        </ul>
    </nav>
</template>