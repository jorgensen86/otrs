<template>
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title pull-right">Tickets</h3>
        <a
          class="btn btn-primary btn-sm float-right"
          v-b-tooltip.hover 
          title="Νέο Ticket"
          @click="showModal(data)"
          ><i class="fas fa-plus"></i
        ></a>
      </div>
      <div class="card-body">
        <b-table
          hover
          ref="table"
          class="table"
          :items="tickets"
          :fields="fields"
          :perPage="5"
        >
        <template #cell(user)="row">
           {{ row.value.name }}
          </template>
        <template #cell(actions)="row">
            <router-link
              size="sm"
              variant="info"
              class="mr-1"
              :to="{ name: 'editTicket', params: { id: row.item.id} }"
            >
              <i class="fas fa-eye"> </i>
            </router-link>
          </template>
        </b-table>
      </div>
    </div>
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
      fields: [
        { key: "id", label: "Ticket Nr" },
        { key: "user", label: "Όνομα" },
        { key: "status", label: "Κατάσταση" },
        { key: "subject", label: "Θέμα" },
        // { key: "message", label: "Ενέργειες" },
        { key: "created_at", label: "Ενέργειες" },
        { key: "actions", label: "Ενέργειες" },
      ],
      tickets: [],
      index: -1,
    };
  },
  async created() {
    const res = await this.callApi("get", "/getTickets", this.data);
    this.tickets = res.data;
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
        this.tickets[this.index] = res.data;
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