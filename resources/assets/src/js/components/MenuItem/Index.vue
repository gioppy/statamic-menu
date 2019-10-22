<template>
  <div>
    <div class="flex items-center mb-3">
      <h1 class="w-full text-center mb-2 md:mb-0 md:text-left md:w-auto md:flex-1">Menu items <small class="text-muted">({{ title }} - {{ locale }})</small></h1>
      <button class="btn btn-primary btn-icon" type="button" @click.prevent="addPage">
        <span class="icon icon-text-document-inverted"></span>
      </button>
      <button class="btn btn-primary btn-icon" type="button" @click.prevent="addCustom">
        <span class="icon icon-link"></span>
      </button>
    </div>

    <div class="flex justify-between">
      <div class="w-1/2">
        <div class="page-tree">
          <ul class="p-0">
            <li v-dragable-for="(index, item) in items" class="block">
              <menu-item
                :item="item"
                :index="index"
                @update="updateItem"
                @delete="deleteItem"
                @page="addNestedPage"
                @custom="addNestedCustom">
              </menu-item>
            </li>
          </ul>
        </div>

        <div class="pt-2 pb-2" v-if="createMode === null">
          <button type="button" class="btn btn-primary" @click.prevent="saveOrder">Save order</button>
        </div>
      </div>
      <div class="w-2/5">
        <div class="card" v-if="createMode === 'page'">
          <menu-item-search @cancel="closeCreation" @selected="savePage" :locale="locale"></menu-item-search>
        </div>

        <div class="card" v-if="createMode === 'custom'">
          <menu-item-form @cancel="closeCreation" @save="saveCustom" :show-url="true"></menu-item-form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      items: {
        type: Array,
        default: []
      },
      menu: {
        type: String
      },
      title: {
        type: String
      },
      locale: {
        type: String
      }
    },
    data() {
      return {
        createMode: null,
        saveAtIndex: null
      };
    },
    methods: {
      deleteItem(index) {
        swal({
          title: translate('cp.are_you_sure'),
          text: translate_choice('cp.confirm_delete_items', 1),
          type: 'warning',
          confirmButtonText: translate('cp.yes_im_sure'),
          showCancelButton: true,
          closeOnConfirm: false
        }, (canDelete) => {
          if (canDelete) {
            this.$http.delete(cp_url('addons/menu/'+this.menu+'/item/'+index))
              .then((response) => {
                location.reload();
              });
          }
        });
      },
      updateItem(index, item) {
        this.$http.put(cp_url('addons/menu/'+this.menu+'/item/'+index), { item: item })
          .then((response) => {
            this.$dispatch("setFlashSuccess", response.data.message);
          });
      },
      saveOrder() {
        this.$http.put(cp_url('addons/menu/'+this.menu), { items: this.items })
          .then((response) => {
            this.$dispatch("setFlashSuccess", response.data.message);
          });
      },
      addPage() {
        this.createMode = 'page';
      },
      addNestedPage(index) {
        this.saveAtIndex = index;
        this.createMode = 'page';
      },
      addCustom() {
        this.createMode = 'custom';
      },
      addNestedCustom(index) {
        this.saveAtIndex = index;
        this.createMode = 'custom';
      },
      savePage(id) {
        this.$http.post(cp_url('addons/menu/'+this.menu+'/item'), { 'page': id, 'parent': this.saveAtIndex })
          .then((response) => {
            location.reload();
          });
      },
      saveCustom(data) {
        if (this.validateData(data)) {
          // make HTTP request to save custom link
          this.$http.post(cp_url('addons/menu/'+this.menu+'/item'), { 'data': data, 'parent': this.saveAtIndex })
            .then((response) => {
              location.reload();
            });
        } else {
          this.$dispatch("setFlashError", 'Uh oh! URL and Title cannot be empty.');
        }
      },
      closeCreation() {
        this.saveAtIndex = null;
        this.createMode = null;
      },
      validateData(data) {
        if (!data) {
          return false;
        }

        return !(!data.url || !data.title);
      }
    }
  }
</script>