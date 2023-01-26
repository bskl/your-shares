<template>
  <v-layout align-center>
    <v-flex text-center>
      <h1 class="display-4 red--text text--lighten-2">
        408
      </h1>
      <p class="red--text text--lighten-2">
        {{ $t("The page timed out while loading. Are you sure you're still connected to the Internet?") }}
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
