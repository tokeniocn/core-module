const mix = require("laravel-mix");
require("laravel-mix-merge-manifest");

let publicPath = "../public";

if (__dirname.indexOf("/modules/") >= 0) {
  publicPath = "../../public";
} else if (__dirname.indexOf("/vendor/") >= 0) {
  publicPath = "../../../public";
}

mix.setPublicPath(publicPath).mergeManifest();

mix
  .copy("resources/assets/vendor", publicPath + "/vendor")
  .copy("resources/assets/images", publicPath + "/images")
  .js(__dirname + "/resources/assets/js/admin/app.js", "js/admin.js")
  .sass(__dirname + "/resources/assets/sass/admin/app.scss", "css/admin.css")

  .extract(["vue", "vuex", "axios", "lodash-es", "moment", "quasar"])
  .version()
  .sourceMaps();
