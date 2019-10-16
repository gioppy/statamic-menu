<template>
  <div>
    <div class="card flush">
      <div class="p-2" v-if="!creation">
        <button type="button" class="btn btn-default add-row" @click.prevent="addPage">Add saved content <i class="icon icon-plus icon-right"></i></button>
        <button type="button" class="btn btn-default add-row" @click.prevent="addCustom">Add custom link <i class="icon icon-plus icon-right"></i></button>
      </div>

      <div class="p-2" v-if="creation && createMode === 'page'">
        <menu-item-search @selected="savePage" @cancel="close" :locale="locale"></menu-item-search>
      </div>
      <div class="p-2" v-if="creation && createMode === 'custom'">
        <menu-item-form @cancel="close"></menu-item-form>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      menu: {
        type: String
      },
      locale: {
        type: String
      }
    },
    data() {
      return {
        creation: false,
        createMode: null
      }
    },
    methods: {
      addPage() {
        this.creation = true;
        this.createMode = 'page';
      },
      savePage(id) {
        // make HTTP request to save page id
        this.$http.post(cp_url('addons/menu/'+this.menu+'/item'), { 'page': id })
          .then((response) => {
            location.reload();
          });
      },
      addCustom() {
        this.creation = true;
        this.createMode = 'custom';
      },
      close() {
        this.creation = false;
        this.createMode = null;
      }
    }
  }
</script>