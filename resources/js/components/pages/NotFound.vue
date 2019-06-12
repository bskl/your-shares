<template>
  <v-layout row wrap align-center>
    <v-flex text-xs-center>
      <h1 class="display-4 font-weight-bold">404</h1>
      <h4 class="display-1">{{ $t('Page not found.')}}</h4>
      <v-btn flat color="info" to="/">{{ $t("Return Home Page") }}</v-btn>
    </v-flex>
  </v-layout>
</template>
