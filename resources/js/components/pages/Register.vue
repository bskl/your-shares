<script>

import { mapActions } from 'vuex';
import validationHandler from '../../mixins/validationHandler.js';
import loadingHandler from '../../mixins/loadingHandler.js';
import FormErrors from '../partials/FormErrors.vue';

export default {
  /**
   * The component's name.
   */
  name: 'Register',

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
      waitFor: 'register',
      form: {
        email: '',
        password: '',
        password_confirmation: '',
      },
      valid: true,
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
        this.startLoading();

        this.register(this.form)
          .then(() => {
            this.clearErrors();
            this.$router.push({ name: 'Home' });
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
};
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
          <span class="title font-weight-light">{{ $t("Register") }}</span>
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
            <v-text-field
              id="email"
              ref="email"
              v-model="form.email"
              type="email"
              name="email"
              filled
              clearable
              prepend-inner-icon="person"
              :disabled="isLoading"
              :label="$t('E-Mail Address')"
              :rules="[rules.required, rules.email]"
              :error-messages="getError('email')"
            />
            <v-text-field
              id="password"
              ref="password"
              v-model="form.password"
              type="password"
              name="password"
              filled
              clearable
              prepend-inner-icon="lock"
              :disabled="isLoading"
              :label="$t('Password')"
              :rules="[rules.required, rules.gte(8)]"
              :error-messages="getError('password')"
            />
            <v-text-field
              id="password_confirmation"
              ref="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              name="password_confirmation"
              filled
              clearable
              prepend-inner-icon="lock"
              :disabled="isLoading"
              :label="$t('Password Confirmation')"
              :rules="[rules.required, rules.gte(8)]"
              :error-messages="getError('password')"
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
            {{ $t("Register") }}
          </v-btn>
        </v-card-actions>
        <v-expand-transition>
          <div style="background-color: #323639;">
            <v-card-text class="pl-4 pa-6">
              {{ $t("Already have an account?") }}
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
