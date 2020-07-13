import Vue from "vue";
import VueMoment from "vue-moment";
import VueClipboard from "vue-clipboard2";
import VueQrcode from "@chenfengyuan/vue-qrcode";
import VueMarkdownEditor from "mavon-editor";

Vue.use(VueMoment);

Vue.use(VueClipboard);
VueClipboard.config.autoSetContainer = true; // add this line

Vue.component(VueQrcode.name, VueQrcode);
Vue.use(VueMarkdownEditor);
