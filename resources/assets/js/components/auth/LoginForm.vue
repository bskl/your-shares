<script type="text/ecmascript-6">
    import { userStore } from '../../stores/userStore.js';
    import MainLayout from '../layout/MainLayout.vue';
    import FormErrors from '../partials/FormErrors.vue';

    export default {
        /*
         * The component's name.
         */
        name: 'LoginForm',

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
                emailRules: [
                    (v) => !!v || 'E-mail is required',
                    (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
                ],
                passwordRules: [
                    (v) => !!v || 'Password is required',
                    (v) => v.length >= 6 || 'Password must be more than 6 characters'
                ],
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
        <v-layout row wrap>
            <v-flex xs12 sm10 md8 lg6>
                <v-card ref="form">
                    <v-container fill-height fluid>
                        <v-layout fill-height>
                            <v-flex xs12 align-end flexbox>
                                <h5 class="display-5">{{ $t("Sign In") }}</h5>
                            </v-flex>
                        </v-layout>
                    </v-container>

                    <v-card-text>
                        <v-text-field name="email" id="email" type="email"
                            v-model="form.email"
                            :label="$t('E-Mail Address')"
                            :rules="emailRules"
                        ></v-text-field>
                        <v-text-field name="password" id="password" type="password"
                            v-model="form.password"
                            :label="$t('Password')"
                            
                        ></v-text-field>
                    </v-card-text>

                    <v-card-actions>
                        <v-flex>
                            <span class="caption"><router-link to="/password/reset">{{ $t("Forgot password?") }}</router-link></span>
                        </v-flex>
                        <v-spacer></v-spacer>
                        <v-btn right color="primary" flat @click="login">{{ $t("Sign In") }}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>

            <v-flex xs12 sm10 md8 lg6>
                <v-card flat>
                    <v-card-text>
                        <span class="body-1">{{ $t("You don't have an account?") }}</span>
                            <router-link to="/register"><span class="body-2">{{ $t("Register") }}</span></router-link>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </main-layout>
</template>
