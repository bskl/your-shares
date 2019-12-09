<script>

import FormErrors from '../partials/FormErrors.vue';
import validationHandler from '../../mixins/validationHandler';
import { mapActions } from 'vuex';

export default {
  /**
   * The component's name.
   */
  name: 'Login',

  mixins: [validationHandler],
  
  components: {
    FormErrors,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      isLoading: false,
      form: {
        email: '',
        password: '',
      },
      valid: true,
    }
  },

  computed: {
    sendTo() {
      const { redirect } = this.$route.query;
      if (redirect !== undefined ) return { path: redirect };
      return { name: 'Home' };
    }
  },

  methods: {
    ...mapActions([
      'login',
    ]),

    /**
     * Login User.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.isLoading = true;

        this.login(this.form)
          .then(() => {
            this.clearErrors();
            this.$router.push(this.sendTo);
          })
          .catch((error) => {
            this.syncErrors(error);
          })
          .finally(() => {
            this.isLoading = false;
          });
      } else {
        this.focusFirstErrorInput();
      }
    },
  },
}
</script>

<template>
  <v-row align="center" justify="center">
    <v-col cols="12" sm="8" md="4">
      <v-card>
        <v-toolbar flat class="pl-2">
          <v-toolbar-title>{{ $t("Sign In") }}</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-form v-model="valid" ref="form" lazy-validation
            @keyup.native.enter="submit"
            @keydown.native="clearError($event.target.name)"
          >
            <form-errors :errors="errors" />
            <v-text-field type="email" name="email" ref="email" id="email" outlined clearable
              prepend-icon="person"
              v-model="form.email"
              :disabled="isLoading"
              :label="$t('E-Mail Address')"
              :rules="[rules.required, rules.email]"
              :error-messages="getError('email')"
            />
            <v-text-field type="password" name="password" ref="password" id="password" outlined clearable
              prepend-icon="lock"
              v-model="form.password"
              :disabled="isLoading"
              :label="$t('Password')"
              :rules="[rules.required, rules.gte(6)]"
              :error-messages="getError('password')"
            />
          </v-form>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="pa-4">
          <router-link to="/password/reset" class="link-custom">{{ $t("Forgot password?") }}</router-link>
          <v-spacer></v-spacer>
          <v-progress-circular v-show="isLoading" indeterminate color="rgba(89, 135, 209, 1)" width="3" size="30" />
          <v-btn class="btn-custom"
            :disabled="isLoading" 
            @click="submit"
          >
            {{ $t("Sign In") }}
          </v-btn>
        </v-card-actions>
        <v-expand-transition>
          <div style="background-color: #323639;">
            <v-card-text class="pl-4 pa-6">
              {{ $t("You don't have an account?") }}
              <router-link to="/register" class="link-custom">{{ $t("Register") }}</router-link>
            </v-card-text>
          </div>
        </v-expand-transition>
      </v-card>
    </v-col>
  </v-row>
</template>
