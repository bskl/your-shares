<script type="text/ecmascript-6">
    import Spinner from '../loaders/Spinner.vue';
    import MainHeader from './MainHeader.vue';
    import MainFooter from './MainFooter.vue';

    export default {
        props: ['loading'],
        /*
         * The component's name.
         */
        name: 'MainLayout',

        components: { 
            Spinner, MainHeader, MainFooter,
        },
    }
</script>

<template>
    <div v-if="!loading">
        <main-header />

        <main>
            <slot/>
        </main>

        <main-footer/>
    </div>
    <div v-else>
        <spinner />
    </div>
</template>
