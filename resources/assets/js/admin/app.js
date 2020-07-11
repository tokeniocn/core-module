import Vue from "vue";
import "./config";
import "./boot/quasar";
import "./boot/http";
import "./boot/plugins";
import "./boot/mixin";
import "./boot/component";
import store from "./store";
import "./boot/outer";

const app = new Vue({
  el: "#app",
  store,
});

export default app;
