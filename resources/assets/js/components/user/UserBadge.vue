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
    <span class="name align-middle">
        {{ state.user.name }}
    </span>
</template>