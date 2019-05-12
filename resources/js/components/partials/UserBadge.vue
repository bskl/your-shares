<script type="text/ecmascript-6">

import { mapGetters } from 'vuex';

export default {
    /*
     * The component's name.
     */
    name: 'UserBadge',

    computed: {
        ...mapGetters([
            'isLoggedIn',
        ])
    },

    methods: {
        logout() {
            this.$store.dispatch('logout')
                .then((res) => {
                    this.$router.push('/login');
                })
                .catch(() => {
                });
        },
    }
}
</script>

<template>
  <div>
    <v-menu offset-y v-if="isLoggedIn">
      <v-toolbar-title slot="activator">
        <v-avatar>
          <v-icon large dark>account_circle</v-icon>
        </v-avatar>
        <!--<span class="subheading">{{ state.user.email }}</span>-->
        <v-icon dark>more_vert</v-icon>
      </v-toolbar-title>
      <v-list dense>
          <v-list-tile :to="'/portfolio/create'">
            <v-icon class="pr-2">add</v-icon>
            <v-list-tile-title>
              {{ $t("Add Portfolio") }}
            </v-list-tile-title>
          </v-list-tile>
        <v-divider></v-divider>
        <v-list-tile @click="logout()">
          <v-icon class="pr-2">exit_to_app</v-icon>
          <v-list-tile-title>
            {{ $t("Logout") }}
          </v-list-tile-title>
        </v-list-tile>
      </v-list>
    </v-menu>
    <v-btn flat to="/register" v-else>{{ $t("Register") }}</v-btn>
  </div>
</template>
