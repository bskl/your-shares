<script type="text/ecmascript-6">
    import { userStore } from '../../stores/userStore.js';

    export default {
        /*
         * The component's name.
         */
        name: 'passwordResetForm',

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
    <div class="container" style="padding-top: 60px;">
        <div class="row justify-content-center">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center">
                            <strong>Manage.yourShares</strong>
                        </h2>
                        <br />

                        <!-- Email Send Form -->
                        <form class="card-text form-horizontal" role="form" method="POST"
                              v-on:submit.prevent="passwordResetEmail" v-on:keydown="form.errors.clear($event.target.name)">

                            <div class="form-group">
                                <div class="col-md-12" v-bind:class="{ 'has-danger': form.errors.has('email') }">
                                    <input type="email" id="email" name="email" class="form-control" autofocus v-bind:placeholder="$t('E-Mail Address')" v-model="form.email">
                                    <label class="sr-only" for="email">{{ $t("E-Mail Address") }}</label>

                                    <span class="form-text text-danger" v-if="form.errors.has('email')" v-text="form.errors.get('email')"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block" :disabled="form.errors.any()">
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
</template>
