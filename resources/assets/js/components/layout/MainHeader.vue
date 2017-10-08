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
    <header class="navbar navbar-expand-lg navbar-light mb-2">
        <a class="navbar-brand" href="/" aria-label="Your Shares"><strong>Your Shares</strong></a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
            </ul>
        </div>

        <user-badge />

    </header>
</template>
