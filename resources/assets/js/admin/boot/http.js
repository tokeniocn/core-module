import Vue from "vue";
import axios from "axios";

import $config from "../config";

const $http = axios.create({
  timeout: 20000,
  baseURL: $config.api.base,
});

$http.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
Vue.prototype.$http = $http;

export default $http;
