<script type="text/ecmascript-6">
    import { userStore } from '../../stores/userStore.js';
    import MainLayout from '../layout/MainLayout.vue';
    import FormErrors from '../partials/FormErrors.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'RegisterForm',

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
    <main-layout>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-6">
                    <div class="card border-light">
                        <div class="card-header">{{ $t("Register") }}</div>
                        <div class="card-body">

                            <form-errors :errors="form.errors" />

                            <!-- Create User Form -->
                            <form class="card-text form-horizontal" role="form" method="POST"
                                v-on:submit.prevent="register" v-on:keydown="form.errors.clear($event.target.name)">

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
                                        <button type="submit" class="btn btn-primary btn-block" value="register">
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
                            <router-link to="/login"><b> {{ $t("Sign In") }}</b></router-link>
                    </p>
                </div>
            </div>
        </div>
    </main-layout>
</template>
