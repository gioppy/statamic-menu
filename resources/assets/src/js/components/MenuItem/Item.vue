<template>
  <div>
    <div class="flex bg-white shadow">
      <div class="flex items-center shadow-inner w-full">
        <div class="page-move drag-handle w-6 h-full"></div>
        <div class="flex-1 p-1">
          {{ item.title }} <small class="text-muted">({{ item.type }})</small>
          <div v-show="edit">
            <menu-item-form :item="item" :index="index" @update="handleUpdate" @cancel="cancelEdit"></menu-item-form>
          </div>
        </div>
        <div class="p-1">
          <div class="btn-group action-more">
            <button class="btn btn-small btn-icon" type="button" @click.prevent="handleNestedAddPage" v-if="!edit">
              <span class="icon icon-text-document-inverted"></span>
            </button>
            <button class="btn btn-small btn-icon" type="button" @click.prevent="handleNestedAddCustom" v-if="!edit">
              <span class="icon icon-link"></span>
            </button>
            <button type="button" class="btn-more dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon icon-dots-three-vertical"></i> </button>
            <ul class="dropdown-menu">
              <li>
                <a @click.prevent="showForm">Edit</a>
              </li>
              <li class="warning">
                <a @click.prevent="handleDelete" title="Delete this item">Delete</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div>
      <ul class="p-0" v-if="item.items.length > 0">
        <li v-dragable-for="(index, item) in item.items" class="block">
          <menu-item :item="item" :index="index" :paths="path" class="ml-2"></menu-item>
        </li>
      </ul>
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
      paths: {
        coerce: function (val) {
          return val + '';
        }
      }
    },
    data() {
      return {
        edit: false
      }
    },
    computed: {
      path: function() {
        if (this.paths === 'undefined') {
          return this.index;
        }
        return this.paths + '.items.' + this.index;
      }
    },
    methods: {
      showForm() {
        this.edit = !this.edit;
      },
      handleDelete() {
        this.$dispatch('delete', this.path);
      },
      cancelEdit(index) {
        this.showForm();
      },
      handleUpdate() {
        this.$dispatch('update', this.path, this.item);
        this.showForm();
      },
      handleNestedAddPage() {
        this.$dispatch('page', this.path);
      },
      handleNestedAddCustom() {
        this.$dispatch('custom', this.path);
      }
    }
  }
</script>
