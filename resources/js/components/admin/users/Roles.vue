<template>
  <div class="container-fluid">
    <div class="card">
          <b-button class="mb-2" variant="primary" @click="$bvToast.show('example-toast')">
      Show toast
    </b-button>
    <b-toast id="example-toast" title="BootstrapVue" static no-auto-hide>
      Hello, world! This is a toast message.
    </b-toast>
      <b-button @click.prevent="makeToast(true)">Show Toast (appended)</b-button>
      <div class="card-header">
        <h3 class="card-title pull-right">Διαχείριση Ρόλων Χρηστών</h3>
        <a
          class="btn btn-outline-primary btn-sm float-right"
          v-b-modal.roleModal
          @click="showModal()">
        <i class="fas fa-plus"></i> Νέος Ρόλος</a>
      </div>
      <div class="card-body">
        <b-table
          hover
          ref="table"
          class="table"
          :items="roles"
          :fields="fields"
        >
        <template #cell(actions)="row">
            <b-button
              size="sm"
              variant="primary"
              v-b-modal.roleModal
              @click="showModal(row.item, row.index)"
              class="mr-1"
            >
              <i class="fas fa-edit"></i>
            </b-button>
            <b-button
              size="sm"
              variant="danger"
              v-b-modal.roleModal
              @click="showModal(row.item, row.index)"
              class="mr-1"
            >
              <i class="fas fa-trash"></i>
            </b-button>
          </template>

        </b-table>
      </div>
    </div>

    <!-- Modal -->
    <b-modal 
    id="roleModal" 
    title="Νέα Κατάσταση" 
    ref="someModal"
    >
      <form role="form">
        <div class="card-body">
          <div class="form-group">
            <input type="hidden" v-model="modalData.id" />
            <input
              type="text"
              class="form-control"
              id="input-name"
              placeholder="Όνομα"
              v-model="modalData.name"
            />
            <input
              type="text"
              class="form-control"
              id="input-slug"
              placeholder="slug"
              v-model="modalData.slug"
            />
          </div>
          <!-- <div class="form-group">
            <label>Ενεργοποιημένο</label>
            <select
              v-model="modalData.status"
              :selected="modalData.status"
              class="form-control"
            >
              <option value="1">Ναι</option>
              <option value="0">Όχι</option>
            </select>
          </div> -->
        </div>
      </form>
      <template #modal-footer="{ cancel }">
        <b-button size="sm" variant="success" @click="saveRole">
          Αποθήκευση
        </b-button>
        <b-button size="sm" variant="danger" @click="cancel()">
          Ακύρωση
        </b-button>
      </template>
    </b-modal>
    <!-- /Modal -->
  </div>
</template>
<script>
export default {
  data() {
    return {
      roles: [],
      modalData: {},
      fields: [
        { key: "id", label: "Id" },
        { key: "name", label: "Όνομα" },
        { key: "slug", label: "Slug" },
        { key: "actions", label: "Ενέργειες" },
      ],
      index: -1,
    };
  },
  async created() {
    const res = await this.callApi("get", "/getRoles", this.data);
    this.roles = res.data;
  },
  methods: {
    showModal(data, index) {
      let obj = {
        id: data ? data.id : '',
        name: data ? data.name : '',
        slug: data ? data.slug : '',
      };
      this.index = data ? index : -1;
      
      this.modalData = obj;
    },
    makeToast(append) {
      this.makeToaster(append);
    },
    async saveRole() {
      if (this.modalData.name.trim() == "") return alert();

      const res = await this.callApi(
        "post",
        "/saveRole",
        this.modalData
      );
      if (res.status == 200 || res.status == 201) {
        if(this.index != -1) {
          this.roles[this.index] = res.data;
        } else {
          this.roles.unshift(res.data);
        }
        this.$refs.table.refresh();
        this.$refs.someModal.hide();
        this.makeToaster();
      } else {
        // this.swr("");
      }
    },
  }
};
</script>