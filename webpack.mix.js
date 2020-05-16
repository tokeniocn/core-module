const mix = require("laravel-mix");
require("laravel-mix-merge-manifest");

let publicPath = "../../../public"; // 默认在vendor包中

if (__dirname.indexOf("/modules/") >= 0) {
  publicPath = "../../public";
}

mix.setPublicPath(publicPath).mergeManifest();

mix
  .js(__dirname + "/resources/assets/js/admin/app.js", "js/admin.js")
  .sass(__dirname + "/resources/assets/sass/admin/app.scss", "css/admin.css")
  .copy("resources/assets/vendor", publicPath + "/vendor")

  .extract(["vue", "vuex", "axios", "lodash-es", "moment", "quasar"])
  .version()
  .sourceMaps();
