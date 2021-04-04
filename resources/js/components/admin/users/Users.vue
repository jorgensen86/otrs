<template>
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title pull-right">Χρήστες Συστήματος</h3>
        <!-- <a
          class="btn btn-primary btn-sm float-right"
          v-b-modal.addStatus
          @click="showModal(data)"
          ><i class="fas fa-plus"></i
        ></a> -->
      </div>
      <div class="card-body">
        <b-table
          hover
          ref="table"
          class="table"
          :items="statuses"
          :fields="fields"
        >
        </b-table>
      </div>
    </div>

  </div>
</template>
<script>
export default {
  data() {
    return {
      users: [],
      modalData: [],
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
      index: -1
    }
  },
  async created() {
    const res = await this.callApi("get", "/getUsers", this.data);
    this.statuses = res.data;
  },
  methods: {
    async saveStatus() {
      console.log(this.modalData);
      if (this.modalData.name.trim() == "") return alert();

      const res = await this.callApi(
        "post",
        "/saveTicketStatus",
        this.modalData
      );
      if (res.status == 200 || res.status == 201) {
        this.statuses[this.index] = res.data;
        this.$refs.table.refresh();
        this.$refs.someModal.hide()
        // this.tags.unshift(res.data);
        // this.s("Tag added successfully!!");
        // this.addModal = false;
      } else {
        // this.swr("");
      }
    },
    showModal(data) {
      let obj = {
        id: data.item.id,
        name: data.item.name,
        status: data.item.status,
      };

      this.index = data.index;

      // item.id = String(item.id);
      this.modalData = obj;
    }
  }
};
</script>