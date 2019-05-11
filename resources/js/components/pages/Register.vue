<script type="text/ecmascript-6">
import { mapActions } from 'vuex';
import FormErrors from '../partials/FormErrors.vue';

export default {
    /*
     * The component's name.
     */
    name: 'Register',

    components: {
        FormErrors,
    },

    /*
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

    mounted() {

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
                        this.$router.push('/');
                    })
                    .catch((error) => {
                        this.form.onFail(error.response.data)
                    })
                    .finally(() => this.isLoading = false);
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
            <v-form v-model="valid" ref="form" @keyup.native.enter="submit">
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
                <v-text-field
                  name="password"
                  id="password"
                  type="password"
                  v-model="form.password"
                  :label="$t('Password')"
                  :rules="passwordRules"
                  required
                ></v-text-field>
              </v-card-text>
            </v-form>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="primary" :loading="isLoading"  @click="submit">
                {{ $t("Register") }}
              </v-btn>
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
