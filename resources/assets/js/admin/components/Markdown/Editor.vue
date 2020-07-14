<template>
  <div ref="toastuiEditor" />
</template>

<script>
// deps for editor
import "codemirror/lib/codemirror.css"; // codemirror
import "@toast-ui/editor/dist/toastui-editor.css"; // editor ui
// import "@toast-ui/editor/dist/i18n/zh-cn";
import Editor from "@toast-ui/editor";
import defaultOptions from "./options";
export default {
  name: "MarkdownEditor",
  props: {
    value: {
      type: String,
      default: "",
    },
    previewStyle: {
      type: String,
      default: "vertical",
    },
    height: {
      type: String,
      default: "300px",
    },
    initialEditType: {
      type: String,
      default: "markdown",
    },
    options: {
      type: Object,
      default: () => defaultOptions,
    },
  },
  data() {
    return {
      editor: null,
    };
  },
  computed: {
    editorOptions() {
      return Object.assign({}, defaultOptions, {
        ...this.options,
        height: this.height,
        previewStyle: this.previewStyle,
        initialEditType: this.initialEditType,
        initialValue: this.value,
      });
    },
  },
  watch: {
    value(newValue, preValue) {
      if (newValue !== preValue && newValue !== this.getValue()) {
        this.editor.setValue(newValue);
      }
    },
    language(val) {
      this.destroyEditor();
      this.initEditor();
    },
    height(newValue) {
      this.editor.height(newValue);
    },
    mode(newValue) {
      this.editor.changeMode(newValue);
    },
  },
  mounted() {
    this.initEditor();
  },
  destroyed() {
    this.destroyEditor();
  },
  methods: {
    initEditor() {
      console.log(this.editorOptions);
      this.editor = new Editor({
        ...this.editorOptions,
        el: this.$refs.toastuiEditor,
      });

      this.editor.on("change", () => {
        this.$emit("input", this.getValue());
      });
    },
    destroyEditor() {
      if (!this.editor) return;
      this.editor.off("change");
      this.editor.remove();
    },
    setValue(value) {
      this.editor.setValue(value);
    },
    getValue() {
      return this.editor.getMarkdown();
    },
  },
};
</script>

<style>
.tui-editor-defaultUI button {
  line-height: 100%;
}
</style>
