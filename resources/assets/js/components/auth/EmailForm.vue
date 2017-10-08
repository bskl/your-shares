<script type="text/ecmascript-6">
    import { userStore } from '../../stores/userStore.js';
    import MainLayout from '../layout/MainLayout.vue';
    import FormErrors from '../partials/FormErrors.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'PasswordResetForm',

        components: {
            MainLayout, FormErrors,
        },

        /*
         * The component's data.
         */
        data() {
            return {
                form: new Form({
                    email: '',
                }),
            }
        },

        mounted() {
            if (userStore.isAuthenticated()) {
                this.$router.push('/');
            }
        },

        methods: {
            /**
             * Sends password reset email to user.
             */
            passwordResetEmail() {
                this.form.post('/password/email')
                    .then(response => {
                        if (response.status === 200) {
                            this.$router.push('/');
                        }
                    })
            },
        }
    }
</script>

<template>
    <main-layout>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="card border-light">
                        <div class="card-header">{{ $t("Reset Password") }}</div>
                        <div class="card-body">

                            <form-errors :errors="form.errors" />

                            <!-- Email Send Form -->
                            <form class="card-text form-horizontal" role="form" method="POST"
                                v-on:submit.prevent="passwordResetEmail" v-on:keydown="form.errors.clear($event.target.name)">

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input type="email" id="email" name="email" class="form-control" autofocus required v-bind:placeholder="$t('E-Mail Address')" v-model="form.email">
                                        <label class="sr-only" for="email">{{ $t("E-Mail Address") }}</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            {{ $t("Send Password Reset Link") }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <br />
                    <p class="text-center">
                        {{ $t("You don't have an account?") }}
                            <router-link to="/register"><b> {{ $t("Register") }}</b></router-link>
                    </p>
                </div>
            </div>
        </div>
    </main-layout>
</template>
