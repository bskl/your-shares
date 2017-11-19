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
                valid: true,
                emailRules: [
                    (v) => !!v || this.$t("E-mail is required"),
                    (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || this.$t("E-mail must be valid")
                ],
                passwordRules: [
                    (v) => !!v || this.$t("Password is required"),
                    (v) => !!v || v.length >= 6 || this.$t("Password must be more than 6 characters")
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
                if (this.$refs.form.validate()) {
                    this.form.post('/login')
                        .then(response => {
                             if (response.status === 200) {
                                this.$router.go('/');
                            }
                        });
                }
            },
        }
    }
</script>

<template>
    <v-layout row wrap justify-center>
        <v-flex xs12 sm6 md4>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card>
                        <v-card-title>
                            <div class="headline mb-0">{{ $t("Sign In") }}</div>
                        </v-card-title>
                        <v-form v-model="valid" ref="form">
                            <v-card-text>
                                <form-errors :errors="form.errors" />
                                <v-text-field name="email" id="email" type="email"
                                    v-model="form.email"
                                    :label="$t('E-Mail Address')"
                                    :rules="emailRules"
                                    required
                                ></v-text-field>
                                <v-text-field name="password" id="password" type="password"
                                    v-model="form.password"
                                    :label="$t('Password')"
                                    :rules="passwordRules"
                                    required
                                ></v-text-field>
                                <div class="text-sm-right">
                                    <v-btn flat small to="/password/reset" class="ma-0">{{ $t("Forgot password?") }}</v-btn>
                                </div>
                            </v-card-text>
                        </v-form>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="login">{{ $t("Sign In") }}</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
                <v-flex xs12>
                    <v-card>
                        <v-card-text>
                            <span>{{ $t("You don't have an account?") }}</span>
                            <router-link to="/register">{{ $t("Register") }}</router-link>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-flex>
    </v-layout>
</template>
