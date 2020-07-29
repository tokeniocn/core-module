import _ from "lodash";
import Vue from "vue";
import clipboard from "clipboard";
import axios from "axios";
import moment from "moment";
import qrcode from "qrcode";
import quasar from "quasar";

import $config from "../config";
import $http from "./http";
import { errorHandler } from "./handler";

window._ = _;
window.Vue = Vue;
window.quasar = quasar;
window.axios = axios;
window.moment = moment;
window.qrcode = qrcode;
window.clipboard = clipboard;

window.$config = $config;
window.$http = $http;
window.$errorHandler = errorHandler;
const headers = {
  "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
};
$http.defaults.headers.common = {
  ...headers,
  ...$http.defaults.headers.common,
};

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
