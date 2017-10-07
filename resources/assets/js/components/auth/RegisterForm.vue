<script type="text/ecmascript-6">
    import { userStore } from '../../stores/userStore.js';

    export default {
        /*
         * The component's name.
         */
        name: 'RegisterForm',

        /*
         * The component's data.
         */
        data() {
            return {
                form: new Form({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
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
             * Create a new User.
             */
            register() {
                this.form.post('/register')
                    .then(response => {
                        if (response.status === 200) {
                            Bus.$emit('userLoggedIn');
                            this.$router.push('/');
                        }
                    })
            },
        }
    }
</script>

<template>
    <div class="container pt-3">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">manage.yourShares</h3>
                        <br />

                        <div class="col-md-12" v-if="form.errors.message">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <span v-text="form.errors.message"></span>
                                <ul class="mb-0">
                                    <template v-for="errorItem in form.errors.errors">
                                        <li v-for="error in errorItem" :key="error.id">
                                            {{ error }}
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <!-- Create User Form -->
                        <form class="card-text form-horizontal" role="form" method="POST"
                              v-on:submit.prevent="register" v-on:keydown="form.errors.clear($event.target.name)">

                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="text" id="name" name="name" class="form-control" autofocus required v-bind:placeholder="$t('Full Name')" v-model="form.name">
                                    <label class="sr-only" for="name">{{ $t("Full Name") }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="email" id="email" name="email" class="form-control" required v-bind:placeholder="$t('E-Mail Address')" v-model="form.email">
                                    <label class="sr-only" for="email">{{ $t("E-Mail Address") }}</label>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="password" id="password" name="password" class="form-control" required v-bind:placeholder="$t('Password')" v-model="form.password">
                                    <label class="sr-only" for="password">{{ $t("Password") }}</label>                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="password" id="password-confirm" name="password_confirmation" class="form-control" required v-bind:placeholder="$t('Confirm Password')" v-model="form.password_confirmation">
                                    <label class="sr-only" for="password-confirm">{{ $t("Confirm Password") }}</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block" value="register" :disabled="form.errors.any()">
                                        {{ $t("Register") }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <br />
                <p class="text-center">
                    {{ $t("Already have an account?") }}
                        <router-link to="/login"><b> {{ $t("Login") }}</b></router-link>
                </p>
            </div>
        </div>
    </div>
</template>
