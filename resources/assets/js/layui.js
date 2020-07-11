import "layui-src/src/layui";
import "layui-src/src/lay/all";
import "layui-src/src/lay/modules/jquery";

window.jQuery = window.$ = layui.$; // jQuery优先加载到全局

const { addcss } = layui;
layui.addcss = () => this; // 不处理内部的css加载请求;

// 排除mobile和jquery两个模块
const files = require.context(
  "layui-src/src/lay/modules",
  false,
  /^(?!.*(?:mobile.js|jquery.js$)).*\.js$/i
);
files.keys().forEach((key) => files(key));

layui.addcss = addcss; // 所有类加载完后再还原

// ajax 基础设定
$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
  },

  error: window.$errorHandler
    ? (jqXHR, textStatus, errorMsg) => {
        // 模拟 axios错误
        $errorHandler({
          response: {
            data: jqXHR.responseJSON,
          },
        });
      }
    : undefined,
});

// // 初始化设置
layui
  .config({
    base: "/vendor/", // 静态资源所在路径
  })
  .extend({
    editormd: "../editormd/editormd.min",
  });
