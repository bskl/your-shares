<template>
  <v-layout align-center>
    <v-flex text-center>
      <h1 class="display-4 red--text text--lighten-2">404</h1>
      <p class="red--text text--lighten-2">{{ $t("Page not found.") }}</p>
      <v-btn to="/" class="btn-custom">
        {{ $t("Return Home Page") }}
      </v-btn>
    </v-flex>
  </v-layout>
</template>
