<template>
  <v-layout align-center>
    <v-flex text-center>
      <h1 class="display-4 red--text text--lighten-2">
        419
      </h1>
      <p class="red--text text--lighten-2">
        {{ $t("Sorry, your session has expired. Please refresh and try again.") }}
      </p>
      <v-btn
        to="/"
        class="btn-action"
      >
        {{ $t("Return Home Page") }}
      </v-btn>
    </v-flex>
  </v-layout>
</template>
