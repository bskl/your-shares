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
    }
</script>

<template>
    <li class="nav-item">
        <span class="nav-link">{{ state.user.name }}</span>
    </li>
</template>