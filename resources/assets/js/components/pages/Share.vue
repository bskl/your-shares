<script type="text/ecmascript-6">
    import { sharedStore } from '../../stores/sharedStore.js';
    import { userStore } from '../../stores/userStore.js';
    import MainLayout from '../layout/MainLayout.vue';

    export default {
        props: ['id'],

        /*
         * The component's name.
         */
        name: 'Share',

        components: {
            MainLayout,
        },

        /*
         * The component's data.
         */
        data() {
            return {
                loading: true,
                transactions: null,
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
                NProgress.start();

                return new Promise((resolve, reject) => {
                    http.get('/share/' + this.id + '/transactions', ({ data }) => {
                        _.assign(this.transactions, data)

                        resolve(this.transactions)
                        setTimeout(() => {
                            this.loading = false;
                        }, 200)
                    }, error => reject(error))
                })
            },
        },
    }
</script>


<template>
</template>

