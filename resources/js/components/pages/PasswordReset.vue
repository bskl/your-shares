<script>

import FormErrors from '../partials/FormErrors.vue';
import { mapActions } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'PasswordReset',

  components: {
    FormErrors,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      form: new Form({
        token: this.$route.params.reset_password_code,
        email: '',
        password: '',
        password_confirmation: '',
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

  methods: {
    ...mapActions([
      'passwordReset', 'setSnackbar',
    ]),

    /**
     * Sends password reset email to user.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.isLoading = true;

        this.passwordReset(this.form)
          .then((res) => {
            this.setSnackbar({ text: res });
            this.$router.push({ name: 'Login' });
          })
          .catch((error) => {
            this.form.onFail(error.response.data);
          })
          .finally(() => {
            this.isLoading = false;
          });;
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
                <div class="headline mb-0">{{ $t("Reset Password") }}</div>
            </v-card-title>
            <v-form v-model="valid" ref="form" @keyup.native.enter="submit">
              <v-card-text>
                <form-errors :errors="form.errors" />
                <input type="hidden" name="token" id="token" required
                  v-model="form.token"
                />
                <v-text-field type="email" name="email" id="email" required
                  v-model="form.email"
                  :label="$t('E-Mail Address')"
                  :rules="emailRules"
                ></v-text-field>
                <v-text-field type="password" name="password" id="password" required
                  v-model="form.password"
                  :label="$t('Password')"
                  :rules="passwordRules"
                ></v-text-field>
                <v-text-field type="password" name="password_confirmation" id="password_confirmation" required
                  v-model="form.password_confirmation"
                  :label="$t('Confirm Password')"
                  :rules="passwordRules"
                ></v-text-field>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" :loading="isLoading" @click="submit">{{ $t("Reset Password") }}</v-btn>
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
