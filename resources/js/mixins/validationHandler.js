import unset from "lodash/unset";

export default {
  data() {
    return {
      errors: {},
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

  methods: {
    syncErrors(error) {
      if (typeof error !== 'undefined') {
        if (typeof error.response !== 'undefined' && error.hasOwnProperty('response') && error.response.hasOwnProperty('data')) {
          if (error.response.data.hasOwnProperty('errors')) {
            this.errors = Object.assign({}, error.response.data.errors);

            for (const key of Object.entries(this.errors)) {
              if (typeof this.$refs[key] !== 'undefined') {
                this.$refs[key].valid = false;
              }
            }

            setTimeout(() => {
              this.focusFirstErrorInput();
            }, 500);
          }
        } else {
          console.log(error);
        }
      }
    },

    clearErrors() {
      this.errors = {};
    },

    clearError(field) {
      if (this.hasErrors(field)) {
        unset(this.errors, field);
        this.errors = Object.assign({}, this.errors, this.errors);
        this.$refs[field].valid = true;
      }
    },

    focusFirstErrorInput() {
      for (let [key, value] of Object.entries(this.form)) {
        if (!this.$refs[key].valid) {
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
