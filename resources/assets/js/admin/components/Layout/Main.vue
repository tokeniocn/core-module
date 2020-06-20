<template>
  <q-layout view="lHh lpR lFf">
    <q-header class="bg-white text-grey-9" bordered height-hint="98">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="left = !left" />
        <q-btn v-if="tabs.length" @click.stop="refreshPage" dense flat round icon="refresh" />
        <slot name="toolbar-left"></slot>

        <q-space />

        <slot name="toolbar-right"></slot>
      </q-toolbar>

      <page-tab :tabs="tabs" :activeTab="activeTab" @switch="toggleTab" @remove="removeTab"></page-tab>
    </q-header>

    <q-drawer
      content-class="bg-grey-10 text-white"
      show-if-above
      v-model="left"
      side="left"
      behavior="desktop"
      :width="230"
      bordered
    >
      <q-scroll-area class="fit" dark>
        <q-toolbar class="text-center">
          <q-toolbar-title>{{ $config.name }}</q-toolbar-title>
        </q-toolbar>

        <left-menu v-if="tree" :tree="tree" :activeMenu="activeTab" @nav="toggleTab"></left-menu>
      </q-scroll-area>
    </q-drawer>

    <q-page-container :style="style">
      <div
        v-for="(tab, index) in tabs"
        :key="index"
        class="content"
        :class="{ show: activeTab && activeTab.id == tab.id }"
      >
        <iframe :ref="`page_${tab.id}`" frameborder="0" :src="tab.url" class="iframe"></iframe>
      </div>
    </q-page-container>
  </q-layout>
</template>

<script>
import LeftMenu from "./LeftMenu";
import PageTab from "./PageTab";
import { mapState, mapGetters, mapActions } from "vuex";
export default {
  components: {
    LeftMenu,
    PageTab
  },
  data() {
    return {
      left: false,
      height: this.$q.screen.height
    };
  },
  created() {
    this.loadTree();
  },
  computed: {
    ...mapState("menu", ["tree"]),
    ...mapGetters("menu", ["tabs", "activeTab"]),
    style() {
      return { height: this.$q.screen.height + "px" };
    }
  },
  methods: {
    ...mapActions("menu", ["loadTree", "toggleTab", "removeTab"]),
    refreshPage() {
      const key = `page_${this.activeTab.id}`;
      if (this.$refs[key]) {
        const $el = this.$refs[key][0];
        $el.contentWindow.location.reload(!0);
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.content {
  position: relative;
  height: 100%;
  width: 100%;
  display: none;

  &.show {
    display: block;
  }

  .iframe {
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
  }
}
</style>
