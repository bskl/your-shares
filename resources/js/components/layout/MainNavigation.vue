<script>

import { mapState, mapGetters, mapActions } from "vuex";

export default {
  /**
   * The component's name.
   */
  name: 'MainNavigation',

  data() {
    return {
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
      ],
    };
  },

  computed: {
    ...mapState([
      'user', 'navDrawer',
    ]),

    ...mapGetters([
      'isLoggedIn',
    ])
  },

  methods: {
    ...mapActions([
      'toggleNavDrawer',
    ]),
  }
};
</script>

<template>
  <v-navigation-drawer app clipped right 
    :value="navDrawer"
    v-if="isLoggedIn"
  >
    <template v-slot:prepend>
      <v-list-item two-line>
        <v-list-item-avatar>
          <v-icon medium>account_circle</v-icon>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title>{{ user.name }}</v-list-item-title>
          <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </template>

    <v-divider></v-divider>

    <v-list dense>
      <v-list-item link
        v-for="item in items"
        :key="item.title"
        :to="item.to"
        @click="toggleNavDrawer"
      >
        <v-list-item-icon>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title>{{ $t(`${item.title}`) }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>

    <template v-slot:append>
      <div class="pa-2">
        <v-btn block class="btn-action" to="/logout">{{ $t('Logout')}}</v-btn>
      </div>
    </template>
  </v-navigation-drawer>
</template>
