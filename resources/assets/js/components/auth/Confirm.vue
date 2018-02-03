<script type="text/ecmascript-6">
    import MainLayout from '../layout/MainLayout.vue';
    import Status from '../partials/Status.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'Confirm',

        components: {
            MainLayout, Status,
        },

        /*
         * The component's data.
         */
        data() {
            return {
                message: null,
                alert: null,
            }
        },

        mounted() {
            this.init();
        },

        methods: {
            /**
             * Confirm User Email.
             */
            init() {
                NProgress.start();

                return new Promise((resolve, reject) => {
                    http.get('/confirm/' + this.$route.params.confirmation_code, ({ data }) => {
                        this.message = this.$t('Your email account has been verified, now you can log in with your username and password.');
                        this.alert = 'success',
                        setTimeout(() => {
                            this.$router.push('/login');
                        }, 3000);
                    }, error => {
                        this.message = this.$t('Your activation code is invalid or your e-mail address verified before.');
                        this.alert = 'error',
                        setTimeout(() => {
                            this.$router.push('/login');
                        }, 3000);
                        reject(error)
                    })
                })
            },
        }
    }
</script>

<template>
    <v-layout row wrap justify-center>
        <v-flex xs12 sm6 md4>
            <v-layout row wrap>
                <status :status="message" :color="alert"></status>
            </v-layout>
        </v-flex>
    </v-layout>
</template>
