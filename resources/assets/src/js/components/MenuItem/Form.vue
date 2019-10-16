<template>
  <div>
    <div class="form-group p-0 mb-2 mt-2">
      <label class="block" :for="'title['+index+']'">Link title</label>
      <input type="text" :id="'title['+index+']'" v-model="item.title" class="w-full">
    </div>
    <div class="form-group flex p-0 mb-2 mt-2">
      <div class="flex-1 pr-1">
        <label class="block" :for="'id['+index+']'">CSS ID</label>
        <input type="text" :id="'id['+index+']'" v-model="item.attributes.id" class="w-full">
      </div>
      <div class="flex-1 pl-1 pr-1">
        <label class="block" :for="'class['+index+']'">CSS classes</label>
        <input type="text" :id="'class['+index+']'" v-model="item.attributes.class" class="w-full">
      </div>
      <div class="flex-1 pl-1">
        <label class="block" :for="'rel['+index+']'">Rel</label>
        <input type="text" :id="'rel['+index+']'" v-model="item.attributes.rel" class="w-full">
      </div>
    </div>
    <div class="form-group p-0 mb-2 mt-2">
      <div class="pr-1 w-1/3">
        <label class="block" :for="'target['+index+']'">Target</label>
        <select-fieldtype :options="targets" :data.sync="item.attributes.target"></select-fieldtype>
      </div>
    </div>
    <div class="form-group p-0">
      <button class="btn btn-default" @click.prevent="cancelEdit">Cancel</button>
      <button class="btn btn-primary" @click.prevent="updateItem">Save</button>
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
      updateItem() {
        this.$emit('update', this.index);
      },
      cancelEdit() {
        this.$emit('cancel', this.index);
      }
    }
  }
</script>