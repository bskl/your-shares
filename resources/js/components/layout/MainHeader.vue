<script>

import { mapState, mapGetters } from "vuex";

export default {
  /**
   * The component's name.
   */
  name: 'MainHeader',

  data() {
    return {
      drawer: false,
      items: [
        {
          to: '/',
          title: 'Home',
          icon: 'home',
        },
        {
          to: '/portfolios/create',
          title: 'Add Portfolio',
          icon: 'add_circle_outline',
        },
        {
          to: '/logout',
          title: 'Logout',
          icon: 'logout',
        },
      ],
    };
  },

  computed: {
    ...mapState([
      'user',
    ]),

    ...mapGetters([
      'isLoggedIn',
    ])
  },
};
</script>

<template>
  <v-sheet>
    <v-app-bar
      app
      clipped-right
      elevate-on-scroll
      class="seperator-line"
    >
      <v-toolbar-title>{{ $t("Shares") }}</v-toolbar-title>
      <v-spacer />
      <v-app-bar-nav-icon
        v-if="isLoggedIn"
        @click.stop="drawer = !drawer"
      />
    </v-app-bar>
    <v-navigation-drawer
      v-if="isLoggedIn"
      v-model="drawer"
      app
      clipped
      right
      fixed
    >
      <template #prepend>
        <v-list-item two-line>
          <v-list-item-avatar>
            <v-icon medium>
              account_circle
            </v-icon>
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title>{{ user.name }}</v-list-item-title>
            <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </template>

      <v-divider />

      <v-list dense>
        <v-list-item
          v-for="item in items"
          :key="item.title"
          link
          :to="item.to"
          @click.stop="drawer = false"
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ $t(`${item.title}`) }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </v-sheet>
</template>
