<template>
  <div>
    <div class="card flush">
      <ul class="menu-tree p-0">
        <li v-for="(index, item) in items" class="p-1 flex items-center menu-tree__item shadow-inner">
          <div class="menu-tree__handle"></div>
          <div class="flex-1">
            {{ item.title }} <small class="text-muted">({{ item.type }})</small>
            <div class="menu-tree__edit-item" v-show="editItems[index]">
              <menu-item-form :item="item" :index="index" @update="updateItem" @cancel="cancelEdit"></menu-item-form>
            </div>
          </div>
          <div class="pl-3">
            <div class="btn-group action-more">
              <button type="button" class="btn-more dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon icon-dots-three-vertical"></i> </button>
              <ul class="dropdown-menu">
                <li>
                  <a @click.prevent="editItem(index)">Edit</a>
                </li>
                <li class="warning">
                  <a @click.prevent="deleteItem(index)" title="Delete this item">Delete</a>
                </li>
              </ul>
            </div>
          </div>
        </li>
      </ul>
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
      }
    },
    data() {
      return {
        editItems: []
      };
    },
    ready() {
      const countItems = this.items.length;
      for (let i = 0; i < countItems; i++) {
        this.editItems.push(false);
      }
    },
    methods: {
      editItem(index) {
        this.editItems.$set(index, !this.editItems[index]);
      },
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
      updateItem(index) {
        this.$http.put(cp_url('addons/menu/'+this.menu+'/item/'+index), {
          data: this.items[index]
        }).then((response) => {
          this.cancelEdit(index);
        });
      },
      cancelEdit(index) {
        this.editItems.$set(index, false);
      }
    }
  }
</script>