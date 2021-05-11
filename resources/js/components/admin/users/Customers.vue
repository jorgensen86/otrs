<template>
  <div class="container-fluid">
    <div v-if="showSpinner" class="d-flex justify-content-center mb-3">
      <b-spinner label="Loading..."></b-spinner>
    </div>
    <div v-if="!showSpinner" class="card">
      <div class="card-header">
        <h3 class="card-title">Πελάτες</h3>
        <div class="btn-group float-right">
          <b-button
            class="mr-2"
            variant="outline-primary"
            size="sm"
            role="button"
            v-b-modal.customerModal
            @click="showModal()"
          >
            <i class="fas fa-plus"></i> Νέος Πελάτης
          </b-button>
          <b-button
            :disabled="deleteIds.length > 0 ? false : true"
            variant="outline-danger"
            size="sm"
            role="button"
            @click="deleteCustomers()"
          >
            <i class="fas fa-trash"></i> Διαγραφή Πελάτη
          </b-button>
        </div>
      </div>
      <div class="card-body">
        <b-table
          bordered
          responsive
          hover
          show-empty
          :total-rows="total"
          :per-page="perPage"
          :items="customers"
          :fields="fields"
          ref="table"
        >
          <template #cell(id)="row">
            <b-form-checkbox v-model="deleteIds" :value="row.item.id">
            </b-form-checkbox>
          </template>
          <template #cell(domain)="row">
            <a
              v-if="row.item.customer_info"
              :href="'http://' + row.item.customer_info.domain"
              target="_blank"
              >{{ row.item.customer_info.domain }}</a
            >
          </template>
          <template #cell(invoice)="row">
            <span v-if="row.item.customer_info">{{
              row.item.customer_info.invoice
            }}</span>
          </template>
          <template #cell(status)="row">
            <b-badge pill variant="success" v-if="row.value">Ναι </b-badge>
            <b-badge pill variant="danger" v-else>Όχι </b-badge>
          </template>
          <template #cell(actions)="row">
            <div class="btn-group">
              <b-button
                size="sm"
                variant="outline-primary"
                v-b-modal.customerModal
                @click="showModal(row.item, row.index)"
                class="mr-1"
              >
                <i class="fas fa-edit"></i>
              </b-button>
            </div>
          </template>
        </b-table>
      </div>
      <div class="card-footer">
        <b-pagination
          v-model="currentPage"
          :total-rows="total"
          :per-page="perPage"
          pills
          @change="changePage"
          class="my-0"
        ></b-pagination>
      </div>
    </div>
    <b-modal
      id="customerModal"
      size="lg"
      :title="modalData.id ? 'Επεξεργασία Πελάτη' : 'Προσθήκη Πελάτη'"
    >
      <b-form @submit.prevent="saveCustomer">
        <input type="hidden" name="id" :value="modalData.id" />
        <b-form-group
          id="input-invoice"
          label="Όνομα:"
          label-for="input-invoice"
        >
          <b-form-input
            id="input-invoice"
            type="text"
            v-model="modalData.invoice"
            placeholder="Όνομα"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group id="input-name" label="Όνομα:" label-for="input-name">
          <b-form-input
            id="input-name"
            type="text"
            v-model="modalData.name"
            placeholder="Όνομα"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group id="input-email" label="Email:" label-for="input-email">
          <b-form-input
            id="input-email"
            type="email"
            v-model="modalData.email"
            placeholder="Email"
            required
          ></b-form-input>
        </b-form-group>
        <b-form-group
          id="input-status"
          label="Κατάσταση"
          label-for="input-status"
        >
          <b-form-select
            v-model="modalData.status"
            :options="{ 0: 'Όχι', 1: 'Ναι' }"
          ></b-form-select>
        </b-form-group>
        <b-form-group
          id="input-password"
          label="Κωδικός:"
          label-for="input-password"
        >
          <b-form-input
            id="input-password"
            type="password"
            v-model="modalData.password"
            placeholder="Κωδικός"
          ></b-form-input>
        </b-form-group>
        <b-button type="submit" variant="primary">Submit</b-button>
      </b-form>
    </b-modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      customers: [],
      roles: [],
      showSpinner : true,
      deleteIds: [],
      modalData: [],
      currentPage: 1,
      total: 0,
      perPage: 20,
      fields: [
        { key: "id", label: "" },
        { key: "name", label: "Όνομα Πελάτη" },
        { key: "invoice", label: "Συμβόλαιο" },
        { key: "domain", label: "Domain" },
        { key: "email" },
        { key: "status", label: "Κατάσταση", class: "text-center" },
        { key: "status", label: "Ενεργοποιημένος", class: "text-center" },
        { key: "actions", label: "Ενέργειες", class: "text-right" },
      ],
      index: -1,
    };
  },
  created() {
    this.getData();
  },

  methods: {
    async getData() {
      const [res, roles] = await Promise.all([
        this.callApi("get", "/getCustomers", { page: this.currentPage }),
        this.callApi("get", "/getRoles", { page: 1 }),
      ]);
      this.total = res.data.total;
      this.customers = res.data.data;
      this.roles = roles.data;
      this.showSpinner = false;
    },
    changePage(value) {
      this.currentPage = value;
      this.getData();
    },
    showModal(data, index) {
      let user = {
        id: data ? data.id : "",
        invoice: data ? data.invoice : "",
        name: data ? data.name : "",
        email: data ? data.email : "",
        password: data ? data.password : "",
        status: data ? data.status : 0,
        role_id: data ? data.role_id : 0,
      };

      this.index = index ? index : -1;

      this.modalData = user;
    },
    async saveUser() {
      const res = await this.callApi("post", "/saveUser", this.modalData);

      if (res.status == 200 || res.status == 201) {
        this.customers.push(res.data);
        this.$refs.table.refresh();
        this.makeToaster();
      } else {
        alert();
      }
    },
    async deleteCustomers() {
      const res = await this.callApi("post", "/deleteUser", this.deleteIds);
      this.$refs.table.refresh();
      this.makeToaster(
        "Οι χρήστες διαγράφηκαν επιτυχώς!",
        "Επιτυχία",
        "success"
      );
    },
  },
};
</script>