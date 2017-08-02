<template>
  <div class="vendor-menu-list">
    <div style="margin-bottom: 25px; font-weight: bold">Menu List</div>
    <div style="padding: 10px; background: #ececec; margin-bottom: 10px">
      <form @submit.prevent="fetchMenus">
        <input type="text" v-model="query" class="form-control" placeholder="Search menu">
      </form>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Menu</th>
          <th>Price</th>
          <th style="width: 64px">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="menu in menus">
          <td>{{ menu.name }}</td>
          <td>{{ menu.price | rupiah }}</td>
          <td>
            <action-options-popover>
              <action-options-popover-menu
                :payload="menu.id"
                event-name="menu-detail"
                v-on:menu-detail="toDetailPage"
              >
                Detail
              </action-options-popover-menu>
              <action-options-popover-menu
                :payload="menu.id"
                event-name="menu-edit"
                v-on:menu-edit="toEditPage"
              >
                Edit
              </action-options-popover-menu>
              <hr style="margin: 0">
              <action-options-popover-menu>
                Delete
              </action-options-popover-menu>
            </action-options-popover>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="list-pagination">
      <ul class="pager">
        <li style="margin-right: 10px">Page {{ currentPage }} / {{ pageCount }}</li>
        <li :class="{ disabled: !isPrevAvailable }"><a href="#" @click="getPrevPage()">Previous</a></li>
        <li :class="{ disabled: !isNextAvailable }"><a href="#" @click="getNextPage()">Next</a></li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: ['vendorId'],
  data() {
    return {
      menus: [],
      query: '',
      currentPage: 1,
      pageCount: 1,
    };
  },
  mounted() {
    this.fetchMenus();
  },
  computed: {
    isPrevAvailable() {
      return this.currentPage > 1;
    },
    isNextAvailable() {
      return this.currentPage < this.pageCount;
    },
  },
  methods: {
    fetchMenus({ page = 1 } = {}) {
      axios.get(`/api/v1/menus?page=${page}&vendor_id=${this.vendorId}&query=${this.query}`)
        .then(({ data }) => {
          this.menus = data.data;
          this.currentPage = data.current_page;
          this.pageCount = data.last_page;
        });
    },
    getPrevPage() {
      if (this.currentPage > 1) {
        this.fetchMenus({ page: this.currentPage - 1});
      }
    },
    getNextPage() {
      if (this.currentPage < this.pageCount) {
        this.fetchMenus({ page: this.currentPage + 1});
      }
    },
    toEditPage(menuId) {
      window.location.href = `/ap/menus/${menuId}/edit`;
    },
    toDetailPage(menuId) {
      window.location.href = `/ap/menus/${menuId}`;
    },
  }
};
</script>
