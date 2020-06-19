import Vue from "vue";
import "./config";
import "./boot/http";
import "./boot/plugins";
import "./boot/mixin";
import "./boot/component";
import store from "./store";
import "./boot/quasar";

import "./outer"; // 外部layui兼容实现

const app = new Vue({
  el: "#LAY_app",
  store,
});

export default app;
