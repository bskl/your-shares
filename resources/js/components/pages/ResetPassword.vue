<script>

import { mapActions } from 'vuex';
import { parseSuccessMessage } from '../../utilities/helpers.js';
import validationHandler from '../../mixins/validationHandler.js';
import loadingHandler from '../../mixins/loadingHandler.js';
import FormErrors from '../partials/FormErrors.vue';

export default {
  /**
   * The component's name.
   */
  name: 'ResetPassword',

  mixins: [
    validationHandler,
    loadingHandler,
  ],

  components: {
    FormErrors,
  },

  /**
   * The component's data.
   */
  data() {
    return {
      waitFor: 'reset_password',
      form: {
        email: '',
        password: '',
        password_confirmation: '',
        token: this.$route.params.reset_password_code,
      },
      valid: true,
    }
  },

  methods: {
    ...mapActions([
      'resetPassword',
    ]),

    /**
     * Sends password reset email to user.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.startLoading();

        this.resetPassword(this.form)
          .then((res) => {
            this.clearErrors();
            parseSuccessMessage(res);
            this.$router.push({ name: 'Login' });
          })
          .catch((error) => {
            this.syncErrors(error);
          })
          .finally(() => {
            this.stopLoading();
          });
      } else {
        this.focusFirstErrorInput();
      }
    },
  }
}
</script>

<template>
  <v-row align="center" justify="center">
    <v-col cols="12" sm="8" md="4">
      <v-card>
        <v-card-title flat class="pl-2">
          <span class="title font-weight-light">{{ $t("Reset Password") }}</span>
        </v-card-title>
        <v-card-text>
          <v-form v-model="valid" ref="form" lazy-validation
            @keyup.native.enter="submit"
            @keydown.native="clearError($event.target.name)"
          >
            <form-errors :errors="errors" />
            <input type="hidden" name="token" ref="token" id="token"
              v-model="form.token"
            />
            <v-text-field type="email" name="email" ref="email" id="email" filled
              prepend-inner-icon="person"
              v-model="form.email"
              :disabled="isLoading"
              :label="$t('E-Mail Address')"
              :rules="[rules.required, rules.email]"
              :error-messages="getError('email')"
            ></v-text-field>
            <v-text-field type="password" name="password" ref="password" id="password" filled
              prepend-inner-icon="lock"
              v-model="form.password"
              :disabled="isLoading"
              :label="$t('Password')"
              :rules="[rules.required, rules.gte(8)]"
              :error-messages="getError('password')"
            ></v-text-field>
            <v-text-field type="password" name="password_confirmation" ref="password_confirmation" id="password_confirmation" filled
              prepend-inner-icon="lock"
              v-model="form.password_confirmation"
              :disabled="isLoading"
              :label="$t('Confirm Password')"
              :rules="[rules.required, rules.gte(8), rules.confirmed(form.password)]"
              :error-messages="getError('password_confirmation')"
            ></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions class="pb-4 pr-4">
          <v-spacer></v-spacer>
          <v-progress-circular v-show="isLoading" indeterminate />
          <v-btn class="btn-action"
            :disabled="isLoading" 
            @click="submit"
          >
            {{ $t("Reset Password") }}
          </v-btn>
        </v-card-actions>
        <v-expand-transition>
          <div style="background-color: #323639;">
            <v-card-text class="pl-4 pa-6">
              {{ $t("You don't have an account?") }}
              <router-link to="/register" class="link-custom">{{ $t("Register") }}</router-link>
              &nbsp;{{ $t("or") }}&nbsp;
              <router-link to="/login" class="link-custom">{{ $t("Sign In") }}</router-link>
            </v-card-text>
          </div>
        </v-expand-transition>
      </v-card>
    </v-col>
  </v-row>
</template>
