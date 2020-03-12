<template>
  <v-container class="fill-height">
    <v-row align="center" justify="center">
      <div class="spinner" role="spinner">
        <div class="spinner-icon"></div>
        </div>
    </v-row>
  </v-container>
</template>

<style>
.spinner {
  display: block;
  position: fixed;
  z-index: 1031;
}
.spinner-icon {
  width: 32px;
  height: 32px;
  box-sizing: border-box;

  border: solid 2px transparent;
  border-top-color: #00ff7f;
  border-left-color: #00ff7f;
  border-radius: 50%;

  -webkit-animation: spinner 400ms linear infinite;
          animation: spinner 400ms linear infinite;
}
@-webkit-keyframes spinner {
  0%   { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}
@keyframes spinner {
  0%   { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>