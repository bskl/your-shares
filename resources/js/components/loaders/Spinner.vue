<template>
  <i class="loader"></i>
</template>

<style>
.loader {
  width: 30px;
  height: 30px;
  box-sizing: border-box;
  border: solid 2px transparent;
  border-top-color: #29d;
  border-left-color: #29d;
  border-radius: 50%;
  display: inline-block;
  position: relative;
  left: 50%;
  margin: 30px 0px 30px -20px;
  animation: spinner 400ms linear infinite;
}

@keyframes spinner {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
