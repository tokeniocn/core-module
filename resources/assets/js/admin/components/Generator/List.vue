<template>
  <div class="q-pa-md">
    <div class="row q-col-gutter-xl">
      <div v-for="(generator, index) in generators" :key="index" class="col-3">
        <q-card>
          <q-card-section class="bg-teal text-white card-section">
            <div class="text-h6">{{generator.title}}</div>
            <div class="text-subtitle2">{{generator.description}}</div>
          </q-card-section>

          <q-separator />

          <q-card-actions align="right">
            <q-btn flat @click.stop="handleGenerate(generator.key)">开始生成</q-btn>
          </q-card-actions>
        </q-card>
      </div>
    </div>
    <q-dialog
      v-model="dialogShow"
      persistent
      :maximized="true"
      transition-show="slide-up"
      transition-hide="slide-down"
    >
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">{{ generator.title }}</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <component :is="generateKey"></component>
      </q-card>
    </q-dialog>
  </div>
</template>

<script>
import Model from "./Model";

export default {
  components: {
    model: Model
  },
  data() {
    return {
      dialogShow: false,
      generateKey: null,
      generators: [
        {
          key: "model",
          title: "模型生成",
          description: "生成基于数据表结构生成Laravel的Model代码"
        },
        {
          key: "crud",
          title: "CRUD生成",
          description:
            "生成基于Laravel的Model结构生成的CRUD(create, edit, list, delete)基础代码页面"
        },
        {
          key: "module",
          title: "模块生成",
          description: "生成基于laravel-modules的laravel模块"
        }
      ]
    };
  },
  computed: {
    generator() {
      return (
        this.generators.find(generator => this.generateKey == generator.key) ||
        {}
      );
    }
  },
  methods: {
    handleGenerate(key) {
      this.dialogShow = true;
      this.generateKey = key;
    }
  }
};
</script>

<style lang="scss" scoped>
.card-section {
  min-height: 150px;
}
</style>