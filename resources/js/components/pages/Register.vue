<script>

import FormErrors from '../partials/FormErrors.vue';
import { mapActions } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'Register',

  components: {
    FormErrors,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
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
        (v) => !!v || v.length >= 8 || this.$t("Password must be more than 8 characters")
      ],
    }
  },

  methods: {
    ...mapActions([
      'register',
    ]),

    /**
     * Create a new User.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.isLoading = true;

        this.register(this.form)
          .then(() => {
            this.$router.push({ name: 'Home' });
          })
          .catch((error) => {
            this.form.onFail(error.response.data);
          })
          .finally(() => {
            this.isLoading = false;
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
                <div class="headline mb-0">{{ $t("Register") }}</div>
            </v-card-title>
            <v-form v-model="valid" ref="form" @keyup.native.enter="submit">
              <v-card-text>
                <form-errors :errors="form.errors" />
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
              </v-card-text>
            </v-form>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="primary" :loading="isLoading" @click="submit">{{ $t("Register") }}</v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
        <v-flex xs12 sm6 md4>
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
