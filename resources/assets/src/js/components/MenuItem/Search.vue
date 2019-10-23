<template>
  <div>
    <div class="form-group p-0 mb-2 mt-2">
      <label class="block" for="page">Page</label>
      <input type="text" id="page" class="w-full" @input="searchContent($event) | debounce 500">

      <div>
        <ul class="shadow p-0">
          <li class="block p-2 border-b-2 border-bg-light border-solid hover:text-blue cursor-pointer" v-for="item in suggestions" @click="selectSuggetion(item.id)">{{ item.title }} <small class="text-muted">({{ item.type }})</small></li>
        </ul>
      </div>
    </div>
    <div class="form-group p-0">
      <button class="btn btn-default" @click.prevent="cancelEdit">Cancel</button>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      locale: {
        type: String
      }
    },
    data() {
      return {
        suggestions: []
      }
    },
    methods: {
      cancelEdit() {
        this.$emit('cancel', this.index);
      },
      searchContent(e) {
        const search = e.target.value;
        if (search.length === 0) {
          this.resetSuggestions();
        } else {
          this.$http.get(cp_url('addons/menu/'+this.locale+'/items/search?s='+encodeURI(search)))
            .then((response) => {
              this.resetSuggestions();
              for (let i = 0; i < response.data.suggestions.length; i++) {
                this.suggestions.push(response.data.suggestions[i]);
              }
            });
        }
      },
      selectSuggetion(id) {
        this.resetSuggestions();
        this.$emit('selected', id);
      },
      resetSuggestions() {
        this.suggestions.splice(0, this.suggestions.length);
      }
    }
  }
</script>
