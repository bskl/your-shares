<script type="text/ecmascript-6">
    import { ls } from '../../services/ls.js';
    import { sharedStore } from '../../stores/sharedStore.js';
    import { userStore } from '../../stores/userStore.js';
    import MainLayout from '../layout/MainLayout.vue';
    import Portfolios from './Portfolios.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'Home',

        components: {
            MainLayout, Portfolios,
        },

        /*
         * The component's data.
         */
        data() {
            return {
                loading: true,
            }
        },

        mounted() {
            if (!userStore.isAuthenticated()) {
                this.$router.push('/login');
            } else {
                this.init();
            }

            document.addEventListener("keydown", (e) => {
                if ((e.ctrlKey && e.keyCode == 82) || e.keyCode == 116) {
                    this.loading = true;
                }
            })
        },

        created() {
            
        },

        methods: {
            init() {
                try {
                    sharedStore.getData()
                        .then(response => {
                            setTimeout(() => {
                                this.loading = false;
                            }, 200)
                        });
                } catch (err) {
                    this.$router.push('/login');
                }
            },
        },
    }
</script>


<template>
    <main-layout :loading="loading">

        <portfolios></portfolios>

    </main-layout>
</template>

