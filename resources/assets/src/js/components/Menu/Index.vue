<template>
  <div>
    <div class="card flush">
      <table class="dossier">
        <tbody>
        <tr v-for="(index, item) in items">
          <td class="cell-title first-cell">
            <span class="column-label">Title</span>
            <a :href="cp_url('addons/menu/'+item['slug']+'/items')">{{ item['name'] }} <small class="text-muted">({{ item['locale'] }})</small></a>
          </td>

          <td class="cell-slug">
            <span class="column-label">Tag</span>
            <span><code><span v-pre>&lcub;&lcub;</span> menu src="{{ item['slug'] }}" <span v-pre>&rcub;&rcub;</span></code></span>
          </td>

          <td class="column-actions">
            <div class="btn-group action-more">
              <button type="button" class="btn-more dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon icon-dots-three-vertical"></i> </button>
              <ul class="dropdown-menu">
                <li class="warning">
                  <a @click.prevent="deleteMenu(index)" title="Delete this menu">Delete</a>
                </li>
              </ul>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      items: {
        type: Array,
        default: []
      }
    },
    methods: {
      deleteMenu(index) {
        const slug = this.items[index].slug;

        swal({
          title: translate('cp.are_you_sure'),
          text: translate_choice('cp.confirm_delete_items', 1),
          type: 'warning',
          confirmButtonText: translate('cp.yes_im_sure'),
          showCancelButton: true,
          closeOnConfirm: false
        }, (canDelete) => {
          if (canDelete) {
            this.$http.delete(cp_url('addons/menu/menu'), {
              slug: slug
            }).then((response) => {
              location.href = response.data.redirect;
            });
          }
        });
      }
    }
  }
</script>