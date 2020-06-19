import $config from "./config";
import $http from "./boot/http";
import _ from "lodash";
import Vue from "vue";
import axios from "axios";
import moment from "moment";
import { errorHandler } from "./boot/handler";

window._ = _;
window.Vue = Vue;
window.axios = axios;
window.moment = moment;

window.$config = $config;
window.$http = $http;
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

/**
 * layui-admin
 */

// 初始化设置
layui
  .config({
    base: "/vendor/layui/", // 静态资源所在路径
  })
  .extend({})
  .use(["jquery"], function() {
    const $ = layui.$;
    window.jQuery || (window.jQuery = $);

    // iframe 下隐藏
    if (top == window) {
      $("[lay-iframe-hide]").show();
    }

    // ajax 基础设定
    $.ajaxSetup({
      headers,
      // dataFilter: function(data, type) {
      //   console.log('dataFilter', arguments);
      //   return data;
      // },
      error: function(jqXHR, textStatus, errorMsg) {
        // 模拟 axios错误
        errorHandler({
          response: {
            data: jqXHR.responseJSON,
          },
        });
      },
    });
  });
