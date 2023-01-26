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
  name: 'ForgotPassword',

  components: {
    FormErrors,
  },

  mixins: [
    validationHandler,
    loadingHandler,
  ],

  /**
   * The component's data.
   */
  data() {
    return {
      waitFor: 'send_reset_password_email',
      form: {
        email: '',
      },
      valid: true,
    }
  },

  methods: {
    ...mapActions([
      'sendResetPasswordEmail',
    ]),

    /**
     * Sends password reset email to user.
     */
    submit() {
      if (this.$refs.form.validate()) {
        this.startLoading();

        this.sendResetPasswordEmail(this.form)
          .then((res) => {
            this.clearErrors();
            parseSuccessMessage(res);
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
  },
}
</script>

<template>
  <v-row
    align="center"
    justify="center"
  >
    <v-col
      cols="12"
      sm="8"
      md="4"
    >
      <v-card>
        <v-card-title>
          <span class="title font-weight-light">{{ $t("Reset Password") }}</span>
        </v-card-title>
        <v-card-text>
          <v-form
            ref="form"
            v-model="valid"
            lazy-validation
            @keyup.native.enter="submit"
            @keydown.native="clearError($event.target.name)"
          >
            <form-errors :errors="errors" />
            <input
              id="token"
              ref="token"
              v-model="form.token"
              type="hidden"
              name="token"
            >
            <v-text-field
              id="email"
              ref="email"
              v-model="form.email"
              type="email"
              name="email"
              filled
              prepend-inner-icon="person"
              :disabled="isLoading"
              :label="$t('E-Mail Address')"
              :rules="[rules.required, rules.email]"
              :error-messages="getError('email')"
            />
          </v-form>
        </v-card-text>
        <v-card-actions class="pb-4 pr-4">
          <v-spacer />
          <v-progress-circular
            v-show="isLoading"
            indeterminate
          />
          <v-btn
            class="btn-action"
            :disabled="isLoading"
            @click="submit"
          >
            {{ $t("Send Password Reset Link") }}
          </v-btn>
        </v-card-actions>
        <v-expand-transition>
          <div style="background-color: #323639;">
            <v-card-text class="pl-4 pa-6">
              {{ $t("You don't have an account?") }}
              <router-link
                to="/register"
                class="link-custom"
              >
                {{ $t("Register") }}
              </router-link>
              &nbsp;{{ $t("or") }}&nbsp;
              <router-link
                to="/login"
                class="link-custom"
              >
                {{ $t("Sign In") }}
              </router-link>
            </v-card-text>
          </div>
        </v-expand-transition>
      </v-card>
    </v-col>
  </v-row>
</template>
