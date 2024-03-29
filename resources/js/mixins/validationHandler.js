import { mapState, mapActions } from 'vuex';
import router from "../router";
import store from '../store';
import includes from 'lodash/includes';

export default {
  data() {
    return {
      rules: {
        required: value => {
          return (!!value || value === 0) || this.$t("rules.required");
        },
        email: value => {
          const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          return pattern.test(value) || this.$t("rules.email");
        },
        confirmed: password => value => {
          return password == value || this.$t("rules.confirmed");
        },
        gte: other => value => {
          return (value && value.length >= other) || this.$t("rules.gte", { length: other });
        },
      }
    }
  },

  computed: {
    ...mapState([
      'errors',
    ]),
  },

  methods: {
    ...mapActions([
      'setShowModal', 'setErrors', 'unsetError',
    ]),

    syncErrors(error) {
      if (typeof error !== 'undefined') {
        if (typeof error.response !== 'undefined' && error.hasOwnProperty('response')) {
          if (includes([400, 401, 403, 404, 419], error.response.status)) {
            this.setShowModal(false);

            if (includes([400, 401], error.response.status)) {
              store.commit('LOGGED_OUT');
              router.push({ name: 'Login' });
            } else {
              router.push(`/${error.response.status}`);
            }
          } else if (error.response.hasOwnProperty('data') && error.response.data.hasOwnProperty('errors')) {
            this.setErrors(error.response.data.errors);

            for (const key of Object.entries(this.errors)) {
              if (typeof this.$refs[key] !== 'undefined') {
                this.$refs[key].valid = false;
              }
            }

            setTimeout(() => {
              this.focusFirstErrorInput();
            }, 500);
          } else {
            this.setShowModal(false);
            router.push({ name: 'NotFound' });
          }
        } else {
          console.log(error);
        }
      }
    },

    clearErrors() {
        this.setErrors({});
    },

    clearError(field) {
      if (this.hasErrors(field)) {
        this.unsetError(field);
        this.$refs[field].valid = true;
      }
    },

    focusFirstErrorInput() {
      for (let [key, value] of Object.entries(this.form)) {
        if (typeof this.$refs[key] !== 'undefined' && !this.$refs[key].valid) {
          this.$refs[key].focus();
          break;
        }
      }
    },

    hasErrors(field) {
      return this.errors.hasOwnProperty(field);
    },

    getError(field) {
      return this.hasErrors(field) ? this.errors[field][0] : null;
    },

    getErrors(field) {
      return this.hasErrors(field) ? this.errors[field] : null;
    },
  },
}
