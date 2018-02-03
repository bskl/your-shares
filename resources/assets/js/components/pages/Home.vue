<script type="text/ecmascript-6">
    import { sharedStore } from '../../stores/sharedStore.js';
    import { userStore } from '../../stores/userStore.js';
    import { ls } from '../../services/ls.js';
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
            if (this.$route.params.confirmation_code) {
                this.loading = false;
            } else if (! userStore.isAuthenticated()) {
                this.$router.push('/login');
                this.loading = false;
            } else {
                this.init();
            }
            Bus.$on('userLoggedIn', event => this.init());

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
                            }, 500)
                        });
                } catch (err) {
                    this.$router.push('/login');
                    this.loading = false;
                }
            },
        },
    }
</script>


<template>
    <main-layout :loading="loading">

        <router-view></router-view>

    </main-layout>
</template>

