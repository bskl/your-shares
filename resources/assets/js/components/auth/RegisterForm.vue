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
             * Create a new User.
             */
            register() {
                if (this.$refs.form.validate()) {
                    this.form.post('/register')
                        .then(response => {
                            if (response.status === 200) {
                                this.$router.go('/')
                            }
                        })
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
                            <div>
                                <h3 class="headline mb-0">{{ $t("Register") }}</h3>
                            </div>
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
                            </v-card-text>
                        </v-form>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="register">{{ $t("Register") }}</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
                <v-flex xs12>
                    <v-card>
                        <v-card-text>
                            <span>{{ $t("Already have an account?") }}</span>
                            <router-link to="/login">{{ $t("Sign In") }}</router-link>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-flex>
    </v-layout>
</template>
