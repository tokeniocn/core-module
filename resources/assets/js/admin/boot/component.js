// import { kebabCase, endsWith } from "lodash";
import Vue from "vue";
import LayoutMain from "../components/Layout/Main";
import GeneratorList from "../components/Generator/List";

Vue.component("layout-main", LayoutMain);
Vue.component("generator-list", GeneratorList);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context("./components", true, /\.vue$/i);
// files.keys().forEach((key) => {
//   if (endsWith(key, ".vue")) {
//     const path = key.replace(".vue", "");
//     const name = kebabCase(path);

//     if (process.env.NODE_ENV !== "production") {
//       console.log(`Init Component [${name}] from ${key}`);
//     }

//     Vue.component(name, files(key).default);
//   }
// });
