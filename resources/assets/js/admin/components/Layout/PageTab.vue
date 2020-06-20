<template>
  <div class="page-tab">
    <q-tabs align="left" :value="activeTab.id" dense no-caps inline-label>
      <!-- <q-tab name="首页" label="首页" @click.stop></q-tab> -->
      <q-tab
        v-for="tab in tabs"
        :key="tab.id"
        :name="tab.id"
        :label="tab.title"
        @click.stop="switchTab(tab)"
      >
        <q-btn class="close" icon="close" size="xs" flat round dense @click.stop="removeTab(tab)" />
      </q-tab>
    </q-tabs>
  </div>
</template>

<script>
export default {
  props: {
    tabs: {
      type: Array,
      default: []
    },
    activeTab: {
      type: Object,
      default: {}
    }
  },
  methods: {
    switchTab(tab) {
      this.$emit("switch", tab);
    },
    removeTab(tab) {
      this.$emit("remove", tab);
    }
  }
};
</script>

<style lang="scss">
.page-tab {
  border-top: 1px solid #f6f6f6;

  .q-tab {
    padding: 0 25px;
    .close {
      position: absolute;
      right: -20px;
      top: 9px;
      display: none;
    }

    &.q-tab--active .close,
    &:hover .close {
      display: block;
    }

    @media (min-width: 1440px) {
      .q-tab__content {
        min-width: 64px;
      }
    }
  }
}
</style>
<style lang="scss" scoped>
</style>
