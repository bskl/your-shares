<script type="text/ecmascript-6">
import { userStore } from '../../stores/userStore.js';
import MainLayout from '../layout/MainLayout.vue';
import FormErrors from '../partials/FormErrors.vue';

export default {
    /*
     * The component's name.
     */
    name: 'ForgotPasswordForm',

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
            valid: true,
            emailRules: [
                (v) => !!v || this.$t("E-mail is required"),
                (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || this.$t("E-mail must be valid")
            ],
        }
    },

    mounted() {
        if (userStore.isAuthenticated()) {
            Bus.$emit('userLoggedIn');
            this.$router.push('/');
        }
    },

    methods: {
        /**
         * Sends password reset email to user.
         */
        passwordResetEmail() {
            if (this.$refs.form.validate()) {
                this.form.post('/password/email')
                    .then(response => {
                        if (response.status === 200) {
                            this.$root.snackbar.show = true;
                            this.$root.snackbar.text = response.data;
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
                <h3 class="headline mb-0">{{ $t("Reset Password") }}</h3>
              </div>
            </v-card-title>
            <v-form v-model="valid" ref="form">
              <v-card-text>
                <form-errors :errors="form.errors" />
                <v-text-field
                  name="email"
                  id="email"
                  type="email"
                  v-model="form.email"
                  :label="$t('E-Mail Address')"
                  :rules="emailRules"
                  required
                ></v-text-field>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="passwordResetEmail">{{
                  $t("Send Password Reset Link")
                }}</v-btn>
              </v-card-actions>
            </v-form>
          </v-card>
        </v-flex>
        <v-flex xs12>
          <v-card>
            <v-card-text>
              <span>{{ $t("You don't have an account?") }}</span>
              <router-link to="/register">{{ $t("Register") }}</router-link>
              <span> {{ $t("or") }} </span>
              <router-link to="/login">{{ $t("Sign In") }}</router-link>
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>
