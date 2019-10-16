<template>
  <div>
    <div class="card">
      <div class="form-group">
        <label class="block">Menu Name</label>
        <input type="text" class="form-control mb-2" autofocus="autofocus" v-model="name">
        <small class="help-block">The name will be converted to a slugified version that you'll use to reference in your templates (i.e. main-menu). Don't start the name with a dot.</small>

        <label class="block">Menu Locale</label>
        <select-fieldtype :options="locales" :data.sync="locale"></select-fieldtype>
      </div>

      <div class="form-group">
        <button type="button" class="btn btn-primary" @click.prevent="saveMenu">Save menu</button>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      locales: {
        type: Array,
        default: []
      }
    },
    data() {
      return {
        name: '',
        locale: ''
      };
    },
    methods: {
      saveMenu() {
        this.$http.post(cp_url('addons/menu/menu'), {
          name: this.name,
          locale: this.locale
        }).then((response) => {
          location.href = response.data.redirect;
        });
      }
    }
  }
</script>