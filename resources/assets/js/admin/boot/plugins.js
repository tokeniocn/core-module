import Vue from "vue";
import VueMoment from "vue-moment";
import VueClipboard from "vue-clipboard2";
import VueQrcode from "@chenfengyuan/vue-qrcode";

import MarkdownEditor from "../components/Markdown/Editor";

Vue.use(VueMoment);

Vue.use(VueClipboard);
VueClipboard.config.autoSetContainer = true; // add this line

Vue.component("qrcode", VueQrcode);
Vue.component("markdown-editor", MarkdownEditor);

console.log(Vue.options);
