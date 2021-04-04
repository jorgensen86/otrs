<template>
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title pull-right">Κατάσταση Ticket</h3>
        <a
          class="btn btn-primary btn-sm float-right"
          v-b-modal.statusModal
          @click="showModal(data)"
          ><i class="fas fa-plus"></i
        ></a>
      </div>
      <div class="card-body">
        <b-table
          hover
          ref="table"
          class="table"
          :items="statuses"
          :fields="fields"
        >
          <template #cell(id)="row">
            <input type="checkbox" name="select[]" :value="row.value" />
          </template>
          <template #cell(default)="row">
            <input type="radio" name="default" :value="row.id" />
          </template>
          <template #cell(status)="row">
            <span v-if="row.value" class="p-2 badge bg-success"> Ναι </span>
            <span v-else class="p-2 badge bg-danger"> Όχι </span>
          </template>
          <template #cell(actions)="row">
            <b-button
              size="sm"
              variant="primary"
              v-b-modal.statusModal
              @click="showModal(row.item, row.index)"
              class="mr-1"
            >
              <i class="fas fa-edit"></i>
            </b-button>
            <b-button
              size="sm"
              variant="danger"
              v-b-modal.statusModal
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
    id="statusModal" 
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
          </div>
          <div class="form-group">
            <label>Ενεργοποιημένο</label>
            <select
              v-model="modalData.status"
              :selected="modalData.status"
              class="form-control"
            >
              <option value="1">Ναι</option>
              <option value="0">Όχι</option>
            </select>
          </div>
        </div>
      </form>
      <template #modal-footer="{ cancel }">
        <b-button size="sm" variant="success" @click="saveStatus">
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
      data: {
        id: null,
        name: "",
        status: "",
      },
      modalData: {},
      fields: [
        { key: "id", label: "" },
        { key: "name", label: "Όνομα" },
        {
          key: "status",
          label: "Ενεργοποιημένο",
        },
        { key: "default", label: "Προεπιλογή" },
        { key: "actions", label: "Ενέργειες" },
      ],
      statuses: [],
      index: -1,
    };
  },
  async created() {
    const res = await this.callApi("get", "/getTicketStatuses", this.data);
    this.statuses = res.data;
  },
  methods: {
    async saveStatus() {
      if (this.modalData.name.trim() == "") return alert();

      const res = await this.callApi(
        "post",
        "/saveTicketStatus",
        this.modalData
      );
      if (res.status == 200 || res.status == 201) {
        this.statuses[this.index] = res.data;
        this.$refs.table.refresh();
        this.$refs.someModal.hide();
        // this.tags.unshift(res.data);
      this.makeToaster();

        // this.s("Tag added successfully!!");
        // this.addModal = false;
      } else {
        // this.swr("");
      }
    },
    showModal(data, index) {
      let obj = {
        id: data.id,
        name: data.name,
        status: data.status,
      };

      this.index = index;
      this.modalData = obj;
    },
    // resetModal() {
    //   this.modalData.id = '';
    //   this.modalData.name = '';
    //   this.modalData.status = 0;
    // },
  },
};
</script>