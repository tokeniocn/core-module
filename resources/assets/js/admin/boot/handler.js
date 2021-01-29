import Vue from "vue";
import $store from "../store";

export function errorHandler(e, vm) {
  console.error(e);

  let message = "服务器开小差了，请联系管理员！";
  if (typeof e === "string") {
    // error错误
    message = e;
  } else if (e.errors) {
    //async-validate 错误
    message = e.errors[0].message || "验证失败";
  } else if (e.message && !e.response) {
    // 逻辑错误
    message = e.message;
  } else if (e.status == 401) {
    message = "请先登录";
    // 未登录 跳转登录
    setTimeout(() => {
      window.location.href = "/admin";
    }, 2000);
  } else if (e.response && e.response.data.errors) {
    // 服务器返回validate错误
    const errors = Object.values(e.response.data.errors);
    message = errors[0][0] || "服务器验证失败";
  } else if (e.response && e.response.data.message) {
    // 服务器返回错误
    message = e.response.data.message || "服务器错误";
  }

  $store.dispatch("showNotify", message);
}

Vue.config.errorHandler = window.onerror = errorHandler;
