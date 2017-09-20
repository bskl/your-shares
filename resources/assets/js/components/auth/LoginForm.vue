<script type="text/ecmascript-6">
    import { userStore } from '../../stores/userStore.js';

    export default {
        /*
         * The component's name.
         */
        name: 'LoginForm',

        /*
         * The component's data.
         */
        data() {
            return {
                form: new Form({
                    email: '',
                    password: '',
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
             * Login User.
             */
            login() {
                this.form.post('/login')
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
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">
                            <strong>manage.yourShares</strong>
                        </h3>
                        <br />

                        <!-- Login User Form -->
                        <form class="card-text form-horizontal" role="form" method="POST"
                            v-on:submit.prevent="login" v-on:keydown="form.errors.clear($event.target.name)">

                            <div class="form-group">
                                <div class="col-md-12" v-bind:class="{ 'has-danger': form.errors.has('email') }">
                                    <input type="email" id="email" name="email" class="form-control" autofocus v-bind:placeholder="$t('E-Mail Address')" v-model="form.email">
                                    <label class="sr-only form-control-label" for="email">{{ $t("E-Mail Address") }}</label>

                                    <span class="form-text text-danger" v-if="form.errors.has('email')" v-text="form.errors.get('email')"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12" v-bind:class="{ 'has-danger': form.errors.has('password') }">
                                    <input type="password" id="password" name="password" class="form-control" v-bind:placeholder="$t('Password')" v-model="form.password">
                                    <label class="sr-only form-control-label" for="password">{{ $t("Password") }}</label>

                                    <span class="form-text text-danger" v-if="form.errors.has('password')" v-text="form.errors.get('password')"></span>

                                    <div class="form-text text-right"><router-link to="/password/reset"><small>{{ $t("Forgot password?") }}</small></router-link></div>                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block" :disabled="form.errors.any()">
                                        {{ $t("Login") }}
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
