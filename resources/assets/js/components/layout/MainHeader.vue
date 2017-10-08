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
    <header class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/" aria-label="Your Shares">Your Shares</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
            </ul>
        </div>

        <user-badge />

    </header>
</template>
