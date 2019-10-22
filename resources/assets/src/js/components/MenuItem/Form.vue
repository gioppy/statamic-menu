<template>
  <div>
    <div class="form-group p-0 mb-2 mt-2" v-if="showUrl">
      <label class="block" :for="'url['+index+']'">URL</label>
      <input type="text" :id="'url['+index+']'" v-model="item.url" class="w-full">
    </div>
    <div class="form-group p-0 mb-2 mt-2">
      <label class="block" :for="'link_title['+index+']'">Link title</label>
      <input type="text" :id="'link_title['+index+']'" v-model="item.title" class="w-full">
    </div>
    <div class="form-group flex p-0 mb-2 mt-2">
      <div class="flex-1 pr-1">
        <label class="block" :for="'title['+index+']'">Title</label>
        <input type="text" :id="'title['+index+']'" v-model="item.attributes.title" class="w-full">
      </div>
      <div class="flex-1 pr-1 pl-1">
        <label class="block" :for="'id['+index+']'">CSS ID</label>
        <input type="text" :id="'id['+index+']'" v-model="item.attributes.id" class="w-full">
      </div>
      <div class="flex-1 pl-1">
        <label class="block" :for="'class['+index+']'">CSS classes</label>
        <input type="text" :id="'class['+index+']'" v-model="item.attributes.class" class="w-full">
      </div>
    </div>
    <div class="form-group flex p-0 mb-2 mt-2">
      <div class="flex-1 pr-1">
        <label class="block" :for="'rel['+index+']'">Rel</label>
        <input type="text" :id="'rel['+index+']'" v-model="item.attributes.rel" class="w-full">
      </div>
      <div class="flex-1 pl-1">
        <label class="block" :for="'target['+index+']'">Target</label>
        <select-fieldtype :options="targets" :data.sync="item.attributes.target"></select-fieldtype>
      </div>
    </div>
    <div class="form-group p-0">
      <button class="btn btn-default" @click.prevent="cancelEdit">Cancel</button>
      <button class="btn btn-primary" @click.prevent="updateItem" v-if="!showUrl">Save</button>
      <button class="btn btn-primary" @click.prevent="saveItem" v-if="showUrl">Save</button>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      item: {
        type: Object
      },
      index: {
        type: Number
      },
      showUrl: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        targets: [
          {
            value: '_self',
            text: 'Same window'
          },
          {
            value: '_blank',
            text: 'New window'
          }
        ]
      }
    },
    methods: {
      saveItem() {
        this.$emit('save', this.item);
      },
      updateItem() {
        this.$emit('update', this.index);
      },
      cancelEdit() {
        this.$emit('cancel', this.index);
      }
    }
  }
</script>